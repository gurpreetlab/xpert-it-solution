<?php

namespace App\Models;

use App\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int|null $products_count
 */
#[Fillable([
    'name',
    'slug',
    'logo',
    'description',
    'website',
    'icecat_brand_id',
])]
class Brand extends Model
{
    use HasSlug, SoftDeletes;

    protected $casts = [
        'products_count' => 'integer',
    ];

    /**
     * Scope a query to search for brands by name.
     *
     * @param  Builder<Brand>  $query
     * @return Builder<Brand>
     */
    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        return $query->when($search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%");
        });
    }

    /**
     * Get the products that belong to this brand.
     *
     * @return HasMany<Product, $this>
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
