<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

#[Fillable(
    'name', 'gstin', 'address_line1', 'address_line2', 'state', 'state_code',
    'phone', 'email', 'bank_account_number', 'bank_ifsc',
    'cgst_rate', 'sgst_rate', 'gst_rate',
    'logo_path', 'signature_path',
)]
class ShopSetting extends Model
{
    const CACHE_KEY = 'shop';

    protected $casts = [
        'cgst_rate' => 'float',
        'sgst_rate' => 'float',
        'gst_rate' => 'float',
    ];

    public static function getCached(): ?self
    {
        $attributes = Cache::rememberForever(self::CACHE_KEY, fn () => self::query()->first()?->getAttributes());

        return $attributes
            ? (new static)->forceFill($attributes)
            : null;
    }

    public static function refreshCache(): void
    {
        Cache::forget(self::CACHE_KEY);

        Cache::rememberForever(self::CACHE_KEY, fn () => self::query()->first());
    }
}
