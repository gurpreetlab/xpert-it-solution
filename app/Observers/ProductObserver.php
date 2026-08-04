<?php

namespace App\Observers;

use App\Models\Product;
use App\Services\ShopCache;

class ProductObserver
{
    public function saved(Product $product): void
    {
        ShopCache::flushCatalog();
        ShopCache::flushProducts();
    }

    public function deleted(Product $product): void
    {
        ShopCache::flushCatalog();
        ShopCache::flushProducts();
    }
}
