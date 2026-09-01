@php
    $order = $invoice->order;

    $signaturePath = null;
    if (! empty(shop()->signature_path)) {
        $absolutePath = \Illuminate\Support\Facades\Storage::disk('public')->path(shop()->signature_path);
        if (file_exists($absolutePath)) {
            $signaturePath = $absolutePath;
        }
    }
@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $invoice->invoice_number }}</title>
    <style>
        @page {
            margin: 0; /* top, right, bottom, left */
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #1a1a1a;
            margin: 0;
            padding: 24px;
        }
        table { width: 100%; border-collapse: collapse; }
        .header-table td { vertical-align: top; }
        .company-name { font-size: 18px; font-weight: bold; margin-bottom: 2px; }
        .muted { color: #555; }
        .title-bar {
            text-align: center;
            background: #1e293b;
            color: #fff;
            padding: 6px 0;
            font-size: 14px;
            font-weight: bold;
            letter-spacing: 1px;
            margin: 16px 0;
        }
        .meta-table td { padding: 3px 0; }
        .meta-table .label { color: #555; width: 45%; }
        .party-table td {
            vertical-align: top;
            width: 50%;
            border: 1px solid #ccc;
            padding: 10px;
        }
        .party-title { font-weight: bold; font-size: 11px; margin-bottom: 4px; color: #1e293b; }
        .items-table { margin-top: 16px; }
        .items-table th, .items-table td {
            border: 1px solid #ccc;
            padding: 5px 6px;
            font-size: 10px;
        }
        .items-table th {
            background: #f1f5f9;
            text-align: left;
            font-weight: bold;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .totals-table { width: 45%; margin-left: auto; margin-top: 10px; }
        .totals-table td { padding: 4px 6px; font-size: 11px; }
        .totals-table .grand-total td {
            border-top: 1.5px solid #1e293b;
            font-weight: bold;
            font-size: 12px;
            padding-top: 6px;
        }
        .words-box {
            margin-top: 10px;
            padding: 8px 10px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            font-size: 10.5px;
        }
        .footer-table { margin-top: 30px; }
        .footer-table td { vertical-align: top; font-size: 9.5px; color: #555; width: 50%; }
        .signatory { margin-top: 40px; text-align: right; font-size: 10.5px; }
        .terms li { margin-bottom: 2px; }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td>
                <div class="company-name">{{ shop()->name }}</div>
                <div class="muted">{{ shop()->address_line1 }}</div>
                <div class="muted">{{ shop()->address_line2 }}, {{ shop()->state }}</div>
                <div class="muted">Phone: {{ shop()->phone }} &middot; {{ shop()->email }}</div>
                <div class="muted">GSTIN: {{ shop()->gstin }}</div>
            </td>
            <td class="text-right">
                <div class="muted">Invoice No: <strong>{{ $invoice->invoice_number }}</strong></div>
                <div class="muted">Invoice Date: {{ $invoice->invoice_date->format('d-m-Y') }}</div>
                <div class="muted">Order Ref: {{ $order->order_number }}</div>
                <div class="muted">Place of Supply: {{ $invoice->place_of_supply }}</div>
                <div class="muted">Reverse Charge: N</div>
            </td>
        </tr>
    </table>

    <div class="title-bar">TAX INVOICE</div>

    <table class="party-table">
        <tr>
            <td>
                <div class="party-title">Billed To</div>
                <div>{{ $order->shipping_name }}</div>
                <div class="muted">
                    {{ $order->shipping_address_line1 }}{{ $order->shipping_address_line2 ? ', ' . $order->shipping_address_line2 : '' }},
                    {{ $order->shipping_city }}, {{ $order->shipping_state }} - {{ $order->shipping_pincode }}
                </div>
                <div class="muted">Phone: {{ $order->shipping_phone }}</div>
                <div class="muted">GSTIN: Unregistered</div>
            </td>
            <td>
                <div class="party-title">Shipped To</div>
                <div>{{ $order->shipping_name }}</div>
                <div class="muted">
                    {{ $order->shipping_address_line1 }}{{ $order->shipping_address_line2 ? ', ' . $order->shipping_address_line2 : '' }},
                    {{ $order->shipping_city }}, {{ $order->shipping_state }} - {{ $order->shipping_pincode }}
                </div>
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Product</th>
                <th nowrap="nowrap">HSN</th>
                <th class="text-right" nowrap="nowrap">Qty</th>
                <th class="text-right" nowrap="nowrap">Rate</th>
                <th class="text-right" nowrap="nowrap">Taxable Amt</th>
                <th class="text-right" nowrap="nowrap">CGST (9%)</th>
                <th class="text-right" nowrap="nowrap">SGST (9%)</th>
                <th class="text-right" nowrap="nowrap">IGST (18%)</th>
                <th class="text-right" nowrap="nowrap">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->hsn_code ?? '-' }}</td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right">₹{{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-right">₹{{ number_format($item->line_total, 2) }}</td>
                    <td class="text-right">₹{{ number_format($item->cgst_amount, 2) }}</td>
                    <td class="text-right">₹{{ number_format($item->sgst_amount, 2) }}</td>
                    <td class="text-right">₹{{ number_format($item->gst_amount, 2) }}</td>
                    <td class="text-right">₹{{ number_format($item->line_total_with_tax, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals-table">
        <tr>
            <td class="muted">Taxable Amount</td>
            <td class="text-right">₹{{ number_format($invoice->taxable_amount, 2) }}</td>
        </tr>

        <tr>
            <td class="muted">CGST</td>
            <td class="text-right">₹{{ number_format($invoice->cgst_amount, 2) }}</td>
        </tr>
        <tr>
            <td class="muted">SGST</td>
            <td class="text-right">₹{{ number_format($invoice->sgst_amount, 2) }}</td>
        </tr>

        @if($order->shipping_fee > 0)
            <tr>
                <td class="muted">Shipping</td>
                <td class="text-right">₹{{ number_format($order->shipping_fee, 2) }}</td>
            </tr>
        @endif
        <tr class="grand-total">
            <td>Grand Total</td>
            <td class="text-right">₹{{ number_format($invoice->total_amount, 2) }}</td>
        </tr>
    </table>

    <div class="words-box">
        <strong>Amount in Words:</strong> {{ \App\Support\IndianNumberToWords::convert($invoice->total_amount) }}
    </div>

    <table class="footer-table">
        <tr>
            <td>
                <strong>Bank Details</strong><br>
                Account Number: {{ shop()->bank_account_number }}<br>
                IFSC Code: {{ shop()->bank_ifsc }}
            </td>
            <td>
                <strong>Terms &amp; Conditions</strong>
                <ul class="terms" style="padding-left: 14px; margin: 4px 0;">
                    <li>Goods once sold will not be taken back or exchanged.</li>
                    <li>Bills unpaid past due date will attract 24% interest.</li>
                    <li>All disputes subject to {{ shop()->state }} jurisdiction only.</li>
                </ul>
            </td>
        </tr>
    </table>

    <div class="signatory">
        For {{ shop()->name }}<br>
        @if($signaturePath)
            <img src="{{ $signaturePath }}" alt="Authorised Signatory" style="height: 30px; margin: 6px 0;"><br>
        @else
            <div style="height: 56px;"></div>
        @endif
        Authorised Signatory
        @unless($signaturePath)
            <div style="margin-top: 4px; color: #555; font-size: 9px;">
                This is a computer-generated invoice and does not require a physical signature.
            </div>
        @endunless
    </div>

</body>
</html>