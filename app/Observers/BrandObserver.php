<?php

namespace App\Observers;

use App\Models\Brand;
use App\Services\ShopCache;

class BrandObserver
{
    public function saved(Brand $brand): void
    {
        ShopCache::flushCatalog();
    }

    public function deleted(Brand $brand): void
    {
        ShopCache::flushCatalog();
    }
}
