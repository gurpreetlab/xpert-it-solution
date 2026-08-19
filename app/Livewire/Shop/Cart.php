<?php

namespace App\Livewire\Shop;

use App\Actions\Cart\ClearCart as ClearCartAction;
use App\Actions\Cart\ValidateCartForCheckout;
use App\Models\CartItem;
use App\Support\Cart\CartTotals;
use App\Support\CartManager; // TODO: confirm this is CartTotals' actual namespace
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Cart extends Component
{
    #[Computed]
    public function cartItems()
    {
        return CartManager::getCartItems();
    }

    #[Computed]
    public function totals(): CartTotals
    {
        return new CartTotals($this->cartItems);
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

    public function incrementQuantity(int|string $itemId): void
    {
        $item = $this->cartItems->firstWhere('id', $itemId);
        if (! $item) {
            return;
        }

        $success = CartManager::updateQuantity($itemId, $item->quantity + 1);

        if (! $success) {
            $this->dispatch(
                'cart-toast',
                message: 'No more stock available',
                variant: 'warning',
            );

            return;
        }

        $this->dispatch('cart-updated');
    }

    public function decrementQuantity(int|string $itemId): void
    {
        $item = $this->cartItems->firstWhere('id', $itemId);

        if (! $item) {
            return;
        }

        if ($item->quantity <= 1) {
            $this->removeItem($itemId);

            return;
        }

        CartManager::updateQuantity($itemId, $item->quantity - 1);

        $this->dispatch('cart-updated');
    }

    public function removeItem(int|string $itemId): void
    {
        CartManager::removeItem($itemId);

        $this->dispatch('cart-updated');
        $this->dispatch(
            'cart-toast',
            message: 'Item removed from cart',
            variant: 'success',
        );
    }

    public function clearCart(ClearCartAction $action): void
    {
        // TODO: $action is injected but never invoked — decide whether this
        // should replace the CartManager::clear() call below (check
        // ClearCartAction's __invoke signature first).
        CartManager::clear();

        $this->dispatch('cart-updated');
        $this->dispatch(
            'cart-toast',
            message: 'Cart cleared',
            variant: 'success',
        );
    }

    public function checkout(ValidateCartForCheckout $validate): ?RedirectResponse
    {
        if ($this->cartItems->isEmpty()) {
            return null;
        }

        $result = $validate($this->cartItems);

        if ($result->blocked) {
            $this->dispatch('cart-toast', message: $result->message, variant: 'danger');

            return null;
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
