<?php

namespace App\Models;

use App\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(["name"])]
class Category extends Model
{
    use SoftDeletes, HasSlug;

    protected $casts = [
        "product_count" => "integer",
    ];

    /**
     * Scope a query to search for categories by name.
     *
     * @param Builder $query
     * @param string|null $search
     * @return Builder
     */
    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        return $query->when($search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%");
        });
    }

    /**
     * Get the products that belong to this category.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
