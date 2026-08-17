<?php

namespace App\Observers;

use App\Models\Product;

class ProductObserver
{
    public function saved(Product $product): void
    {
        //
    }

    public function deleted(Product $product): void
    {
        //
    }
}
