<?php

namespace App\Actions\Cart;

use App\Models\CartItem;
use App\Support\Cart\CartActionResult;

final class DecrementCartItem
{
    /**
     * Assumes quantity > 1 — the caller is responsible for routing a
     * decrement from quantity 1 to RemoveCartItem instead.
     */
    public function __invoke(CartItem $item): CartActionResult
    {
        $item->decrement('quantity');

        return CartActionResult::ok();
    }
}
