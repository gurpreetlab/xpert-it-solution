@php
    $statusColors = [
        'pending' => 'zinc',
        'processing' => 'blue',
        'shipped' => 'purple',
        'delivered' => 'emerald',
        'cancelled' => 'rose',
    ];
    $statusColor = $statusColors[$order->status] ?? 'zinc';
@endphp

<main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">

    <!-- Breadcrumb Navigation -->
    <nav class="flex items-center gap-2 text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-8">
        <a href="{{ route('home') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition" wire:navigate>Home</a>
        <flux:icon icon="chevron-right" class="size-3 text-zinc-400" />
        <span class="text-zinc-900 dark:text-white font-semibold">Order Confirmation</span>
    </nav>

    <!-- Success Header -->
    <div class="text-center rounded-3xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm p-8 sm:p-12 mb-8">
        <div class="inline-flex items-center justify-center size-16 rounded-2xl bg-emerald-100 dark:bg-emerald-900/30 mb-5">
            <flux:icon icon="check-circle" class="size-9 text-emerald-600 dark:text-emerald-400" />
        </div>

        @if($order->isPaid())
            <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-zinc-900 dark:text-white">Order Placed Successfully</h2>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-2 max-w-md mx-auto">
                Thank you for your purchase. A confirmation has been sent to your registered email address.
            </p>
        @else
            <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-zinc-900 dark:text-white">Order Received</h2>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-2 max-w-md mx-auto">
                We've recorded your order. If payment hasn't gone through yet, please check your email or contact support.
            </p>
        @endif

        <div class="flex flex-wrap items-center justify-center gap-3 mt-6">
            <span class="px-4 py-1.5 rounded-full bg-zinc-100 dark:bg-zinc-800 text-sm font-mono font-semibold text-zinc-700 dark:text-zinc-300">
                {{ $order->order_number }}
            </span>
            <flux:badge color="{{ $statusColor }}" size="sm" class="capitalize">{{ $order->status }}</flux:badge>
            <flux:badge color="{{ $order->isPaid() ? 'emerald' : 'amber' }}" size="sm" class="capitalize">
                Payment {{ $order->payment_status }}
            </flux:badge>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">

        <!-- Shipping Address -->
        <div class="rounded-3xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm p-6">
            <h3 class="text-sm font-bold tracking-tight text-zinc-900 dark:text-white flex items-center gap-2 mb-4">
                <flux:icon icon="map-pin" class="size-4 text-blue-600" />
                Shipping Address
            </h3>
            <p class="text-sm font-semibold text-zinc-900 dark:text-white">{{ $order->shipping_name }}</p>
            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1 leading-relaxed">
                {{ $order->shipping_address_line1 }}{{ $order->shipping_address_line2 ? ', ' . $order->shipping_address_line2 : '' }},
                {{ $order->shipping_city }}, {{ $order->shipping_state }} - {{ $order->shipping_pincode }}
            </p>
            <p class="text-xs text-zinc-400 mt-1">{{ $order->shipping_phone }}</p>
        </div>

        <!-- Order Meta -->
        <div class="rounded-3xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm p-6">
            <h3 class="text-sm font-bold tracking-tight text-zinc-900 dark:text-white flex items-center gap-2 mb-4">
                <flux:icon icon="information-circle" class="size-4 text-blue-600" />
                Order Details
            </h3>
            <div class="space-y-2 text-xs">
                <div class="flex items-center justify-between">
                    <span class="text-zinc-500 dark:text-zinc-400">Order Date</span>
                    <span class="font-semibold text-zinc-900 dark:text-white">{{ $order->created_at->format('d M Y, h:i A') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-zinc-500 dark:text-zinc-400">Payment Method</span>
                    <span class="font-semibold text-zinc-900 dark:text-white capitalize">{{ $order->payment_method }}</span>
                </div>
                @if($order->razorpay_payment_id)
                    <div class="flex items-center justify-between">
                        <span class="text-zinc-500 dark:text-zinc-400">Payment ID</span>
                        <span class="font-mono font-semibold text-zinc-900 dark:text-white">{{ $order->razorpay_payment_id }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Items -->
    <div class="rounded-3xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm p-6 sm:p-8 mb-6">
        <h3 class="text-sm font-bold tracking-tight text-zinc-900 dark:text-white flex items-center gap-2 mb-4">
            <flux:icon icon="cube" class="size-4 text-blue-600" />
            Items in this Order
        </h3>

        <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
            @foreach($order->items as $item)
                <div class="flex items-center gap-4 py-4 first:pt-0 last:pb-0">
                    <div class="size-14 shrink-0 rounded-xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center overflow-hidden">
                        @if($item->product?->primaryImage?->path)
                            <img src="{{ asset('storage/' . $item->product->primaryImage->path) }}" alt="{{ $item->product_name }}" class="size-full object-contain p-1" />
                        @else
                            <flux:icon icon="cube" class="size-6 text-zinc-400" />
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-zinc-900 dark:text-white truncate">{{ $item->product_name }}</p>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">
                            Qty: {{ $item->quantity }} &times; ₹{{ number_format($item->unit_price, 2) }}
                            @if($item->sku)
                                &middot; SKU: {{ $item->sku }}
                            @endif
                        </p>
                    </div>
                    <span class="text-sm font-bold text-zinc-900 dark:text-white">₹{{ number_format($item->line_total, 2) }}</span>
                </div>
            @endforeach
        </div>

        <div class="pt-4 mt-4 border-t border-zinc-200 dark:border-zinc-800 space-y-2">
            <div class="flex items-center justify-between text-sm text-zinc-600 dark:text-zinc-300">
                <span>Price</span>
                <span class="font-semibold text-zinc-900 dark:text-white">₹{{ number_format($order->subtotal + $order->discount, 2) }}</span>
            </div>
            @if($order->discount > 0)
                <div class="flex items-center justify-between text-sm text-emerald-600 dark:text-emerald-400">
                    <span>Savings</span>
                    <span class="font-semibold">- ₹{{ number_format($order->discount, 2) }}</span>
                </div>
            @endif
            <div class="flex items-center justify-between text-sm text-zinc-600 dark:text-zinc-300 pt-2 border-t border-dashed border-zinc-100 dark:border-zinc-800">
                <span>Subtotal</span>
                <span class="font-semibold text-zinc-900 dark:text-white">₹{{ number_format($order->subtotal, 2) }}</span>
            </div>

            <div class="flex items-center justify-between text-sm text-zinc-600 dark:text-zinc-300">
                <span>Shipping</span>
                <span class="font-semibold {{ $order->shipping_fee > 0 ? 'text-zinc-900 dark:text-white' : 'text-emerald-600 dark:text-emerald-400' }}">
                    {{ $order->shipping_fee > 0 ? '₹' . number_format($order->shipping_fee, 2) : 'Free' }}
                </span>
            </div>
            <div class="flex items-center justify-between pt-2 border-t border-zinc-100 dark:border-zinc-800">
                <span class="text-sm font-bold text-zinc-900 dark:text-white">Total Paid</span>
                <span class="text-xl font-black tracking-tight text-zinc-950 dark:text-white">₹{{ number_format($order->total, 2) }}</span>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
        <flux:button href="{{ route('shop.products') }}" wire:navigate variant="filled" size="sm" class="bg-blue-600 hover:bg-blue-700 text-white border-0 font-medium">
            Continue Shopping
        </flux:button>
        <flux:button href="{{ route('shop.orders') }}" wire:navigate variant="ghost" size="sm" class="text-zinc-600 dark:text-zinc-400">
            View My Orders
        </flux:button>
    </div>

</main>
