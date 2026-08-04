<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'product_id',
    'path',
    'source_url',
    'source',
    'type',
    'alt_text',
    'is_primary',
    'sort_order',
])]
class ProductImage extends Model
{
    /**
     * Get the product that this image belongs to.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
