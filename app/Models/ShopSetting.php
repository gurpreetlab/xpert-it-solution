<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

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

    public static function getCached(): self
    {
        if (! Schema::hasTable((new static)->getTable())) {
            return static::fromConfig();
        }

        $attributes = Cache::rememberForever(self::CACHE_KEY, fn () => self::query()->first()?->getAttributes());

        return $attributes
            ? (new static)->forceFill($attributes)
            : static::fromConfig();
    }

    public static function current(): self
    {
        if (! Schema::hasTable((new static)->getTable())) {
            return static::fromConfig();
        }

        $instance = static::query()->first();

        if ($instance) {
            return $instance;
        }

        $defaults = static::fromConfig()->getAttributes();

        if (! empty($defaults['logo_path'])) {
            $defaults['logo_path'] = preg_replace('#^/?storage/#', '', $defaults['logo_path']);
        }

        if (! empty($defaults['signature_path'])) {
            $defaults['signature_path'] = preg_replace('#^/?storage/#', '', $defaults['signature_path']);
        }

        return static::create($defaults);
    }

    public static function refreshCache(): void
    {
        Cache::forget(self::CACHE_KEY);

        Cache::rememberForever(self::CACHE_KEY, fn () => self::query()->first()?->getAttributes());
    }

    public static function fromConfig(): self
    {
        $company = config('shop.company', []);
        $logoPath = $company['logo_path'] ?? null;
        if ($logoPath) {
            $logoPath = preg_replace('#^/?storage/#', '', $logoPath);
        }

        $signaturePath = $company['signature_path'] ?? null;
        if ($signaturePath) {
            $signaturePath = preg_replace('#^/?storage/#', '', $signaturePath);
        }

        return (new static)->forceFill([
            'name' => $company['name'] ?? '',
            'gstin' => $company['gstin'] ?? '',
            'address_line1' => $company['address_line1'] ?? '',
            'address_line2' => $company['address_line2'] ?? null,
            'state' => $company['state'] ?? '',
            'state_code' => $company['state_code'] ?? '',
            'phone' => $company['phone'] ?? '',
            'email' => $company['email'] ?? '',
            'bank_account_number' => $company['bank_account_number'] ?? null,
            'bank_ifsc' => $company['bank_ifsc'] ?? null,
            'signature_path' => $signaturePath ?? null,
            'logo_path' => $logoPath ?? null,
            'cgst_rate' => config('shop.cgst_rate', 9.0),
            'sgst_rate' => config('shop.sgst_rate', 9.0),
            'gst_rate' => config('shop.gst_rate', 18.0),
        ]);
    }

    public function logoUrl(): ?string
    {
        return $this->logo_path
            ? Storage::disk('public')->url($this->logo_path)
            : null;
    }

    public function signatureUrl(): ?string
    {
        return $this->signature_path
            ? Storage::disk('public')->url($this->signature_path)
            : null;
    }
}
