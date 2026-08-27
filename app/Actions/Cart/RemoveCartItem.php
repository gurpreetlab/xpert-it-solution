<?php

namespace App\Actions\Cart;

use App\Models\CartItem;
use App\Support\Cart\CartActionResult;

final class RemoveCartItem
{
    public function __invoke(CartItem $item): CartActionResult
    {
        $item->delete();

        return CartActionResult::ok();
    }
}
