<?php

namespace App\Observers;

use App\Models\Brand;

class BrandObserver
{
    public function saved(Brand $brand): void
    {
        //
    }

    public function deleted(Brand $brand): void
    {
        //
    }
}
