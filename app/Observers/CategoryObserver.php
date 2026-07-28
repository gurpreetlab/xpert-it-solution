<?php

namespace App\Observers;

use App\Models\Category;
use App\Services\ShopCache;

class CategoryObserver
{
    public function saved(Category $category): void
    {
        ShopCache::flushCatalog();
    }

    public function deleted(Category $category): void
    {
        ShopCache::flushCatalog();
    }
}
