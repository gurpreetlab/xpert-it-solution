<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Order;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class InvoiceController extends Controller
{
    public function downloadForOrder(Order $order): BinaryFileResponse
    {
        $invoice = $order->invoice;

        if (! $invoice instanceof Invoice) {
            abort(404, 'Invoice has not been generated for this order yet.');
        }

        return $this->stream($invoice);
    }

    public function download(Invoice $invoice): BinaryFileResponse
    {
        return $this->stream($invoice);
    }

    protected function stream(Invoice $invoice): BinaryFileResponse
    {
        return $invoice->renderPdf()->download($invoice->pdfFilename());
    }
}
