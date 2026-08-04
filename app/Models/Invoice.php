<?php

namespace App\Models;

use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPdfOutput;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

#[
    Fillable([
        'order_id',
        'invoice_number',
        'financial_year',
        'sequence',
        'invoice_date',
        'place_of_supply',
        'is_inter_state',
        'taxable_amount',
        'cgst_amount',
        'sgst_amount',
        'igst_amount',
        'total_amount',
    ]),
]
class Invoice extends Model
{
    protected $casts = [
        'invoice_date' => 'date',
        'is_inter_state' => 'boolean',
    ];

    /**
     * @return BelongsTo<Order, $this>
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Creates the single, permanent invoice for a paid order.
     *
     * Called once, right when an order's payment is verified — never
     * lazily on first PDF download — so invoice numbers are issued
     * strictly in the order sales actually happened, with no gaps.
     */
    public static function generateForOrder(Order $order): Invoice
    {
        if ($order->invoice) {
            return $order->invoice;
        }

        $invoice = new Invoice([
            'order_id' => $order->id,
            'invoice_number' => sprintf(
                'INV/%s/%05d',
                $financialYear = static::currentFinancialYear(),
                $sequence = (static::where('financial_year', $financialYear)
                    ->lockForUpdate()
                    ->max('sequence') ?? 0) + 1,
            ),
            'financial_year' => $financialYear,
            'sequence' => $sequence,
            'invoice_date' => now()->toDateString(),
            'place_of_supply' => $order->shipping_state,
            'is_inter_state' => strcasecmp(trim($order->shipping_state), trim(shop()->state)) !== 0,
            'taxable_amount' => (float) $order->subtotal,
            'cgst_amount' => $cgst = round(((float) $order->tax_amount) / 2, 2),
            'sgst_amount' => $sgst = round(((float) $order->tax_amount) / 2, 2),
            'igst_amount' => $igst = round((float) $order->tax_amount, 2),
            'total_amount' => $order->total,
        ]);

        DB::transaction(function () use ($invoice): void {
            $invoice->save();
        });

        return $invoice;
    }

    /**
     * Indian financial year runs April–March. Returns a 4-digit code
     * like '2627' for FY 2026-27.
     */
    public static function currentFinancialYear(): string
    {
        $now = now();
        $startYear = $now->month >= 4 ? $now->year : $now->year - 1;

        return substr((string) $startYear, 2, 2).substr((string) ($startYear + 1), 2, 2);
    }

    public function renderPdf(): DomPdfOutput
    {
        $this->loadMissing('order.items.product');

        return Pdf::loadView('pdfs.invoice', ['invoice' => $this])->setPaper('a4');
    }

    public function pdfFilename(): string
    {
        return str_replace(['/', '\\'], '-', $this->invoice_number).'.pdf';
    }
}
