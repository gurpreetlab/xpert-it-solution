<?php

namespace App\Models;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
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

    public function order()
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
    public static function generateForOrder(Order $order): self
    {
        if ($order->invoice) {
            return $order->invoice;
        }

        return DB::transaction(function () use ($order) {
            $financialYear = static::currentFinancialYear();

            // Lock the running sequence for this financial year so two
            // concurrent payments can never be issued the same number.
            $lastSequence =
                static::where('financial_year', $financialYear)
                    ->lockForUpdate()
                    ->max('sequence') ?? 0;

            $sequence = $lastSequence + 1;

            $sellerState = shop()->state;
            $isInterState =
                strcasecmp(trim($order->shipping_state), trim($sellerState)) !==
                0;

            $taxableAmount = (float) $order->subtotal;
            $taxAmount = (float) $order->tax_amount;

            // $cgst = $isInterState ? 0 : round($taxAmount / 2, 2);
            // $sgst = $isInterState ? 0 : round($taxAmount / 2, 2);
            // $igst = $isInterState ? round($taxAmount, 2) : 0;

            $cgst = round($taxAmount / 2, 2);
            $sgst = round($taxAmount / 2, 2);
            $igst = round($taxAmount, 2);

            return static::create([
                'order_id' => $order->id,
                'invoice_number' => sprintf(
                    'INV/%s/%05d',
                    $financialYear,
                    $sequence,
                ),
                'financial_year' => $financialYear,
                'sequence' => $sequence,
                'invoice_date' => now()->toDateString(),
                'place_of_supply' => $order->shipping_state,
                'is_inter_state' => $isInterState,
                'taxable_amount' => $taxableAmount,
                'cgst_amount' => $cgst,
                'sgst_amount' => $sgst,
                'igst_amount' => $igst,
                'total_amount' => $order->total,
            ]);
        });
    }

    /**
     * Indian financial year runs April–March. Returns a 4-digit code
     * like '2627' for FY 2026-27.
     */
    public static function currentFinancialYear(): string
    {
        $now = now();
        $startYear = $now->month >= 4 ? $now->year : $now->year - 1;

        return substr($startYear, 2, 2).substr($startYear + 1, 2, 2);
    }

    public function renderPdf()
    {
        $this->loadMissing('order.items.product');

        return Pdf::loadView('pdfs.invoice', ['invoice' => $this])->setPaper('a4');
    }

    public function pdfFilename(): string
    {
        return str_replace(['/', '\\'], '-', $this->invoice_number).'.pdf';
    }
}
