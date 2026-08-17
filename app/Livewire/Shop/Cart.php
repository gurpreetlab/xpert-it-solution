<?php

namespace App\Livewire\Shop;

use App\Actions\Cart\ClearCart as ClearCartAction;
use App\Actions\Cart\DecrementCartItem;
use App\Actions\Cart\IncrementCartItem;
use App\Actions\Cart\RemoveCartItem;
use App\Actions\Cart\ValidateCartForCheckout;
use App\Models\CartItem;
use App\Support\Cart\CartTotals;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Cart extends Component
{
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
    public function totals(): CartTotals
    {
        return new CartTotals($this->cartItems);
    }

    public function incrementQuantity(int $itemId, IncrementCartItem $action): void
    {
        $item = $this->findOwnedItem($itemId);

        if (! $item) {
            return;
        }

        $result = $action($item);

        if ($result->blocked) {
            $this->dispatch('cart-toast', message: $result->message, variant: 'warning');

            return;
        }

        $this->dispatch('cart-updated');
    }

    public function decrementQuantity(int $itemId, DecrementCartItem $decrement, RemoveCartItem $remove): void
    {
        $item = $this->findOwnedItem($itemId);

        if (! $item) {
            return;
        }

        if ($item->quantity <= 1) {
            $this->removeItem($itemId, $remove);

            return;
        }

        $decrement($item);

        $this->dispatch('cart-updated');
    }

    public function removeItem(int $itemId, RemoveCartItem $action): void
    {
        $item = $this->findOwnedItem($itemId);

        if (! $item) {
            return;
        }

        $action($item);

        $this->dispatch('cart-updated');
        $this->dispatch(
            'cart-toast',
            message: 'Item removed from cart',
            variant: 'success',
        );
    }

    public function clearCart(ClearCartAction $action): void
    {
        $action(Auth::user()->cart);

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
