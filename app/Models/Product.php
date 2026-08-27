<?php

namespace App\Models;

use App\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Laravel\Scout\Searchable;

/**
 * @property int $id
 * @property string|null $name
 * @property string|null $slug
 * @property string|null $sku
 * @property string|null $mpn
 * @property string|null $gtin
 * @property string|null $short_description
 * @property string|null $description
 * @property int|null $category_id
 * @property int|null $brand_id
 * @property float|int|null $sale_price
 * @property float|int|null $mrp
 * @property int|null $stock
 * @property bool|null $is_featured
 * @property bool|null $is_active
 * @property Carbon|null $created_at
 * @property-read Category|null $category
 * @property-read Brand|null $brand
 * @property-read ProductImage|null $primaryImage
 * @property-read Collection<int, ProductImage> $images
 */
#[Fillable(['category_id', 'brand_id', 'type', 'name', 'slug', 'sku', 'mpn', 'gtin', 'hsn_code', 'icecat_id', 'icecat_synced_at', 'mrp', 'purchase_price', 'sale_price', 'stock', 'short_description', 'description', 'weight', 'warranty', 'is_featured', 'is_active'])]
class Product extends Model
{
    use HasSlug, Searchable, SoftDeletes;

    /**
     * @return array<string, mixed>
     */
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'sku' => $this->sku,
            'category_id' => $this->category_id,
            'brand_id' => $this->brand_id,
            'category_name' => $this->category?->name,
            'brand_name' => $this->brand?->name,
            'sale_price' => (float) $this->sale_price,
            'stock' => (int) ($this->stock ?? 0),
            'is_featured' => (bool) $this->is_featured,
            'is_active' => (bool) $this->is_active,
            'created_at' => $this->created_at?->timestamp,
        ];
    }

    /**
     * Only ever index active products — keeps the index smaller and means
     * a de-activated product disappears from search immediately, without
     * relying on every query remembering to filter is_active itself.
     */
    public function shouldBeSearchable(): bool
    {
        return (bool) $this->is_active;
    }

    /**
     * Eager-load relations during `scout:import` / bulk re-indexing.
     * Without this, toSearchableArray()'s $this->category / $this->brand
     * access triggers one query PER PRODUCT during a full re-index —
     * fine for saving one product, very slow for importing thousands.
     *
     * @param  Builder<Product>  $query
     * @return Builder<Product>
     */
    public function makeAllSearchableUsing(Builder $query): Builder
    {
        return $query->with(['category:id,name', 'brand:id,name']);
    }

    protected $casts = [
        'category_id' => 'integer',
        'brand_id' => 'integer',
        'mrp' => 'decimal:2',
        'purchase_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'stock' => 'integer',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Scope a query to search for brands by name.
     *
     * @param  Builder<Product>  $query
     * @return Builder<Product>
     */
    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        return $query->when($search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%");
        });
    }

    /**
     * Get the images for this product.
     *
     * @return HasMany<ProductImage, $this>
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    /**
     * Get the primary image for this product.
     *
     * @return HasOne<ProductImage, $this>
     */
    public function primaryImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    /**
     * Get the specifications for this product.
     *
     * @return HasMany<ProductSpecification, $this>
     */
    public function specifications(): HasMany
    {
        return $this->hasMany(ProductSpecification::class)->orderBy('sort_order');
    }

    /**
     * Get the category that this product belongs to.
     *
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the brand that this product belongs to.
     *
     * @return BelongsTo<Brand, $this>
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Get the reviews for the product.
     *
     * @return HasMany<Review, $this>
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->latest();
    }

    /**
     * Get the users who wishlisted the product.
     *
     * @return BelongsToMany<User, $this>
     */
    public function wishlistedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'wishlist_items')
            ->withTimestamps();
    }
}
