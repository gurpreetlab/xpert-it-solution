<?php

namespace App\Observers;

use App\Models\ShopSetting;

class ShopSettingObserver
{
    public function created(ShopSetting $setting): void
    {
        ShopSetting::refreshCache();
    }

    public function updated(ShopSetting $setting): void
    {
        ShopSetting::refreshCache();
    }

    public function deleted(ShopSetting $setting): void
    {
        ShopSetting::refreshCache();
    }
}
