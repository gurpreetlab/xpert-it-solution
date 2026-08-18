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
        return \App\Support\WishlistManager::getProducts();
    }

    /**
     * Remove an item from the wishlist.
     */
    public function removeFromWishlist(int $productId): void
    {
        \App\Support\WishlistManager::remove($productId);

        $this->dispatch('wishlist-updated');

        Flux::toast(text: 'Product removed from your wishlist.', variant: 'success');
    }

    /**
     * Add a wishlisted item to the cart.
     */
    public function addToCart(int $productId): void
    {
        $product = Product::findOrFail($productId);

        if ($product->stock <= 0) {
            Flux::toast(text: 'Product is out of stock.', variant: 'danger');
            return;
        }

        $success = \App\Support\CartManager::add($productId, 1);

        if (! $success) {
            Flux::toast(text: 'Not enough stock available.', variant: 'warning');
            return;
        }

        $this->dispatch('cart-updated');
        Flux::toast(text: "Added {$product->name} to cart.", variant: 'success');
    }

    #[Layout('layouts.blank')]
    public function render(): View
    {
        return view('livewire.shop.wishlist');
    }
}
