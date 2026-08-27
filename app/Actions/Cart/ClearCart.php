<?php

namespace App\Actions\Cart;

use App\Models\Cart;
use App\Support\Cart\CartActionResult;

final class ClearCart
{
    public function __invoke(?Cart $cart): CartActionResult
    {
        $cart?->items()->delete();

        return CartActionResult::ok();
    }
}
