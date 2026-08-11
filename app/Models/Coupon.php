<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['code', 'discount_percent', 'is_active'])]
class Coupon extends Model
{
    protected $casts = [
        'is_active' => 'boolean',
        'discount_percent' => 'integer',
    ];

    /**
     * Retrieve an active coupon by its code.
     */
    public static function findActive(string $code): ?self
    {
        return self::where('code', strtoupper($code))
            ->where('is_active', true)
            ->first();
    }
}
