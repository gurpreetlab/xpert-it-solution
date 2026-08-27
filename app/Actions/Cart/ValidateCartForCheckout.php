<?php

namespace App\Actions\Cart;

use App\Support\Cart\CartActionResult;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

final class ValidateCartForCheckout
{
    /**
     * Re-validate stock right before handing off to checkout, since
     * availability may have changed since items were added to the cart.
     *
     * @param  EloquentCollection<int, \App\Models\CartItem>  $items
     */
    public function __invoke(EloquentCollection $items): CartActionResult
    {
        foreach ($items as $item) {
            if ($item->product->stock < $item->quantity) {
                return CartActionResult::blocked(
                    "\"{$item->product->name}\" no longer has enough stock",
                );
            }
        }

        return CartActionResult::ok();
    }
}
