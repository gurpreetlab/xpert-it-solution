@php

$subtotal = (float) $order->subtotal;
$discount = (float) $order->discount;
$shippingFee = (float) $order->shipping_fee;
$tax = (float) $order->tax_amount;
$total = (float) $order->total;

$statusLabels = [
'pending' => 'Pending',
'processing' => 'Processing',
'shipped' => 'Shipped',
'delivered' => 'Delivered',
'cancelled' => 'Cancelled',
];
$statusLabel = $statusLabels[$order->status] ?? ucfirst($order->status);
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Order Confirmed — {{ $order->order_number }}</title>
    <!--[if mso]>
<noscript>
<xml>
<o:OfficeDocumentSettings>
<o:PixelsPerInch>96</o:PixelsPerInch>
</o:OfficeDocumentSettings>
</xml>
</noscript>
<![endif]-->
    <style>
        body,
        table,
        td {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: #f4f4f5;
            -webkit-text-size-adjust: 100%;
        }

        table {
            border-collapse: collapse;
        }

        img {
            border: 0;
            display: block;
        }

        a {
            text-decoration: none;
        }

        @media only screen and (max-width: 600px) {
            .email-container {
                width: 100% !important;
            }

            .stack-col {
                display: block !important;
                width: 100% !important;
                padding-right: 0 !important;
                padding-bottom: 16px !important;
            }

            .items-table th.hide-sm,
            .items-table td.hide-sm {
                display: none !important;
            }

            .px-mobile {
                padding-left: 20px !important;
                padding-right: 20px !important;
            }
        }
    </style>
</head>

<body>
    <!-- Preheader (hidden preview text) -->
    <div style="display:none; max-height:0; overflow:hidden; opacity:0; mso-hide:all;">
        Your order {{ $order->order_number }} is confirmed — total ₹{{ number_format($total, 2) }}. Thank you for shopping with {{ shop()->name }}.
    </div>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f4f5;">
        <tr>
            <td align="center" style="padding: 32px 16px;">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" class="email-container" style="width:600px; max-width:600px;">

                    <!-- Brand Header -->
                    <tr>
                        <td style="padding: 4px 4px 24px 4px;" align="center">
                            <table role="presentation" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="vertical-align:middle; padding-right:10px;">
                                        <table role="presentation" cellpadding="0" cellspacing="0" width="36" height="36" style="background-color:#18181b; border-radius:8px;">
                                            <tr>
                                                <td align="center" valign="middle" style="width:36px; height:36px;">
                                                    <img src="{{ asset('logo-xpert-it-solution.png') }}" width="20" height="20" alt="{{ shop()->name }}" style="display:block;">
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td style="vertical-align:middle;">
                                        <span style="font-size:18px; font-weight:700; color:#18181b;">Xpert <span style="color:#2563eb;">IT Solution</span></span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Main Card -->
                    <tr>
                        <td style="background-color:#ffffff; border:1px solid #e4e4e7; border-radius:20px; overflow:hidden;">

                            <!-- Status Title Bar -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="background-color:#18181b; padding:14px 20px;">
                                        <span style="font-size:12px; font-weight:700; letter-spacing:1.5px; color:#ffffff; text-transform:uppercase;">✓ Order Confirmed</span>
                                    </td>
                                </tr>
                            </table>

                            <!-- Greeting -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td class="px-mobile" style="padding: 28px 32px 8px 32px;">
                                        <p style="margin:0 0 4px 0; font-size:20px; font-weight:700; color:#18181b;">Thanks for your order, {{ $order->shipping_name }}!</p>
                                        <p style="margin:0; font-size:14px; line-height:22px; color:#71717a;">
                                            We've received your order and it's being prepared. We'll email you again once it ships. A tax invoice is attached to this email as a PDF.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Order Meta Chips -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td class="px-mobile" style="padding: 20px 32px 4px 32px;">
                                        <table role="presentation" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="background-color:#eff6ff; border-radius:999px; padding:6px 14px; font-size:11px; font-weight:600; color:#1d4ed8;">
                                                    Order #{{ $order->order_number }}
                                                </td>
                                                <td style="width:8px;">&nbsp;</td>
                                                <td style="background-color:#f4f4f5; border-radius:999px; padding:6px 14px; font-size:11px; font-weight:600; color:#3f3f46;">
                                                    {{ $order->created_at->format('d M Y') }}
                                                </td>
                                                <td style="width:8px;">&nbsp;</td>
                                                <td style="background-color:#ecfdf5; border-radius:999px; padding:6px 14px; font-size:11px; font-weight:600; color:#047857;">
                                                    {{ $statusLabel }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Divider -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td class="px-mobile" style="padding: 20px 32px 0 32px;">
                                        <div style="border-top:1px solid #e4e4e7;"></div>
                                    </td>
                                </tr>
                            </table>

                            <!-- Items -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td class="px-mobile" style="padding: 20px 32px 0 32px;">
                                        <p style="margin:0 0 12px 0; font-size:11px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:#a1a1aa;">Order Summary</p>

                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" class="items-table" style="border:1px solid #e4e4e7; border-radius:12px; overflow:hidden;">
                                            <tr style="background-color:#f4f4f5;">
                                                <td style="padding:10px 12px; font-size:11px; font-weight:700; color:#52525b; text-transform:uppercase; letter-spacing:0.5px;">Product</td>
                                                <td class="hide-sm" align="center" style="padding:10px 12px; font-size:11px; font-weight:700; color:#52525b; text-transform:uppercase; letter-spacing:0.5px;">Qty</td>
                                                <td align="right" style="padding:10px 12px; font-size:11px; font-weight:700; color:#52525b; text-transform:uppercase; letter-spacing:0.5px;">Amount</td>
                                            </tr>
                                            @foreach($order->items as $item)
                                            @php
                                            $lineTotal = $item->line_total ?? ($item->unit_price * $item->quantity);
                                            @endphp
                                            <tr>
                                                <td style="padding:12px; font-size:13px; color:#18181b; border-top:1px solid #e4e4e7;">
                                                    {{ $item->product_name }}
                                                    <div class="hide-sm-inline" style="font-size:11px; color:#a1a1aa; margin-top:2px;">Qty {{ $item->quantity }} &middot; ₹{{ number_format($item->unit_price, 2) }} each</div>
                                                </td>
                                                <td class="hide-sm" align="center" style="padding:12px; font-size:13px; color:#3f3f46; border-top:1px solid #e4e4e7;">{{ $item->quantity }}</td>
                                                <td align="right" style="padding:12px; font-size:13px; font-weight:600; color:#18181b; border-top:1px solid #e4e4e7; white-space:nowrap;">₹{{ number_format($lineTotal, 2) }}</td>
                                            </tr>
                                            @endforeach
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Totals -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td class="px-mobile" style="padding: 16px 32px 0 32px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td width="55%">&nbsp;</td>
                                                <td width="45%">
                                                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                                        <tr>
                                                            <td style="padding:4px 0; font-size:12px; color:#71717a;">Subtotal</td>
                                                            <td align="right" style="padding:4px 0; font-size:12px; color:#3f3f46;">₹{{ number_format($subtotal, 2) }}</td>
                                                        </tr>
                                                        @if($discount > 0)
                                                        <tr>
                                                            <td style="padding:4px 0; font-size:12px; color:#71717a;">Discount</td>
                                                            <td align="right" style="padding:4px 0; font-size:12px; color:#e11d48;">-₹{{ number_format($discount, 2) }}</td>
                                                        </tr>
                                                        @endif
                                                        <tr>
                                                            <td style="padding:4px 0; font-size:12px; color:#71717a;">Shipping</td>
                                                            <td align="right" style="padding:4px 0; font-size:12px; color:#3f3f46;">
                                                                {{ $shippingFee > 0 ? '₹' . number_format($shippingFee, 2) : 'Free' }}
                                                            </td>
                                                        </tr>
                                                        @if($tax > 0)
                                                        <tr>
                                                            <td style="padding:4px 0; font-size:12px; color:#71717a;">Tax (GST)</td>
                                                            <td align="right" style="padding:4px 0; font-size:12px; color:#3f3f46;">₹{{ number_format($tax, 2) }}</td>
                                                        </tr>
                                                        @endif
                                                        <tr>
                                                            <td style="padding:10px 0 0 0; font-size:14px; font-weight:700; color:#18181b; border-top:1.5px solid #18181b;">Total</td>
                                                            <td align="right" style="padding:10px 0 0 0; font-size:14px; font-weight:700; color:#18181b; border-top:1.5px solid #18181b;">₹{{ number_format($total, 2) }}</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA Button -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td class="px-mobile" align="center" style="padding: 28px 32px 4px 32px;">
                                        <table role="presentation" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="background-color:#2563eb; border-radius:10px;">
                                                    <a href="{{ route('shop.orders') }}" target="_blank" style="display:inline-block; padding:12px 28px; font-size:14px; font-weight:600; color:#ffffff;">
                                                        Track Your Order
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Divider -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td class="px-mobile" style="padding: 28px 32px 0 32px;">
                                        <div style="border-top:1px solid #e4e4e7;"></div>
                                    </td>
                                </tr>
                            </table>

                            <!-- Shipping Address -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td class="px-mobile" style="padding: 20px 32px 28px 32px;">
                                        <p style="margin:0 0 10px 0; font-size:11px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:#a1a1aa;">Shipping To</p>
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#fafafa; border:1px dashed #e4e4e7; border-radius:12px;">
                                            <tr>
                                                <td style="padding:16px;">
                                                    <p style="margin:0 0 2px 0; font-size:13px; font-weight:700; color:#18181b;">{{ $order->shipping_name }}</p>
                                                    <p style="margin:0 0 2px 0; font-size:12px; color:#52525b; line-height:19px;">
                                                        {{ $order->shipping_address_line1 }}{{ $order->shipping_address_line2 ? ', ' . $order->shipping_address_line2 : '' }},
                                                        {{ $order->shipping_city }}, {{ $order->shipping_state }} - {{ $order->shipping_pincode }}
                                                    </p>
                                                    <p style="margin:0; font-size:12px; color:#a1a1aa;">Phone: {{ $order->shipping_phone }}</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    <!-- Trust Row -->
                    <tr>
                        <td style="padding: 24px 4px 0 4px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td width="33%" class="stack-col" align="center" style="padding:0 6px;">
                                        <p style="margin:0; font-size:11px; color:#71717a;">🛡️ Genuine Products</p>
                                    </td>
                                    <td width="33%" class="stack-col" align="center" style="padding:0 6px;">
                                        <p style="margin:0; font-size:11px; color:#71717a;">📦 Secure Packaging</p>
                                    </td>
                                    <td width="33%" class="stack-col" align="center" style="padding:0 6px;">
                                        <p style="margin:0; font-size:11px; color:#71717a;">🎧 Dedicated Support</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 24px 20px 0 20px;" align="center">
                            <p style="margin:0 0 4px 0; font-size:12px; color:#71717a;">
                                Questions about your order? Contact us at
                                <a href="mailto:{{ shop()->email }}" style="color:#2563eb; font-weight:600;">{{ shop()->email }}</a>
                                or +91 {{ shop()->phone }}
                            </p>
                            <p style="margin:0 0 4px 0; font-size:11px; color:#a1a1aa;">
                                {{ shop()->address_line1 }}, {{ shop()->address_line2 }}, {{ shop()->state }}
                            </p>
                            <p style="margin:12px 0 0 0; font-size:11px; color:#d4d4d8;">
                                &copy; {{ date('Y') }} {{ shop()->name }}. All rights reserved.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>

</html>