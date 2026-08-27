<?php

namespace App\Actions\Cart;

use App\Models\CartItem;
use App\Support\Cart\CartActionResult;

final class IncrementCartItem
{
    public function __invoke(CartItem $item): CartActionResult
    {
        if ($item->quantity >= $item->product->stock) {
            return CartActionResult::blocked('No more stock available');
        }

        $item->increment('quantity');

        return CartActionResult::ok();
    }
}
