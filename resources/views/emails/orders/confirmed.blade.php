<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmed</title>
</head>
<body style="margin:0;padding:0;background:#fafafa;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;color:#18181b;">

<!-- Preheader (hidden preview text in inbox) -->
<div style="display:none;max-height:0;overflow:hidden;mso-hide:all;">
    Your order {{ $order->order_number }} is confirmed — thanks for shopping with Xpert IT Solution.
</div>

<table width="100%" cellpadding="0" cellspacing="0" style="background:#fafafa;padding:32px 15px;">
<tr>
<td align="center">

<table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:16px;overflow:hidden;border:1px solid #e4e4e7;">

    {{-- Brand Header (matches site header: dark bar, blue mark, wordmark) --}}
    <tr>
        <td style="background:#18181b;padding:24px 40px;">
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        <table cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="background:#2563eb;width:36px;height:36px;border-radius:8px;text-align:center;vertical-align:middle;">
                                    <span style="color:#ffffff;font-weight:700;font-size:16px;line-height:36px;">X</span>
                                </td>
                                <td style="padding-left:10px;">
                                    <span style="color:#ffffff;font-size:17px;font-weight:700;">Xpert </span><span style="color:#60a5fa;font-size:17px;font-weight:700;">IT Solution</span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    {{-- Hero / Confirmation Banner --}}
    <tr>
        <td style="padding:40px 40px 28px;text-align:center;border-bottom:1px solid #f4f4f5;">

            <table cellpadding="0" cellspacing="0" align="center" style="margin:0 auto;">
                <tr>
                    <td style="background:#eff6ff;width:56px;height:56px;border-radius:9999px;text-align:center;vertical-align:middle;">
                        <span style="color:#2563eb;font-size:26px;line-height:56px;">&#10003;</span>
                    </td>
                </tr>
            </table>

            <h1 style="margin:20px 0 8px;font-size:26px;font-weight:800;color:#09090b;letter-spacing:-0.02em;">
                Order Confirmed
            </h1>

            <p style="margin:0;font-size:15px;color:#71717a;line-height:1.6;">
                Hi {{ $user->name }}, thank you for your purchase.<br>
                We've received your payment and your order is now being prepared.
            </p>

            <div style="margin-top:20px;display:inline-block;padding:8px 18px;background:#f4f4f5;border-radius:9999px;">
                <span style="font-size:13px;color:#71717a;">Order </span><span style="font-size:13px;font-weight:700;color:#18181b;font-family:monospace;">{{ $order->order_number }}</span>
            </div>

        </td>
    </tr>

    {{-- Order Meta --}}
    <tr>
        <td style="padding:24px 40px 0;">
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="font-size:13px;color:#71717a;padding-bottom:6px;">Order Date</td>
                    <td align="right" style="font-size:13px;font-weight:600;color:#18181b;padding-bottom:6px;">
                        {{ $order->created_at->format('d M Y, h:i A') }}
                    </td>
                </tr>
                <tr>
                    <td style="font-size:13px;color:#71717a;">Payment Method</td>
                    <td align="right" style="font-size:13px;font-weight:600;color:#18181b;text-transform:capitalize;">
                        {{ $order->payment_method }}
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    {{-- Items --}}
    <tr>
        <td style="padding:28px 40px 0;">
            <h2 style="margin:0 0 16px;font-size:15px;font-weight:700;color:#18181b;text-transform:uppercase;letter-spacing:0.03em;">
                Your Items
            </h2>

            @foreach($order->items as $item)
                <table width="100%" cellpadding="0" cellspacing="0"
                    style="border:1px solid #e4e4e7;border-radius:12px;margin-bottom:12px;background:#fafafa;">
                    <tr>
                        <td style="padding:16px 18px;">
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="font-size:14px;font-weight:600;color:#18181b;">
                                        {{ $item->product_name }}
                                    </td>
                                    <td align="right" style="font-size:15px;font-weight:700;color:#09090b;white-space:nowrap;">
                                        ₹{{ number_format($item->line_total_with_tax, 2) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="font-size:12.5px;color:#71717a;padding-top:4px;">
                                        Qty {{ $item->quantity }} &times; ₹{{ number_format($item->unit_price, 2) }}
                                        @if($item->sku)
                                            &middot; SKU: {{ $item->sku }}
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            @endforeach
        </td>
    </tr>

    {{-- Payment Summary --}}
    <tr>
        <td style="padding:16px 40px 0;">
            <table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e4e4e7;border-radius:12px;">
                <tr>
                    <td style="padding:22px 22px 18px;">
                        <h2 style="margin:0 0 16px;font-size:15px;font-weight:700;color:#18181b;text-transform:uppercase;letter-spacing:0.03em;">
                            Payment Summary
                        </h2>

                        <table width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="padding:5px 0;font-size:13.5px;color:#71717a;">Subtotal</td>
                                <td align="right" style="padding:5px 0;font-size:13.5px;color:#18181b;">₹{{ number_format($order->subtotal, 2) }}</td>
                            </tr>
                            <tr>
                                <td style="padding:5px 0;font-size:13.5px;color:#71717a;">GST</td>
                                <td align="right" style="padding:5px 0;font-size:13.5px;color:#18181b;">₹{{ number_format($order->tax_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <td style="padding:5px 0;font-size:13.5px;color:#71717a;">Shipping</td>
                                <td align="right" style="padding:5px 0;font-size:13.5px;color:#16a34a;">
                                    {{ $order->shipping_fee > 0 ? '₹' . number_format($order->shipping_fee, 2) : 'Free' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding-top:12px;">
                                    <div style="border-top:1px solid #e4e4e7;"></div>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-top:12px;font-size:15px;font-weight:700;color:#09090b;">Total Paid</td>
                                <td align="right" style="padding-top:12px;font-size:21px;font-weight:800;color:#2563eb;letter-spacing:-0.02em;">
                                    ₹{{ number_format($order->total, 2) }}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    {{-- Shipping Address --}}
    <tr>
        <td style="padding:16px 40px 0;">
            <table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e4e4e7;border-radius:12px;">
                <tr>
                    <td style="padding:22px;">
                        <h2 style="margin:0 0 12px;font-size:15px;font-weight:700;color:#18181b;text-transform:uppercase;letter-spacing:0.03em;">
                            Shipping Address
                        </h2>

                        <div style="font-size:14px;font-weight:600;color:#18181b;">
                            {{ $order->shipping_name }}
                        </div>
                        <div style="margin-top:6px;color:#71717a;font-size:13.5px;line-height:1.7;">
                            {{ $order->shipping_address_line1 }}
                            @if($order->shipping_address_line2)
                                , {{ $order->shipping_address_line2 }}
                            @endif
                            <br>
                            {{ $order->shipping_city }}, {{ $order->shipping_state }} - {{ $order->shipping_pincode }}
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    {{-- Invoice note --}}
    @if($order->invoice)
        <tr>
            <td style="padding:20px 40px 0;">
                <table width="100%" cellpadding="0" cellspacing="0" style="background:#eff6ff;border-radius:12px;">
                    <tr>
                        <td style="padding:14px 18px;font-size:13px;color:#1d4ed8;">
                            📎 Your GST invoice <strong style="font-family:monospace;">{{ $order->invoice->invoice_number }}</strong> is attached to this email.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    @endif

    {{-- CTA --}}
    <tr>
        <td align="center" style="padding:32px 40px 40px;">
            <a href="{{ route('shop.order.confirmation', $order->order_number) }}"
               style="background:#2563eb;color:#ffffff;text-decoration:none;padding:14px 32px;border-radius:8px;display:inline-block;font-weight:600;font-size:15px;">
                View Order
            </a>

            <p style="margin-top:20px;color:#71717a;font-size:13.5px;line-height:1.7;">
                We'll email you again as soon as your order has shipped.
            </p>
        </td>
    </tr>

    {{-- Footer (matches site footer: dark bar, zinc-400 text) --}}
    <tr>
        <td style="background:#18181b;padding:28px 40px;text-align:center;">
            <p style="margin:0;font-size:13.5px;color:#d4d4d8;">
                Thank you for shopping with <strong style="color:#ffffff;">Xpert IT Solution</strong>
            </p>
            <p style="margin:10px 0 0;font-size:12px;color:#71717a;">
                {{ config('shop.company.email') }} &middot; {{ config('shop.company.phone') }}
            </p>
            <p style="margin:14px 0 0;font-size:11px;color:#52525b;">
                &copy; {{ date('Y') }} Xpert IT Solution. All rights reserved.
            </p>
        </td>
    </tr>

</table>

</td>
</tr>
</table>

</body>
</html>