<?php

namespace App\Models;

use App\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

/**
 * @property int|null $products_count
 */
#[
    Fillable([
        "parent_id",
        "name",
        "slug",
        "description",
        "image",
        "icecat_category_id",
        "sort_order",
        "is_active",
    ]),
]
class Category extends Model
{
    use HasSlug, SoftDeletes;

    protected $casts = [
        "product_count" => "integer",
        "products_count" => "integer",
    ];

    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget("shop:categories_html_v1");
        });

        static::deleted(function () {
            Cache::forget("shop:categories_html_v1");
        });
    }

    /**
     * Scope a query to search for categories by name.
     *
     * @param  Builder<Category>  $query
     * @return Builder<Category>
     */
    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        return $query->when($search, function ($query, $search) {
            $query->where("name", "like", "%{$search}%");
        });
    }

    /**
     * Get the products that belong to this category.
     *
     * @return HasMany<Product, $this>
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
