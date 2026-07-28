<div class="space-y-6">

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:heading size="xl" level="1">Orders</flux:heading>
            <flux:text class="mt-1 text-gray-500">Manage customer orders and payments</flux:text>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
        <flux:input
            wire:model.live.debounce.400ms="search"
            placeholder="Search order #, customer name or email..."
            icon="magnifying-glass"
            class="lg:col-span-2"
        />

        <flux:select wire:model.live="status">
            <flux:select.option value="">All statuses</flux:select.option>
            <flux:select.option value="pending">Pending</flux:select.option>
            <flux:select.option value="processing">Processing</flux:select.option>
            <flux:select.option value="shipped">Shipped</flux:select.option>
            <flux:select.option value="delivered">Delivered</flux:select.option>
            <flux:select.option value="cancelled">Cancelled</flux:select.option>
        </flux:select>

        <div class="flex gap-2">
            <flux:select wire:model.live="paymentStatus" class="flex-1">
                <flux:select.option value="">All payments</flux:select.option>
                <flux:select.option value="pending">Pending</flux:select.option>
                <flux:select.option value="paid">Paid</flux:select.option>
                <flux:select.option value="failed">Failed</flux:select.option>
                <flux:select.option value="refunded">Refunded</flux:select.option>
            </flux:select>

            @if ($search !== '' || $status !== '' || $paymentStatus !== '')
                <flux:button variant="ghost" icon="x-mark" wire:click="clearFilters" title="Clear filters" />
            @endif
        </div>
    </div>

    <!-- Orders Table -->
    <flux:table :paginate="$orders">
        <flux:table.columns>
            <flux:table.column>Order</flux:table.column>
            <flux:table.column>Customer</flux:table.column>
            <flux:table.column>Items</flux:table.column>
            <flux:table.column>Total</flux:table.column>
            <flux:table.column>Payment</flux:table.column>
            <flux:table.column>Status</flux:table.column>
            <flux:table.column>Placed At</flux:table.column>
            <flux:table.column>Actions</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse($orders as $order)
                <flux:table.row :key="$order->id">
                    <flux:table.cell class="whitespace-nowrap">
                        <a href="{{ route('dashboard.orders.show', $order) }}" class="font-mono hover:text-emerald-400">
                            {{ $order->order_number }}
                        </a>
                    </flux:table.cell>

                    <flux:table.cell class="whitespace-nowrap">
                        <div class="flex flex-col">
                            <span>{{ $order->user?->name ?? 'Deleted user' }}</span>
                            <span class="text-xs text-gray-500">{{ $order->user?->email }}</span>
                        </div>
                    </flux:table.cell>

                    <flux:table.cell class="whitespace-nowrap">{{ $order->items_count }}</flux:table.cell>

                    <flux:table.cell class="whitespace-nowrap">₹{{ number_format($order->total, 2) }}</flux:table.cell>

                    <flux:table.cell class="whitespace-nowrap">
                        <flux:badge size="sm" color="{{ match($order->payment_status) {
                            'paid' => 'emerald',
                            'failed' => 'red',
                            'refunded' => 'amber',
                            default => 'zinc',
                        } }}" class="capitalize">
                            {{ $order->payment_status }}
                        </flux:badge>
                    </flux:table.cell>

                    <flux:table.cell class="whitespace-nowrap">
                        <flux:badge size="sm" color="{{ match($order->status) {
                            'processing' => 'blue',
                            'shipped' => 'purple',
                            'delivered' => 'emerald',
                            'cancelled' => 'red',
                            default => 'zinc',
                        } }}" class="capitalize">
                            {{ $order->status }}
                        </flux:badge>
                    </flux:table.cell>

                    <flux:table.cell class="whitespace-nowrap">{{ $order->created_at->format('d-m-Y h:i A') }}</flux:table.cell>

                    <flux:table.cell class="whitespace-nowrap gap-2 flex">
                        <flux:button size="sm" href="{{ route('dashboard.orders.show', $order) }}" wire:navigate>
                            View
                        </flux:button>
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="8" class="text-center">No orders found.</flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>
</div>
