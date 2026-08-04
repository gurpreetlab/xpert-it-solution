<?php

namespace App\Livewire\Shop;

use App\Models\CartItem;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Cart extends Component
{
    /** @var EloquentCollection<int, CartItem>|null */
    protected ?EloquentCollection $cartItems = null;

    protected ?float $subtotal = null;

    protected ?float $savings = null;

    /** @return EloquentCollection<int, CartItem> */
    #[Computed]
    public function cartItems(): EloquentCollection
    {
        $cart = Auth::user()->cart;

        if (! $cart) {
            return new EloquentCollection;
        }

        return $cart
            ->items()
            ->with([
                'product.category',
                'product.brand',
                'product.images',
                'product.primaryImage',
            ])
            ->latest()
            ->get();
    }

    #[Computed]
    public function mrp(): float
    {
        return $this->cartItems->sum(
            fn ($item) => ($item->product->mrp ?? 0) * $item->quantity,
        );
    }

    #[Computed]
    public function subtotal(): float
    {
        return $this->cartItems->sum(
            fn ($item) => $item->sale_price * $item->quantity,
        );
    }

    #[Computed]
    public function savings(): float
    {
        return $this->cartItems->sum(function ($item) {
            $mrp = $item->product->mrp ?? 0;

            return $mrp > $item->sale_price
                ? ($mrp - $item->sale_price) * $item->quantity
                : 0;
        });
    }

    public function incrementQuantity(int $itemId): void
    {
        $item = $this->findOwnedItem($itemId);

        if (! $item) {
            return;
        }

        if ($item->quantity >= $item->product->stock) {
            $this->dispatch(
                'cart-toast',
                message: 'No more stock available',
                variant: 'warning',
            );

            return;
        }

        $item->increment('quantity');

        $this->dispatch('cart-updated');
        $this->cartItems = null;
        $this->subtotal = null;
        $this->savings = null;
    }

    public function decrementQuantity(int $itemId): void
    {
        $item = $this->findOwnedItem($itemId);

        if (! $item) {
            return;
        }

        if ($item->quantity <= 1) {
            $this->removeItem($itemId);

            return;
        }

        $item->decrement('quantity');

        $this->dispatch('cart-updated');
        $this->cartItems = null;
        $this->subtotal = null;
        $this->savings = null;
    }

    public function removeItem(int $itemId): void
    {
        $item = $this->findOwnedItem($itemId);

        if (! $item) {
            return;
        }

        $item->delete();

        $this->dispatch('cart-updated');
        $this->dispatch(
            'cart-toast',
            message: 'Item removed from cart',
            variant: 'success',
        );
        $this->cartItems = null;
        $this->subtotal = null;
        $this->savings = null;
    }

    public function clearCart(): void
    {
        $cart = Auth::user()->cart;

        $cart?->items()->delete();

        $this->dispatch('cart-updated');
        $this->dispatch(
            'cart-toast',
            message: 'Cart cleared',
            variant: 'success',
        );
        $this->cartItems = null;
        $this->subtotal = null;
        $this->savings = null;
    }

    public function checkout(): ?RedirectResponse
    {
        if ($this->cartItems->isEmpty()) {
            return null;
        }

        // Re-validate stock right before handing off to checkout,
        // since availability may have changed since items were added.
        foreach ($this->cartItems as $item) {
            if ($item->product->stock < $item->quantity) {
                $this->dispatch(
                    'cart-toast',
                    message: "\"{$item->product->name}\" no longer has enough stock",
                    variant: 'danger',
                );

                return null;
            }
        }

        return $this->redirect(route('shop.checkout'), navigate: true);
    }

    protected function findOwnedItem(int $itemId): ?CartItem
    {
        $cart = Auth::user()->cart;

        if (! $cart) {
            return null;
        }

        return $cart->items()->whereKey($itemId)->first();
    }

    #[Layout('layouts.blank')]
    public function render(): View
    {
        return view('livewire.shop.cart');
    }
}
