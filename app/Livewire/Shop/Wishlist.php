<?php

namespace App\Livewire\Shop;

use App\Models\Product;
use Flux\Flux;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Wishlist extends Component
{
    /**
     * Get the user's wishlisted products.
     *
     * @return Collection<int, Product>
     */
    #[Computed]
    public function wishlistItems(): Collection
    {
        if (! auth()->check()) {
            return new Collection();
        }

        return auth()->user()->wishlistProducts()
            ->with(['brand', 'primaryImage', 'images', 'category'])
            ->get();
    }

    /**
     * Remove an item from the wishlist.
     */
    public function removeFromWishlist(int $productId): void
    {
        if (! auth()->check()) {
            return;
        }

        auth()->user()->wishlistProducts()->detach($productId);

        Flux::toast(text: 'Product removed from your wishlist.', variant: 'success');
    }

    /**
     * Add a wishlisted item to the cart.
     */
    public function addToCart(int $productId): void
    {
        if (! auth()->check()) {
            Flux::toast(text: 'Please login to add items to your cart.', variant: 'danger');
            return;
        }

        $product = Product::findOrFail($productId);

        // Check stock
        if ($product->stock <= 0) {
            Flux::toast(text: 'Product is out of stock.', variant: 'danger');
            return;
        }

        $cart = auth()->user()->cart()->firstOrCreate();

        $item = $cart->items()->where('product_id', $productId)->first();

        if ($item) {
            if ($item->quantity >= $product->stock) {
                Flux::toast(text: 'Not enough stock available.', variant: 'warning');
                return;
            }
            $item->increment('quantity');
        } else {
            $cart->items()->create([
                'product_id' => $productId,
                'quantity' => 1,
                'sale_price' => $product->sale_price,
            ]);
        }

        // Keep it in wishlist or remove? Normally we keep it, but let's just toast and dispatch update
        $this->dispatch('cart-updated');
        Flux::toast(text: "Added {$product->name} to cart.", variant: 'success');
    }

    #[Layout('layouts.blank')]
    public function render(): View
    {
        return view('livewire.shop.wishlist');
    }
}
