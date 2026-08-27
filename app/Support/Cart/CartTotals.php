<?php

namespace App\Support\Cart;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;

final class CartTotals
{
    public readonly float $mrp;

    public readonly float $subtotal;

    public readonly float $savings;

    /** @param  EloquentCollection<int, \App\Models\CartItem>  $items */
    public function __construct(EloquentCollection $items)
    {
        $this->mrp = (float) $items->sum(
            fn ($item) => ($item->product->mrp ?? 0) * $item->quantity,
        );

        $this->subtotal = (float) $items->sum(
            fn ($item) => $item->sale_price * $item->quantity,
        );

        $this->savings = $this->mrp > $this->subtotal
            ? $this->mrp - $this->subtotal
            : 0.0;
    }
}
