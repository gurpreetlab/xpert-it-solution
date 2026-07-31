<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Order;

class InvoiceController extends Controller
{
    public function downloadForOrder(Order $order)
    {
        abort_unless(
            $order->invoice,
            404,
            'Invoice has not been generated for this order yet.',
        );

        return $this->stream($order->invoice);
    }

    public function download(Invoice $invoice)
    {
        return $this->stream($invoice);
    }

    protected function stream(Invoice $invoice)
    {
        return $invoice->renderPdf()->download($invoice->pdfFilename());
    }
}
