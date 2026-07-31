<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmed - Xpert IT Solution</title>
</head>
<body style="margin:0;padding:0;background-color:#f4f4f5;font-family:'Instrument Sans',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;color:#18181b;-webkit-font-smoothing:antialiased;">

<!-- Inbox Preheader Text -->
<div style="display:none;font-size:1px;color:#f4f4f5;line-height:1px;max-height:0px;max-width:0px;opacity:0;overflow:hidden;mso-hide:all;">
    Your order {{ $order->order_number }} has been confirmed! Thank you for purchasing from Xpert IT Solution.
</div>

<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f4f4f5;padding:40px 16px;">
    <tr>
        <td align="center">
            
            <!-- Email Container (Max 640px) -->
            <table width="640" cellpadding="0" cellspacing="0" border="0" style="max-width:640px;width:100%;background-color:#ffffff;border-radius:24px;overflow:hidden;border:1px solid #e4e4e7;box-shadow:0 10px 30px rgba(0,0,0,0.05);">
                
                <!-- Brand Header (Matches Website Dark Header) -->
                <tr>
                    <td style="background-color:#09090b;padding:28px 40px;border-bottom:1px solid #27272a;">
                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td align="left">
                                    <table cellpadding="0" cellspacing="0" border="0">
                                        <tr>
                                            <td style="background-color:#2563eb;width:40px;height:40px;border-radius:10px;text-align:center;vertical-align:middle;">
                                                <!-- SVG Logo Icon -->
                                                <svg width="22" height="22" viewBox="0 0 40 42" fill="none" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">
                                                    <path fill="#ffffff" fill-rule="evenodd" clip-rule="evenodd" d="M17.2 5.633 8.6.855 0 5.633v26.51l16.2 9 16.2-9v-8.442l7.6-4.223V9.856l-8.6-4.777-8.6 4.777V18.3l-5.6 3.111V5.633ZM38 18.301l-5.6 3.11v-6.157l5.6-3.11V18.3Zm-1.06-7.856-5.54 3.078-5.54-3.079 5.54-3.078 5.54 3.079ZM24.8 18.3v-6.157l5.6 3.111v6.158L24.8 18.3Zm-1 1.732 5.54 3.078-13.14 7.302-5.54-3.078 13.14-7.3v-.002Zm-16.2 7.89 7.6 4.222V38.3L2 30.966V7.92l5.6 3.111v16.892ZM8.6 9.3 3.06 6.222 8.6 3.143l5.54 3.08L8.6 9.3Zm21.8 15.51-13.2 7.334V38.3l13.2-7.334v-6.156ZM9.6 11.034l5.6-3.11v14.6l-5.6 3.11v-14.6Z"/>
                                                </svg>
                                            </td>
                                            <td style="padding-left:12px;">
                                                <span style="color:#ffffff;font-size:20px;font-weight:800;letter-spacing:-0.02em;">Xpert </span>
                                                <span style="color:#3b82f6;font-size:20px;font-weight:700;letter-spacing:-0.02em;">IT Solution</span>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td align="right" style="font-size:12px;color:#a1a1aa;font-weight:600;letter-spacing:0.05em;text-transform:uppercase;">
                                    Enterprise Partner
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <!-- Confirmation Banner Hero -->
                <tr>
                    <td style="padding:44px 40px 32px;text-align:center;border-bottom:1px solid #f4f4f5;background:linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);">
                        
                        <!-- Success Checkmark Badge -->
                        <table cellpadding="0" cellspacing="0" border="0" align="center" style="margin:0 auto 20px;">
                            <tr>
                                <td style="background-color:#dbeafe;width:64px;height:64px;border-radius:9999px;text-align:center;vertical-align:middle;box-shadow:0 4px 12px rgba(37,99,235,0.15);">
                                    <span style="color:#2563eb;font-size:32px;line-height:64px;font-weight:bold;">&#10003;</span>
                                </td>
                            </tr>
                        </table>

                        <h1 style="margin:0 0 10px;font-size:28px;font-weight:800;color:#09090b;letter-spacing:-0.03em;">
                            Order Confirmed!
                        </h1>

                        <p style="margin:0 0 20px;font-size:15px;color:#52525b;line-height:1.6;max-width:480px;margin-left:auto;margin-right:auto;">
                            Hi <strong style="color:#18181b;">{{ $user->name }}</strong>, thank you for your purchase. We've confirmed your payment and our technical warehouse team is preparing your shipment.
                        </p>

                        <!-- Order Number Badge -->
                        <div style="display:inline-block;padding:10px 22px;background-color:#09090b;border-radius:9999px;box-shadow:0 4px 10px rgba(0,0,0,0.08);">
                            <span style="font-size:12px;color:#a1a1aa;text-transform:uppercase;letter-spacing:0.08em;font-weight:600;">Order ID: </span>
                            <span style="font-size:14px;font-weight:800;color:#ffffff;font-family:ui-monospace,SFMono-Regular,Menlo,Monaco,Consolas,monospace;letter-spacing:0.04em;">{{ $order->order_number }}</span>
                        </div>

                    </td>
                </tr>

                <!-- Order Meta Info Cards -->
                <tr>
                    <td style="padding:28px 40px 0;">
                        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f8fafc;border-radius:16px;border:1px solid #e2e8f0;">
                            <tr>
                                <td width="50%" style="padding:18px 24px;border-right:1px solid #e2e8f0;">
                                    <div style="font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:4px;">Date Placed</div>
                                    <div style="font-size:14px;font-weight:700;color:#0f172a;">{{ $order->created_at->format('d M Y, h:i A') }}</div>
                                </td>
                                <td width="50%" style="padding:18px 24px;">
                                    <div style="font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:4px;">Payment Method</div>
                                    <div style="font-size:14px;font-weight:700;color:#0f172a;text-transform:capitalize;">{{ str_replace('_', ' ', $order->payment_method) }}</div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <!-- Order Items Section -->
                <tr>
                    <td style="padding:32px 40px 0;">
                        <div style="font-size:12px;font-weight:800;color:#71717a;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:16px;">
                            Purchased Hardware ({{ count($order->items) }} {{ count($order->items) === 1 ? 'Item' : 'Items' }})
                        </div>

                        @foreach($order->items as $item)
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border:1px solid #e4e4e7;border-radius:16px;margin-bottom:12px;background-color:#ffffff;box-shadow:0 2px 6px rgba(0,0,0,0.02);">
                                <tr>
                                    <td style="padding:18px 22px;">
                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td align="left" style="vertical-align:top;">
                                                    <div style="font-size:15px;font-weight:700;color:#09090b;line-height:1.4;margin-bottom:6px;">
                                                        {{ $item->product_name }}
                                                    </div>
                                                    <div style="font-size:12px;color:#71717a;">
                                                        <span style="background-color:#f4f4f5;padding:3px 8px;border-radius:6px;font-weight:600;color:#3f3f46;display:inline-block;">Qty: {{ $item->quantity }}</span>
                                                        @if($item->sku)
                                                            &nbsp;&middot;&nbsp;<span style="font-family:ui-monospace,SFMono-Regular,Menlo,Monaco,Consolas,monospace;color:#71717a;">SKU: {{ $item->sku }}</span>
                                                        @endif
                                                        &nbsp;&middot;&nbsp;<span>₹{{ number_format($item->unit_price, 2) }} each</span>
                                                    </div>
                                                </td>
                                                <td align="right" style="vertical-align:top;white-space:nowrap;padding-left:16px;">
                                                    <div style="font-size:16px;font-weight:800;color:#09090b;letter-spacing:-0.02em;">
                                                        ₹{{ number_format($item->line_total_with_tax, 2) }}
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        @endforeach
                    </td>
                </tr>

                <!-- Payment Breakdown & Summary -->
                <tr>
                    <td style="padding:16px 40px 0;">
                        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#fafafa;border:1px solid #e4e4e7;border-radius:16px;">
                            <tr>
                                <td style="padding:24px;">
                                    <div style="font-size:12px;font-weight:800;color:#71717a;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:14px;">
                                        Payment Breakdown
                                    </div>

                                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                        <tr>
                                            <td style="padding:6px 0;font-size:14px;color:#52525b;">Items Subtotal</td>
                                            <td align="right" style="padding:6px 0;font-size:14px;font-weight:600;color:#18181b;">₹{{ number_format($order->subtotal, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td style="padding:6px 0;font-size:14px;color:#52525b;">Applicable GST (Tax)</td>
                                            <td align="right" style="padding:6px 0;font-size:14px;font-weight:600;color:#18181b;">₹{{ number_format($order->tax_amount, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td style="padding:6px 0;font-size:14px;color:#52525b;">Shipping & Handling</td>
                                            <td align="right" style="padding:6px 0;font-size:14px;font-weight:700;color:#16a34a;">
                                                {{ $order->shipping_fee > 0 ? '₹' . number_format($order->shipping_fee, 2) : 'FREE' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="padding-top:14px;padding-bottom:14px;">
                                                <div style="border-top:1px solid #e4e4e7;"></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:16px;font-weight:800;color:#09090b;">Total Paid</td>
                                            <td align="right" style="font-size:24px;font-weight:900;color:#2563eb;letter-spacing:-0.03em;">
                                                ₹{{ number_format($order->total, 2) }}
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <!-- Shipping Address Card -->
                <tr>
                    <td style="padding:16px 40px 0;">
                        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border:1px solid #e4e4e7;border-radius:16px;background-color:#ffffff;">
                            <tr>
                                <td style="padding:22px 24px;">
                                    <div style="font-size:12px;font-weight:800;color:#71717a;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:10px;">
                                        Delivery Address
                                    </div>

                                    <div style="font-size:15px;font-weight:700;color:#09090b;margin-bottom:4px;">
                                        {{ $order->shipping_name }}
                                    </div>
                                    <div style="font-size:14px;color:#52525b;line-height:1.6;">
                                        {{ $order->shipping_address_line1 }}
                                        @if($order->shipping_address_line2)
                                            , {{ $order->shipping_address_line2 }}
                                        @endif
                                        <br>
                                        {{ $order->shipping_city }}, {{ $order->shipping_state }} - <strong>{{ $order->shipping_pincode }}</strong>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <!-- Invoice Banner (If Invoice Exists) -->
                @if($order->invoice)
                    <tr>
                        <td style="padding:20px 40px 0;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#eff6ff;border:1px solid #bfdbfe;border-radius:16px;">
                                <tr>
                                    <td style="padding:16px 20px;font-size:13.5px;color:#1d4ed8;line-height:1.5;">
                                        📎 <strong>Tax Invoice Attached:</strong> Your official GST invoice <strong style="font-family:ui-monospace,SFMono-Regular,Menlo,Monaco,Consolas,monospace;">{{ $order->invoice->invoice_number }}</strong> has been generated and attached to this email.
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                @endif

                <!-- CTA Action Button -->
                <tr>
                    <td align="center" style="padding:36px 40px 40px;">
                        <table cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td style="background-color:#2563eb;border-radius:12px;box-shadow:0 6px 16px rgba(37,99,235,0.25);">
                                    <a href="{{ route('shop.order.confirmation', $order->order_number) }}" 
                                       target="_blank"
                                       style="display:inline-block;padding:16px 36px;color:#ffffff;text-decoration:none;font-weight:700;font-size:15px;letter-spacing:-0.01em;">
                                        View & Track Order &rarr;
                                    </a>
                                </td>
                            </tr>
                        </table>

                        <p style="margin:20px 0 0;color:#71717a;font-size:13.5px;line-height:1.6;">
                            We'll send you another updates email as soon as your shipment is dispatched.
                        </p>
                    </td>
                </tr>

                <!-- Trust / Value Proposition Highlights -->
                <tr>
                    <td style="background-color:#fafafa;padding:24px 40px;border-top:1px solid #e4e4e7;border-bottom:1px solid #e4e4e7;">
                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td width="33%" align="center" style="padding:6px;">
                                    <div style="font-size:12px;font-weight:700;color:#18181b;">🛡️ 100% Genuine</div>
                                    <div style="font-size:11px;color:#71717a;margin-top:2px;">Brand Warranty</div>
                                </td>
                                <td width="33%" align="center" style="padding:6px;border-left:1px solid #e4e4e7;border-right:1px solid #e4e4e7;">
                                    <div style="font-size:12px;font-weight:700;color:#18181b;">🚚 Express Shipping</div>
                                    <div style="font-size:11px;color:#71717a;margin-top:2px;">Secure Freight</div>
                                </td>
                                <td width="33%" align="center" style="padding:6px;">
                                    <div style="font-size:12px;font-weight:700;color:#18181b;">📞 Expert Support</div>
                                    <div style="font-size:11px;color:#71717a;margin-top:2px;">Dedicated Engineers</div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <!-- Brand Footer (Matches Website Dark Footer) -->
                <tr>
                    <td style="background-color:#09090b;padding:32px 40px;text-align:center;">
                        <div style="font-size:15px;font-weight:800;color:#ffffff;margin-bottom:6px;">
                            Xpert <span style="color:#3b82f6;">IT Solution</span>
                        </div>
                        <p style="margin:0 0 16px;font-size:12.5px;color:#a1a1aa;line-height:1.6;max-width:400px;margin-left:auto;margin-right:auto;">
                            Enterprise IT Infrastructure, CCTV Surveillance Systems, Network Storage & Industrial Backup Hardware Supplier.
                        </p>
                        <p style="margin:0 0 16px;font-size:12px;color:#71717a;">
                            {{ config('shop.company.email', 'info@xpertitsolution.com') }} &nbsp;&middot;&nbsp; {{ config('shop.company.phone', '+91 98765 43210') }}
                        </p>
                        <p style="margin:0;font-size:11px;color:#52525b;">
                            &copy; {{ date('Y') }} Xpert IT Solution. All rights reserved.
                        </p>
                    </td>
                </tr>

            </table>
            <!-- End Container -->

        </td>
    </tr>
</table>

</body>
</html>