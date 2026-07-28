<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[
    Fillable(
        "product_id",
        "product_name",
        "sku",
        "hsn_code",
        "unit_price",
        "mrp",
        "cgst_rate",
        "cgst_amount",
        "sgst_rate",
        "sgst_amount",
        "gst_rate",
        "gst_amount",
        "quantity",
    ),
]
class OrderItem extends Model
{
    protected $casts = [
        "unit_price" => "decimal:2",
        "mrp" => "decimal:2",
        "tax_rate" => "decimal:2",
        "tax_amount" => "decimal:2",
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Taxable value for this line — quantity x GST-exclusive unit price.
     */
    public function getLineTotalAttribute(): float
    {
        return $this->unit_price * $this->quantity;
    }

    /**
     * What the customer actually paid for this line, tax included.
     */
    public function getLineTotalWithTaxAttribute(): float
    {
        return $this->line_total + $this->tax_amount;
    }
}
