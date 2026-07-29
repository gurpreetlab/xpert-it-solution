<?php

namespace App\Livewire\Shop;

use App\Models\CartItem;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

class Cart extends Component
{
    #[Computed]
    public function cartItems(): Collection
    {
        $cart = Auth::user()->cart;

        if (!$cart) {
            return collect();
        }

        return $cart
            ->items()
            ->with([
                "product.category",
                "product.brand",
                "product.images",
                "product.primaryImage",
            ])
            ->latest()
            ->get();
    }

    #[Computed]
    public function mrp(): float
    {
        return $this->cartItems->sum(
            fn($item) => $item->product->mrp * $item->quantity ?? 0,
        );
    }

    #[Computed]
    public function subtotal(): float
    {
        return $this->cartItems->sum(
            fn($item) => $item->sale_price * $item->quantity,
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

        if (!$item) {
            return;
        }

        if ($item->quantity >= $item->product->stock) {
            $this->dispatch(
                "cart-toast",
                message: "No more stock available",
                variant: "warning",
            );
            return;
        }

        $item->increment("quantity");

        $this->dispatch("cart-updated");
        unset($this->cartItems, $this->subtotal, $this->savings);
    }

    public function decrementQuantity(int $itemId): void
    {
        $item = $this->findOwnedItem($itemId);

        if (!$item) {
            return;
        }

        if ($item->quantity <= 1) {
            $this->removeItem($itemId);
            return;
        }

        $item->decrement("quantity");

        $this->dispatch("cart-updated");
        unset($this->cartItems, $this->subtotal, $this->savings);
    }

    public function removeItem(int $itemId)
    {
        $item = $this->findOwnedItem($itemId);

        if (!$item) {
            return;
        }

        $item->delete();

        $this->dispatch("cart-updated");
        $this->dispatch(
            "cart-toast",
            message: "Item removed from cart",
            variant: "success",
        );
        unset($this->cartItems, $this->subtotal, $this->savings);
    }

    public function clearCart()
    {
        $cart = Auth::user()->cart;

        $cart?->items()->delete();

        $this->dispatch("cart-updated");
        $this->dispatch(
            "cart-toast",
            message: "Cart cleared",
            variant: "success",
        );
        unset($this->cartItems, $this->subtotal, $this->savings);
    }

    public function checkout()
    {
        if ($this->cartItems->isEmpty()) {
            return;
        }

        // Re-validate stock right before handing off to checkout,
        // since availability may have changed since items were added.
        foreach ($this->cartItems as $item) {
            if ($item->product->stock < $item->quantity) {
                $this->dispatch(
                    "cart-toast",
                    message: "\"{$item->product->name}\" no longer has enough stock",
                    variant: "danger",
                );
                return;
            }
        }

        return $this->redirect(route("shop.checkout"), navigate: true);
    }

    protected function findOwnedItem(int $itemId): ?CartItem
    {
        $cart = Auth::user()->cart;

        if (!$cart) {
            return null;
        }

        return $cart->items()->whereKey($itemId)->first();
    }

    #[Layout("layouts.blank")]
    public function render()
    {
        return view("livewire.shop.cart");
    }
}
