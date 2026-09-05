<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class CartService
{
    protected function getCartKey(): string
    {
        if (Auth::check()) {
            return 'cart_user_' . Auth::id(); // Or 'cart:user:' standard format
        }

        return 'cart_guest_' . session()->getId();
    }

    // Add or update product in redis cart
    public function addOrUpdateProduct(int $productId, int $quantity, float $price, string $name, ?string $image = null): array
    {
        $cartKey = $this->getCartKey();

        $existing = Redis::hget($cartKey, $productId);
        $currentQty = $existing ? json_decode($existing, true)['quantity'] : 0;

        $itemData = json_encode([
            'product_id' => $productId,
            'name'       => $name,
            'price'      => $price,
            'quantity'   => $currentQty + $quantity,
            'image'      => $image,
        ]);

        Redis::hset($cartKey, $productId, $itemData);

        // Auto expire inactive guest cart after 7 days
        if (!Auth::check()) {
            Redis::expire($cartKey, 60 * 60 * 24 * 7);
        }

        return $this->getCartSummary();
    }

    // Remove product from redis cart
    public function removeProduct(int $productId): array
    {
        $cartKey = $this->getCartKey();
        Redis::hdel($cartKey, $productId);

        return $this->getCartSummary();
    }

    // Update specific item quantity on Cart Page
    public function updateProductQuantity(int $productId, int $quantity): array
    {
        $cartKey = $this->getCartKey();
        $existing = Redis::hget($cartKey, $productId);

        if ($existing) {
            $itemData = json_decode($existing, true);
            $itemData['quantity'] = $quantity;

            Redis::hset($cartKey, $productId, json_encode($itemData));
        }

        return $this->getCartSummary();
    }

    // Get realtime cart items and total count from redis with DB fallback
    public function getCartSummary(): array
    {
        $cartKey = $this->getCartKey();
        $rawItems = Redis::hgetall($cartKey);

        if (empty($rawItems)) {
            $rawItems = $this->restoreCartFromDatabase($cartKey);
        }

        $items = [];
        $totalCount = 0;
        $totalAmount = 0.0;

        foreach ($rawItems as $itemJson) {
            $item = json_decode($itemJson, true);
            $items[] = $item;
            $totalCount += $item['quantity'];
            $totalAmount += $item['price'] * $item['quantity'];
        }

        return [
            'items'        => $items,
            'total_count'  => $totalCount,
            'total_amount' => round($totalAmount, 2),
        ];
    }

    /**
     * FIX 1: Restore Cart according to new DB Schema (Carts + CartItems)
     */
    protected function restoreCartFromDatabase(string $cartKey): array
    {
        if (!Auth::check()) {
            return [];
        }

        $userId = Auth::id();

        // 1. Get user cart with cart items & products
        $cart = Cart::with('items.product')->where('user_id', $userId)->first();

        if (!$cart || $cart->items->isEmpty()) {
            return [];
        }

        $restoredRedisData = [];

        // 2. Store each item back into Redis
        foreach ($cart->items as $item) {
            $itemData = json_encode([
                'product_id' => $item->product_id,
                'name'       => $item->product->name ?? 'Product',
                'price'      => (float) $item->sale_price, // 'sale_price' as per migration
                'quantity'   => (int) $item->quantity,
                'image'      => $item->product->image_url ?? null,
            ]);

            Redis::hset($cartKey, $item->product_id, $itemData);
            $restoredRedisData[$item->product_id] = $itemData;
        }

        return $restoredRedisData;
    }

    /**
     * FIX 2: Hourly Cron Job Synchronization method for new DB structure
     */
    public function syncCartsToDatabase(): void
    {
        $keys = Redis::keys('cart_user_*');

        foreach ($keys as $key) {
            $userId = (int) str_replace('cart_user_', '', $key);
            $rawItems = Redis::hgetall($key);

            if (empty($rawItems)) {
                continue;
            }

            DB::transaction(function () use ($userId, $rawItems) {
                // 1. Ensure user has a entry in 'carts' table
                $cart = Cart::firstOrCreate(['user_id' => $userId]);

                // 2. Wipe old items to prevent duplication/stale data
                CartItem::where('cart_id', $cart->id)->delete();

                // 3. Prepare bulk insert data
                $insertData = [];
                $now = now();

                foreach ($rawItems as $productId => $itemJson) {
                    $item = json_decode($itemJson, true);

                    $insertData[] = [
                        'cart_id'    => $cart->id,
                        'product_id' => (int) $productId,
                        'quantity'   => (int) $item['quantity'],
                        'sale_price' => (float) $item['price'],
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                if (!empty($insertData)) {
                    CartItem::insert($insertData);
                }
            });
        }
    }
}
