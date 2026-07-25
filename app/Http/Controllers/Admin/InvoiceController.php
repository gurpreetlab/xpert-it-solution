<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function downloadForOrder(Order $order)
    {
        abort_unless(
            $order->invoice,
            404,
            "Invoice has not been generated for this order yet.",
        );

        return $this->stream($order->invoice);
    }

    public function download(Invoice $invoice)
    {
        return $this->stream($invoice);
    }

    protected function stream(Invoice $invoice)
    {
        $invoice->load("order.items.product");

        $pdf = Pdf::loadView("pdfs.invoice", ["invoice" => $invoice])->setPaper(
            "a4",
        );

        $filename =
            str_replace(["/", "\\"], "-", $invoice->invoice_number) . ".pdf";

        return $pdf->download($filename);
    }
}
