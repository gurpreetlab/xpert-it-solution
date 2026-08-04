<?php

use App\Models\ShopSetting;

if (! function_exists('shop')) {
    function shop(): ?ShopSetting
    {
        return ShopSetting::getCached();
    }
}
