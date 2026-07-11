<?php

namespace App\Models;

use App\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(["name", "sku", "hsn_code", "mrp", "purchase_price", "sale_price", "stock", "short_description", "description", "is_featured", "is_active"])]
class Product extends Model
{
    use SoftDeletes, HasSlug;

    protected $casts = [
        'mrp' => 'decimal:2',
        'purchase_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'stock' => 'integer',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the images for this product.
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * Get the specifications for this product.
     */
    public function specifications(): HasMany
    {
        return $this->hasMany(ProductSpecification::class);
    }

    /**
     * Get the category that this product belongs to.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the brand that this product belongs to.
     */

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

}
