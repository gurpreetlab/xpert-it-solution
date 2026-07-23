<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[
    Fillable(
        "order_id",
        "product_id",
        "product_name",
        "sku",
        "hsn_code",
        "unit_price",
        "mrp",
        "quantity",
    ),
]
class OrderItem extends Model
{
    protected $casts = [
        "unit_price" => "decimal:2",
        "mrp" => "decimal:2",
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getLineTotalAttribute(): float
    {
        return $this->unit_price * $this->quantity;
    }
}
