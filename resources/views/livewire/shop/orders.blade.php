<main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs font-medium text-zinc-500 mb-2">
        <a href="{{ route('home') }}" class="hover:text-primary transition" wire:navigate>Home</a>
        <flux:icon icon="chevron-right" class="size-3 text-zinc-400" />
        <span class="text-zinc-900 font-semibold">My Account & Orders</span>
    </nav>

    <!-- Page Title -->
    <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-zinc-900">Account Portal</h1>
        <p class="text-xs sm:text-sm text-zinc-500 mt-1">Manage orders, track shipping status, and download tax invoices.</p>
    </div>

    <!-- Layout Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        <!-- Account Nav Sidebar (Cols 1-3) -->
        <div class="lg:col-span-3 rounded-2xl border border-border bg-surface p-4 shadow-2xs space-y-2">
            <span class="text-[10px] font-bold uppercase tracking-wider text-zinc-400 px-3 block">Navigation</span>

            <a href="{{ route('shop.orders') }}" wire:navigate class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-bold bg-primary/10 text-primary">
                <flux:icon icon="shopping-bag" class="size-4" />
                <span>My Orders</span>
            </a>

            <a href="{{ route('shop.wishlist') }}" wire:navigate class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold text-zinc-600 hover:bg-surface-muted transition">
                <flux:icon icon="heart" class="size-4" />
                <span>Wishlist</span>
            </a>

            <a href="{{ route('shop.compare') }}" wire:navigate class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold text-zinc-600 hover:bg-surface-muted transition">
                <flux:icon icon="scale" class="size-4" />
                <span>Product Comparison</span>
            </a>

            <a href="{{ route('shop.bulk-orders') }}" wire:navigate class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold text-zinc-600 hover:bg-surface-muted transition">
                <flux:icon icon="document-text" class="size-4" />
                <span>Bulk Quotes</span>
            </a>

            <a href="{{ route('profile.edit') }}" wire:navigate class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold text-zinc-600 hover:bg-surface-muted transition">
                <flux:icon icon="user-circle" class="size-4" />
                <span>Account Profile</span>
            </a>
        </div>

        <!-- Orders Feed (Cols 4-12) -->
        <div class="lg:col-span-9 space-y-4">
            @if($this->orders->isEmpty())
                <div class="py-16 text-center rounded-2xl border border-dashed border-border bg-surface shadow-2xs">
                    <div class="inline-flex items-center justify-center size-14 rounded-2xl bg-surface-muted mb-3">
                        <flux:icon icon="shopping-bag" class="size-7 text-primary" />
                    </div>
                    <h2 class="text-base font-bold text-zinc-900">No orders placed yet</h2>
                    <p class="text-xs text-zinc-500 mt-1 max-w-sm mx-auto">
                        Your order history, tracking details, and GST invoices will appear here.
                    </p>
                    <a href="{{ route('shop.products') }}" wire:navigate class="mt-5 inline-block px-4 py-2.5 rounded-xl bg-primary text-white text-xs font-bold hover:bg-primary-hover transition shadow-2xs">
                        Start Shopping
                    </a>
                </div>
            @else
                <div class="rounded-2xl border border-border bg-surface shadow-2xs divide-y divide-border overflow-hidden">
                    @foreach($this->orders as $order)
                        <div class="p-5 space-y-4">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-border">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-mono font-extrabold text-zinc-900">Order #{{ $order->order_number }}</span>
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-surface-muted border border-border text-zinc-700">
                                            {{ $order->status }}
                                        </span>
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $order->isPaid() ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                            {{ $order->payment_status }}
                                        </span>
                                    </div>
                                    <span class="text-xs text-zinc-400 mt-0.5 block">Placed on {{ $order->created_at->format('d M Y, h:i A') }}</span>
                                </div>

                                <div class="text-right flex items-center gap-3">
                                    <div>
                                        <span class="text-xs text-zinc-400 block">Total Paid</span>
                                        <span class="text-base font-extrabold text-zinc-950">₹{{ number_format($order->total) }}</span>
                                    </div>

                                    <a
                                        href="{{ route('shop.order.confirmation', $order->order_number) }}"
                                        wire:navigate
                                        class="px-3 py-1.5 rounded-lg border border-border bg-surface-muted text-xs font-semibold text-zinc-700 hover:bg-border transition">
                                        View Details →
                                    </a>
                                </div>
                            </div>

                            <!-- Order Status Progress Timeline -->
                            <div class="grid grid-cols-4 gap-2 text-center text-[10px] font-bold">
                                <div class="p-2 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span>1. Ordered</span>
                                </div>
                                <div class="p-2 rounded-lg {{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-surface-muted text-zinc-400' }}">
                                    <span>2. Confirmed</span>
                                </div>
                                <div class="p-2 rounded-lg {{ in_array($order->status, ['shipped', 'delivered']) ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-surface-muted text-zinc-400' }}">
                                    <span>3. Shipped</span>
                                </div>
                                <div class="p-2 rounded-lg {{ $order->status === 'delivered' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-surface-muted text-zinc-400' }}">
                                    <span>4. Delivered</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($this->orders->hasPages())
                    <div class="pt-4">
                        {{ $this->orders->links(data: ['scrollTo' => false]) }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</main>
