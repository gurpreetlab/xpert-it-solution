<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['product_id', 'group_name', 'key', 'value', 'unit', 'icecat_feature_id', 'sort_order'])]
class ProductSpecification extends Model
{
    /**
     * Get the product that this specification belongs to.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

}
