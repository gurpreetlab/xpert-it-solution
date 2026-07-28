@php
    $stats = $this->stats;
    $trend = $this->revenueTrend;
    $statusColors = [
        'pending' => 'zinc',
        'processing' => 'blue',
        'shipped' => 'purple',
        'delivered' => 'emerald',
        'cancelled' => 'red',
    ];
    $statusBarClasses = [
        'pending' => 'bg-zinc-500',
        'processing' => 'bg-blue-500',
        'shipped' => 'bg-purple-500',
        'delivered' => 'bg-emerald-500',
        'cancelled' => 'bg-red-500',
    ];
@endphp

<div class="flex h-full w-full flex-1 flex-col gap-6">

    <div>
        <flux:heading size="xl" level="1">Dashboard</flux:heading>
        <flux:text class="mt-1 text-gray-500">A snapshot of sales, orders and inventory health.</flux:text>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-5">
            <flux:text class="text-gray-500 text-xs uppercase tracking-wide font-semibold">Total Revenue</flux:text>
            <div class="mt-2 text-2xl font-bold">₹{{ number_format($stats['total_revenue'], 2) }}</div>
            <div class="mt-1 flex items-center gap-1 text-xs">
                <flux:icon icon="{{ $stats['revenue_growth'] >= 0 ? 'arrow-trending-up' : 'arrow-trending-down' }}" class="size-3.5 {{ $stats['revenue_growth'] >= 0 ? 'text-emerald-500' : 'text-red-500' }}" />
                <span class="{{ $stats['revenue_growth'] >= 0 ? 'text-emerald-500' : 'text-red-500' }} font-semibold">{{ abs($stats['revenue_growth']) }}%</span>
                <span class="text-gray-500">vs last month</span>
            </div>
        </div>

        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-5">
            <flux:text class="text-gray-500 text-xs uppercase tracking-wide font-semibold">Orders This Month</flux:text>
            <div class="mt-2 text-2xl font-bold">{{ $stats['orders_this_month'] }}</div>
            <div class="mt-1 flex items-center gap-1 text-xs">
                <flux:icon icon="{{ $stats['order_growth'] >= 0 ? 'arrow-trending-up' : 'arrow-trending-down' }}" class="size-3.5 {{ $stats['order_growth'] >= 0 ? 'text-emerald-500' : 'text-red-500' }}" />
                <span class="{{ $stats['order_growth'] >= 0 ? 'text-emerald-500' : 'text-red-500' }} font-semibold">{{ abs($stats['order_growth']) }}%</span>
                <span class="text-gray-500">vs last month</span>
            </div>
        </div>

        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-5">
            <flux:text class="text-gray-500 text-xs uppercase tracking-wide font-semibold">Pending Orders</flux:text>
            <div class="mt-2 text-2xl font-bold">{{ $stats['pending_orders'] }}</div>
            <div class="mt-1 text-xs text-gray-500">
                @if($stats['failed_payments'] > 0)
                    <span class="text-red-500 font-semibold">{{ $stats['failed_payments'] }}</span> failed payments
                @else
                    No failed payments
                @endif
            </div>
        </div>

        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-5">
            <flux:text class="text-gray-500 text-xs uppercase tracking-wide font-semibold">Low Stock Alerts</flux:text>
            <div class="mt-2 text-2xl font-bold {{ $stats['low_stock_count'] > 0 ? 'text-amber-500' : '' }}">{{ $stats['low_stock_count'] }}</div>
            <div class="mt-1 text-xs text-gray-500">out of {{ $stats['total_products'] }} products</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Revenue Trend -->
        <div class="lg:col-span-2 rounded-xl border border-neutral-200 dark:border-neutral-700 p-5">
            <flux:heading size="sm">Revenue Trend (Last 6 Months)</flux:heading>
            <div class="mt-4" wire:ignore>
                <canvas id="revenue-trend-chart" height="110"
                    data-labels="{{ json_encode($trend['labels']) }}"
                    data-values="{{ json_encode($trend['data']) }}"
                ></canvas>
            </div>
        </div>

        <!-- Orders by Status -->
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-5">
            <flux:heading size="sm">Orders by Status</flux:heading>
            <div class="mt-4 space-y-3">
                @forelse($this->ordersByStatus as $status => $count)
                    @php
                        $total = $this->ordersByStatus->sum();
                        $percent = $total > 0 ? round(($count / $total) * 100) : 0;
                    @endphp
                    <div>
                        <div class="flex items-center justify-between text-xs mb-1">
                            <flux:badge size="sm" color="{{ $statusColors[$status] ?? 'zinc' }}" class="capitalize">{{ $status }}</flux:badge>
                            <span class="text-gray-500">{{ $count }} orders</span>
                        </div>
                        <div class="h-1.5 rounded-full bg-neutral-100 dark:bg-neutral-800 overflow-hidden">
                            <div class="h-full rounded-full {{ $statusBarClasses[$status] ?? 'bg-zinc-500' }}" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>
                @empty
                    <flux:text class="text-gray-500 text-sm">No orders yet.</flux:text>
                @endforelse
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Recent Orders -->
        <div class="lg:col-span-2 rounded-xl border border-neutral-200 dark:border-neutral-700 p-5">
            <div class="flex items-center justify-between mb-4">
                <flux:heading size="sm">Recent Orders</flux:heading>
                <flux:button href="{{ route('dashboard.orders.index') }}" wire:navigate variant="ghost" size="sm">View All</flux:button>
            </div>

            <div class="space-y-1">
                @forelse($this->recentOrders as $order)
                    <a href="{{ route('dashboard.orders.show', $order) }}" wire:navigate class="flex items-center justify-between gap-3 py-2.5 px-2 -mx-2 rounded-lg hover:bg-neutral-50 dark:hover:bg-neutral-800/50 transition">
                        <div class="min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="font-mono text-sm font-semibold">{{ $order->order_number }}</span>
                                <flux:badge size="sm" color="{{ $statusColors[$order->status] ?? 'zinc' }}" class="capitalize">{{ $order->status }}</flux:badge>
                            </div>
                            <div class="text-xs text-gray-500 truncate">{{ $order->user?->name ?? 'Deleted user' }} &middot; {{ $order->created_at->diffForHumans() }}</div>
                        </div>
                        <span class="text-sm font-semibold shrink-0">₹{{ number_format($order->total, 2) }}</span>
                    </a>
                @empty
                    <flux:text class="text-gray-500 text-sm">No orders placed yet.</flux:text>
                @endforelse
            </div>
        </div>

        <!-- Low Stock Alerts -->
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-5">
            <div class="flex items-center justify-between mb-4">
                <flux:heading size="sm">Low Stock</flux:heading>
                <flux:icon icon="exclamation-triangle" class="size-4 text-amber-500" />
            </div>

            <div class="space-y-3">
                @forelse($this->lowStockProducts as $product)
                    <a href="{{ route('dashboard.products.show', $product) }}" wire:navigate class="flex items-center justify-between gap-2 hover:text-emerald-500 transition">
                        <div class="min-w-0">
                            <div class="text-sm truncate">{{ $product->name }}</div>
                            <div class="text-xs text-gray-500">{{ $product->sku ?? '-' }}</div>
                        </div>
                        <flux:badge size="sm" color="{{ $product->stock == 0 ? 'red' : 'amber' }}">
                            {{ $product->stock == 0 ? 'Out of stock' : $product->stock . ' left' }}
                        </flux:badge>
                    </a>
                @empty
                    <flux:text class="text-gray-500 text-sm">All products are well stocked.</flux:text>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Top Selling Products -->
    <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-5">
        <flux:heading size="sm" class="mb-4">Top Selling Products</flux:heading>

        <flux:table>
            <flux:table.columns>
                <flux:table.column>Product</flux:table.column>
                <flux:table.column>Units Sold</flux:table.column>
                <flux:table.column>Revenue</flux:table.column>
            </flux:table.columns>
            <flux:table.rows>
                @forelse($this->topProducts as $product)
                    <flux:table.row :key="$product->product_id">
                        <flux:table.cell>{{ $product->product_name }}</flux:table.cell>
                        <flux:table.cell>{{ $product->total_qty }}</flux:table.cell>
                        <flux:table.cell>₹{{ number_format($product->total_revenue, 2) }}</flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="3" class="text-center">No sales data yet.</flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </div>
</div>

@script
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    (function () {
        let chartInstance = null;

        function renderChart() {
            const canvas = document.getElementById('revenue-trend-chart');
            if (!canvas || typeof Chart === 'undefined') return;

            const labels = JSON.parse(canvas.dataset.labels);
            const values = JSON.parse(canvas.dataset.values);

            if (chartInstance) {
                chartInstance.destroy();
            }

            chartInstance = new Chart(canvas.getContext('2d'), {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Revenue',
                        data: values,
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.1)',
                        tension: 0.35,
                        fill: true,
                        pointRadius: 3,
                    }],
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { callback: (value) => '₹' + value.toLocaleString('en-IN') },
                        },
                    },
                },
            });
        }

        renderChart();
        document.addEventListener('livewire:navigated', renderChart);
    })();
</script>
@endscript
