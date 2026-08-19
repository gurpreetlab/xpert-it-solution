<?php

namespace App\Support;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Fluent;

class CartManager
{
    /**
     * Add a product to the cart with the given quantity.
     */
    public static function add(int $productId, int $quantity = 1): bool
    {
        $product = Product::find($productId);
        if (! $product || $product->stock <= 0) {
            return false;
        }

        if (auth()->check()) {
            $cart = auth()->user()->cart()->firstOrCreate();
            $item = $cart->items()->where('product_id', $productId)->first();

            if ($item) {
                if (($item->quantity + $quantity) > $product->stock) {
                    return false;
                }
                $item->increment('quantity', $quantity);
            } else {
                if ($quantity > $product->stock) {
                    return false;
                }
                $cart->items()->create([
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'sale_price' => $product->sale_price,
                ]);
            }

            return true;
        }

        // Guest Cart Session
        $guestCart = session()->get('guest_cart', []);
        $currentQty = $guestCart[$productId] ?? 0;
        $newQty = $currentQty + $quantity;

        if ($newQty > $product->stock) {
            return false;
        }

        $guestCart[$productId] = $newQty;
        session()->put('guest_cart', $guestCart);

        return true;
    }

    /**
     * Update item quantity.
     */
    public static function updateQuantity(int|string $itemId, int $quantity): bool
    {
        if (auth()->check()) {
            $cart = auth()->user()->cart;
            if (! $cart) {
                return false;
            }

            $item = $cart->items()->whereKey($itemId)->first();
            if (! $item) {
                return false;
            }

            if ($quantity <= 0) {
                $item->delete();

                return true;
            }

            if ($quantity > $item->product->stock) {
                return false;
            }

            $item->update(['quantity' => $quantity]);

            return true;
        }

        // Guest Cart Session
        $productId = (int) $itemId;
        $guestCart = session()->get('guest_cart', []);

        if (! isset($guestCart[$productId])) {
            return false;
        }

        if ($quantity <= 0) {
            unset($guestCart[$productId]);
            session()->put('guest_cart', $guestCart);

            return true;
        }

        $product = Product::find($productId);
        if (! $product || $quantity > $product->stock) {
            return false;
        }

        $guestCart[$productId] = $quantity;
        session()->put('guest_cart', $guestCart);

        return true;
    }

    /**
     * Remove an item from the cart.
     */
    public static function removeItem(int|string $itemId): void
    {
        if (auth()->check()) {
            $cart = auth()->user()->cart;
            $cart?->items()->whereKey($itemId)->delete();

            return;
        }

        $productId = (int) $itemId;
        $guestCart = session()->get('guest_cart', []);
        unset($guestCart[$productId]);
        session()->put('guest_cart', $guestCart);
    }

    /**
     * Clear all items from the cart.
     */
    public static function clear(): void
    {
        if (auth()->check()) {
            auth()->user()->cart?->items()->delete();

            return;
        }

        session()->forget('guest_cart');
    }

    /**
     * Get total item count in cart.
     */
    public static function count(): int
    {
        if (auth()->check()) {
            return auth()->user()->cart?->items()->sum('quantity') ?? 0;
        }

        return array_sum(session()->get('guest_cart', []));
    }

    /**
     * Get normalized cart items via batch query.
     *
     * @return Collection<int, mixed>
     */
    public static function getCartItems(): Collection
    {
        if (auth()->check()) {
            $cart = auth()->user()->cart;
            if (! $cart) {
                return new Collection;
            }

            return $cart->items()
                ->with(['product.category', 'product.brand', 'product.images', 'product.primaryImage'])
                ->latest()
                ->get();
        }

        $guestCart = session()->get('guest_cart', []);
        if (empty($guestCart)) {
            return new Collection;
        }

        $productIds = array_keys($guestCart);
        $products = Product::with(['category', 'brand', 'images', 'primaryImage'])
            ->whereIn('id', $productIds)
            ->get()
            ->keyBy('id');

        $items = new Collection;
        foreach ($guestCart as $pId => $qty) {
            if (! isset($products[$pId])) {
                continue;
            }

            $product = $products[$pId];
            $items->push(new Fluent([
                'id' => $product->id, // For guests, item id is product id
                'product_id' => $product->id,
                'quantity' => $qty,
                'sale_price' => $product->sale_price,
                'product' => $product,
            ]));
        }

        return $items;
    }
}
