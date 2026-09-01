@php
    $statusColors = [
        'pending' => 'zinc',
        'processing' => 'blue',
        'shipped' => 'purple',
        'delivered' => 'emerald',
        'cancelled' => 'rose',
    ];
@endphp

<main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">

    <!-- Breadcrumb Navigation -->
    <nav class="flex items-center gap-2 text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-8">
        <a href="{{ route('home') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition" wire:navigate>Home</a>
        <flux:icon icon="chevron-right" class="size-3 text-zinc-400" />
        <span class="text-zinc-900 dark:text-white font-semibold">My Orders</span>
    </nav>

    <div class="mb-8">
        <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-zinc-900 dark:text-white">My Orders</h2>
        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Track and review everything you've ordered from us.</p>
    </div>

    @if($this->orders->isEmpty())

        <!-- Empty State -->
        <div class="py-20 text-center rounded-3xl border border-dashed border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
            <div class="inline-flex items-center justify-center size-16 rounded-2xl bg-zinc-100 dark:bg-zinc-800 mb-4">
                <flux:icon icon="clipboard-document-list" class="size-8 text-zinc-400" />
            </div>
            <h3 class="text-lg font-bold text-zinc-900 dark:text-white">No orders yet</h3>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1 max-w-sm mx-auto">
                Once you place an order, it'll show up here so you can track its status anytime.
            </p>
            <flux:button href="{{ route('shop.products') }}" wire:navigate variant="filled" size="sm" class="mt-6 bg-blue-600 hover:bg-blue-700 text-white border-0 font-medium">
                Start Shopping
            </flux:button>
        </div>

    @else

        <div class="rounded-3xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm divide-y divide-zinc-100 dark:divide-zinc-800 overflow-hidden">
            @foreach($this->orders as $order)
                <a href="{{ route('shop.order.confirmation', $order->order_number) }}" wire:navigate class="flex flex-col sm:flex-row sm:items-center gap-4 p-5 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition">

                    <div class="flex items-center justify-center size-12 rounded-2xl bg-zinc-100 dark:bg-zinc-800 shrink-0">
                        <flux:icon icon="cube" class="size-5 text-zinc-500 dark:text-zinc-400" />
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-sm font-mono font-bold text-zinc-900 dark:text-white">{{ $order->order_number }}</span>
                            <flux:badge color="{{ $statusColors[$order->status] ?? 'zinc' }}" size="sm" class="capitalize">{{ $order->status }}</flux:badge>
                            <flux:badge color="{{ $order->isPaid() ? 'emerald' : 'amber' }}" size="sm" class="capitalize">Payment {{ $order->payment_status }}</flux:badge>
                        </div>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">
                            {{ $order->created_at->format('d M Y, h:i A') }} &middot; {{ $order->items_count }} {{ Str::plural('item', $order->items_count) }}
                        </p>
                    </div>

                    <div class="flex items-center gap-4 sm:gap-6 pl-16 sm:pl-0">
                        <span class="text-base font-extrabold text-zinc-950 dark:text-white">₹{{ number_format($order->total, 2) }}</span>
                        <flux:icon icon="chevron-right" class="size-4 text-zinc-400" />
                    </div>
                </a>
            @endforeach
        </div>

        @if($this->orders->hasPages())
            <div class="pt-6">
                {{ $this->orders->links(data: ['scrollTo' => false]) }}
            </div>
        @endif

    @endif

</main>
