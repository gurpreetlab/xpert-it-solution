<div class="space-y-6">

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-3">
                <flux:heading size="xl" level="1" class="font-mono">{{ $order->order_number }}</flux:heading>
                <flux:badge size="sm" color="{{ match($order->status) {
                    'processing' => 'blue',
                    'shipped' => 'purple',
                    'delivered' => 'emerald',
                    'cancelled' => 'red',
                    default => 'zinc',
                } }}" class="capitalize">{{ $order->status }}</flux:badge>
                <flux:badge size="sm" color="{{ match($order->payment_status) {
                    'paid' => 'emerald',
                    'failed' => 'red',
                    'refunded' => 'amber',
                    default => 'zinc',
                } }}" class="capitalize">Payment {{ $order->payment_status }}</flux:badge>
            </div>
            <flux:text class="mt-1 text-gray-500">
                Placed by {{ $order->user?->name ?? 'Deleted user' }} on {{ $order->created_at->format('d M Y, h:i A') }}
            </flux:text>
        </div>

        <div class="flex items-center gap-2">
            @if($order->invoice)
                <flux:button href="{{ route('dashboard.orders.invoice', $order) }}" icon="arrow-down-tray" variant="primary">
                    Download Invoice
                </flux:button>
            @else
                <flux:button variant="ghost" icon="arrow-down-tray" disabled title="Invoice is generated automatically once payment is confirmed">
                    Invoice Pending
                </flux:button>
            @endif
            <flux:button href="{{ route('dashboard.orders.index') }}" wire:navigate variant="ghost" icon="arrow-left">
                Back to Orders
            </flux:button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left: Items + Totals -->
        <div class="lg:col-span-2 space-y-6">

            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Product</flux:table.column>
                    <flux:table.column>Sku</flux:table.column>
                    <flux:table.column>Hsn Code</flux:table.column>
                    <flux:table.column>Rate</flux:table.column>
                    <flux:table.column>Qty</flux:table.column>
                    <flux:table.column>Taxable Amt</flux:table.column>
                    <flux:table.column>GST</flux:table.column>
                    <flux:table.column>Total</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse($order->items as $item)
                        <flux:table.row :key="$item->id">
                            <flux:table.cell class="whitespace-nowrap">
                                @if($item->product)
                                    <a href="{{ route('dashboard.products.show', $item->product) }}" wire:navigate class="hover:text-emerald-400">
                                        {{ $item->product_name }}
                                    </a>
                                @else
                                    <span class="text-gray-500">{{ $item->product_name }} (deleted)</span>
                                @endif
                            </flux:table.cell>
                            <flux:table.cell class="whitespace-nowrap">{{ $item->sku ?? '-' }}</flux:table.cell>
                            <flux:table.cell class="whitespace-nowrap">{{ $item->hsn_code ?? '-' }}</flux:table.cell>
                            <flux:table.cell class="whitespace-nowrap">₹{{ number_format($item->unit_price, 2) }}</flux:table.cell>
                            <flux:table.cell class="whitespace-nowrap">{{ $item->quantity }}</flux:table.cell>
                            <flux:table.cell class="whitespace-nowrap">₹{{ number_format($item->line_total, 2) }}</flux:table.cell>
                            <flux:table.cell class="whitespace-nowrap">
                                ₹{{ number_format($item->tax_amount, 2) }}
                                <span class="text-gray-500 text-xs">({{ rtrim(rtrim(number_format($item->tax_rate, 2), '0'), '.') }}%)</span>
                            </flux:table.cell>
                            <flux:table.cell class="whitespace-nowrap font-semibold">₹{{ number_format($item->line_total_with_tax, 2) }}</flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="8" class="text-center">No items on this order.</flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>

            <!-- Totals -->
            <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 p-5 space-y-2 ml-auto max-w-xs">
                <div class="flex items-center justify-between text-sm">
                    <flux:text class="text-gray-500">Price</flux:text>
                    <span>₹{{ number_format($order->subtotal + $order->discount, 2) }}</span>
                </div>
                @if($order->discount > 0)
                    <div class="flex items-center justify-between text-sm">
                        <flux:text class="text-gray-500">Discount</flux:text>
                        <span>- ₹{{ number_format($order->discount, 2) }}</span>
                    </div>
                @endif
                <div class="flex items-center justify-between text-sm pt-2 border-t border-dashed border-zinc-200 dark:border-zinc-700">
                    <flux:text class="text-gray-500">Subtotal</flux:text>
                    <span>₹{{ number_format($order->subtotal, 2) }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <flux:text class="text-gray-500">Shipping</flux:text>
                    <span>{{ $order->shipping_fee > 0 ? '₹' . number_format($order->shipping_fee, 2) : 'Free' }}</span>
                </div>
                @if($order->tax_amount > 0)
                    <div class="flex items-center justify-between text-sm">
                        <flux:text class="text-gray-500">Tax</flux:text>
                        <span>₹{{ number_format($order->tax_amount, 2) }}</span>
                    </div>
                @endif
                <div class="flex items-center justify-between pt-2 border-t border-zinc-200 dark:border-zinc-700 font-semibold">
                    <span>Total</span>
                    <span>₹{{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Right: Customer / Shipping / Payment / Status -->
        <div class="space-y-6">

            <!-- Update Status -->
            <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 p-5 space-y-4">
                <flux:heading size="sm">Order Status</flux:heading>
                <flux:select wire:model="newStatus">
                    <flux:select.option value="pending">Pending</flux:select.option>
                    <flux:select.option value="processing">Processing</flux:select.option>
                    <flux:select.option value="shipped">Shipped</flux:select.option>
                    <flux:select.option value="delivered">Delivered</flux:select.option>
                    <flux:select.option value="cancelled">Cancelled</flux:select.option>
                </flux:select>
                <flux:button wire:click="updateStatus" variant="primary" class="w-full">
                    Save Status
                </flux:button>
            </div>

            <!-- Customer -->
            <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 p-5 space-y-1">
                <flux:heading size="sm">Customer</flux:heading>
                <flux:text>{{ $order->user?->name ?? 'Deleted user' }}</flux:text>
                <flux:text class="text-gray-500 text-sm">{{ $order->user?->email }}</flux:text>
            </div>

            <!-- Shipping Address -->
            <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 p-5 space-y-1">
                <flux:heading size="sm">Shipping Address</flux:heading>
                <flux:text>{{ $order->shipping_name }}</flux:text>
                <flux:text class="text-gray-500 text-sm">
                    {{ $order->shipping_address_line1 }}{{ $order->shipping_address_line2 ? ', ' . $order->shipping_address_line2 : '' }},
                    {{ $order->shipping_city }}, {{ $order->shipping_state }} - {{ $order->shipping_pincode }}
                </flux:text>
                <flux:text class="text-gray-500 text-sm">{{ $order->shipping_phone }}</flux:text>
            </div>

            <!-- Payment -->
            <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 p-5 space-y-1">
                <flux:heading size="sm">Payment</flux:heading>
                <div class="flex items-center justify-between text-sm">
                    <flux:text class="text-gray-500">Method</flux:text>
                    <span class="capitalize">{{ $order->payment_method }}</span>
                </div>
                @if($order->razorpay_order_id)
                    <div class="flex items-center justify-between text-sm">
                        <flux:text class="text-gray-500">Razorpay Order ID</flux:text>
                        <span class="font-mono text-xs">{{ $order->razorpay_order_id }}</span>
                    </div>
                @endif
                @if($order->razorpay_payment_id)
                    <div class="flex items-center justify-between text-sm">
                        <flux:text class="text-gray-500">Payment ID</flux:text>
                        <span class="font-mono text-xs">{{ $order->razorpay_payment_id }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
