@php
    $stats = $this->stats;
    $trend = $this->revenueTrend;
    $volume = $this->orderVolumeTrend;
    $catSales = $this->categorySales;

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

<div class="flex h-full w-full flex-1 flex-col gap-6 p-6 sm:p-8">

    <div>
        <flux:heading size="xl" level="1">Super Admin Dashboard</flux:heading>
        <flux:text class="mt-1 text-zinc-500">Real-time business intelligence, customer engagement, and inventory health metrics.</flux:text>
    </div>

    <!-- Stat Cards Grid -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">

        <!-- Total Revenue Card -->
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5 shadow-sm">
            <flux:text class="text-zinc-500 text-[10px] uppercase tracking-wider font-semibold">Total Revenue</flux:text>
            <div class="mt-2 text-xl font-extrabold text-zinc-900 dark:text-white">₹{{ number_format($stats['total_revenue'], 2) }}</div>
            <div class="mt-1 flex items-center gap-1 text-[10px]">
                <flux:icon icon="{{ $stats['revenue_growth'] >= 0 ? 'arrow-trending-up' : 'arrow-trending-down' }}" class="size-3.5 {{ $stats['revenue_growth'] >= 0 ? 'text-emerald-500' : 'text-rose-500' }}" />
                <span class="{{ $stats['revenue_growth'] >= 0 ? 'text-emerald-500' : 'text-rose-500' }} font-bold">{{ abs($stats['revenue_growth']) }}%</span>
                <span class="text-zinc-500">vs last month</span>
            </div>
        </div>

        <!-- Orders This Month Card -->
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5 shadow-sm">
            <flux:text class="text-zinc-500 text-[10px] uppercase tracking-wider font-semibold">Orders This Month</flux:text>
            <div class="mt-2 text-xl font-extrabold text-zinc-900 dark:text-white">{{ $stats['orders_this_month'] }}</div>
            <div class="mt-1 flex items-center gap-1 text-[10px]">
                <flux:icon icon="{{ $stats['order_growth'] >= 0 ? 'arrow-trending-up' : 'arrow-trending-down' }}" class="size-3.5 {{ $stats['order_growth'] >= 0 ? 'text-emerald-500' : 'text-rose-500' }}" />
                <span class="{{ $stats['order_growth'] >= 0 ? 'text-emerald-500' : 'text-rose-500' }} font-bold">{{ abs($stats['order_growth']) }}%</span>
                <span class="text-zinc-500">vs last month</span>
            </div>
        </div>

        <!-- Pending Orders Card -->
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5 shadow-sm">
            <flux:text class="text-zinc-500 text-[10px] uppercase tracking-wider font-semibold">Pending Orders</flux:text>
            <div class="mt-2 text-xl font-extrabold text-zinc-900 dark:text-white">{{ $stats['pending_orders'] }}</div>
            <div class="mt-1 text-[10px] text-zinc-500">
                @if($stats['failed_payments'] > 0)
                    <span class="text-rose-500 font-bold">{{ $stats['failed_payments'] }}</span> failed payments
                @else
                    No failed payments
                @endif
            </div>
        </div>

        <!-- Low Stock Card -->
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5 shadow-sm">
            <flux:text class="text-zinc-500 text-[10px] uppercase tracking-wider font-semibold">Low Stock Alerts</flux:text>
            <div class="mt-2 text-xl font-extrabold {{ $stats['low_stock_count'] > 0 ? 'text-amber-500' : 'text-zinc-900 dark:text-white' }}">{{ $stats['low_stock_count'] }}</div>
            <div class="mt-1 text-[10px] text-zinc-500">out of {{ $stats['total_products'] }} products</div>
        </div>

        <!-- Registered Users Card -->
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5 shadow-sm">
            <flux:text class="text-zinc-500 text-[10px] uppercase tracking-wider font-semibold">Registered Users</flux:text>
            <div class="mt-2 text-xl font-extrabold text-zinc-900 dark:text-white">{{ $stats['total_users'] }}</div>
            <div class="mt-1 flex items-center gap-1 text-[10px]">
                <flux:icon icon="{{ $stats['user_growth'] >= 0 ? 'arrow-trending-up' : 'arrow-trending-down' }}" class="size-3.5 {{ $stats['user_growth'] >= 0 ? 'text-emerald-500' : 'text-rose-500' }}" />
                <span class="{{ $stats['user_growth'] >= 0 ? 'text-emerald-500' : 'text-rose-500' }} font-bold">{{ abs($stats['user_growth']) }}%</span>
                <span class="text-zinc-500">vs last month</span>
            </div>
        </div>

        <!-- Contact Inquiries Card -->
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5 shadow-sm">
            <flux:text class="text-zinc-500 text-[10px] uppercase tracking-wider font-semibold">Contact Inquiries</flux:text>
            <div class="mt-2 text-xl font-extrabold text-zinc-900 dark:text-white">{{ $stats['total_inquiries'] }}</div>
            <div class="mt-1 text-[10px] text-zinc-500">received from contact forms</div>
        </div>

    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Revenue Trend -->
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5 shadow-sm">
            <flux:heading size="sm">Revenue Trend</flux:heading>
            <div class="mt-4" wire:ignore>
                <canvas id="revenue-trend-chart" height="180"
                    data-labels="{{ json_encode($trend['labels']) }}"
                    data-values="{{ json_encode($trend['data']) }}"
                ></canvas>
            </div>
        </div>

        <!-- Monthly Order Volume (Bar Chart) -->
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5 shadow-sm">
            <flux:heading size="sm">Order Volume</flux:heading>
            <div class="mt-4" wire:ignore>
                <canvas id="order-volume-chart" height="180"
                    data-labels="{{ json_encode($volume['labels']) }}"
                    data-values="{{ json_encode($volume['data']) }}"
                ></canvas>
            </div>
        </div>

        <!-- Sales by Category (Doughnut Chart) -->
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5 shadow-sm">
            <flux:heading size="sm">Sales by Category</flux:heading>
            <div class="mt-4" wire:ignore>
                <canvas id="category-sales-chart" height="180"
                    data-labels="{{ json_encode($catSales['labels']) }}"
                    data-values="{{ json_encode($catSales['data']) }}"
                ></canvas>
            </div>
        </div>

    </div>

    <!-- BI Products Insights & Orders Status Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Top Selling Products -->
        <div class="lg:col-span-2 rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5 shadow-sm">
            <flux:heading size="sm" class="mb-4">Top Selling Products (Revenue Generating)</flux:heading>
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Product</flux:table.column>
                    <flux:table.column>Units Sold</flux:table.column>
                    <flux:table.column>Revenue Generated</flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @forelse($this->topProducts as $product)
                        <flux:table.row :key="'selling-'.$product->product_id">
                            <flux:table.cell class="font-medium text-zinc-900 dark:text-white">{{ $product->product_name }}</flux:table.cell>
                            <flux:table.cell>{{ $product->total_qty }}</flux:table.cell>
                            <flux:table.cell class="font-semibold text-zinc-950 dark:text-zinc-100">₹{{ number_format($product->total_revenue, 2) }}</flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="3" class="text-center text-zinc-500">No sales data yet.</flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </div>

        <!-- Orders by Status -->
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5 shadow-sm">
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
                            <span class="text-zinc-500">{{ $count }} orders</span>
                        </div>
                        <div class="h-1.5 rounded-full bg-zinc-100 dark:bg-zinc-800 overflow-hidden">
                            <div class="h-full rounded-full {{ $statusBarClasses[$status] ?? 'bg-zinc-500' }}" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>
                @empty
                    <flux:text class="text-zinc-500 text-sm">No orders yet.</flux:text>
                @endforelse
            </div>
        </div>

    </div>

    <!-- Active Feeds Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Recent Orders Feed -->
        <div class="lg:col-span-2 rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <flux:heading size="sm">Recent Orders</flux:heading>
                <flux:button href="{{ route('dashboard.orders.index') }}" wire:navigate variant="ghost" size="sm">View All</flux:button>
            </div>

            <div class="space-y-1">
                @forelse($this->recentOrders as $order)
                    <a href="{{ route('dashboard.orders.show', $order) }}" wire:navigate class="flex items-center justify-between gap-3 py-2.5 px-2 -mx-2 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition">
                        <div class="min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="font-mono text-sm font-semibold">{{ $order->order_number }}</span>
                                <flux:badge size="sm" color="{{ $statusColors[$order->status] ?? 'zinc' }}" class="capitalize">{{ $order->status }}</flux:badge>
                            </div>
                            <div class="text-xs text-zinc-500 truncate">{{ $order->user?->name ?? 'Deleted user' }} &middot; {{ $order->created_at->diffForHumans() }}</div>
                        </div>
                        <span class="text-sm font-semibold shrink-0">₹{{ number_format($order->total, 2) }}</span>
                    </a>
                @empty
                    <flux:text class="text-zinc-500 text-sm">No orders placed yet.</flux:text>
                @endforelse
            </div>
        </div>

        <!-- Recent Contact Inquiries Feed -->
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <flux:heading size="sm">Recent Inquiries</flux:heading>
                <flux:button href="{{ route('dashboard.contact-messages.index') }}" wire:navigate variant="ghost" size="sm">View Inbox</flux:button>
            </div>

            <div class="space-y-3">
                @forelse($this->recentInquiries as $msg)
                    <a href="{{ route('dashboard.contact-messages.index') }}" wire:navigate class="block p-3 rounded-lg border border-zinc-100 dark:border-zinc-800 hover:border-blue-500 transition">
                        <div class="flex items-center justify-between text-xs text-zinc-500 mb-1">
                            <span class="font-semibold text-zinc-800 dark:text-zinc-200">{{ $msg->name }}</span>
                            <span>{{ $msg->created_at->diffForHumans() }}</span>
                        </div>
                        <h4 class="text-xs font-bold text-zinc-900 dark:text-white truncate">{{ $msg->subject }}</h4>
                        <p class="text-[11px] text-zinc-500 truncate mt-1">{{ $msg->message }}</p>
                    </a>
                @empty
                    <flux:text class="text-zinc-500 text-sm">No inquiries in inbox.</flux:text>
                @endforelse
            </div>
        </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Top Wishlisted Products (Business Intelligence) -->
        <div class="lg:col-span-2 rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5 shadow-sm">
            <flux:heading size="sm" class="mb-4">Top Wishlisted Products (High Purchase Intent)</flux:heading>
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Product</flux:table.column>
                    <flux:table.column>Times Wishlisted</flux:table.column>
                    <flux:table.column>BI Action Insight</flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @forelse($this->topWishlisted as $product)
                        <flux:table.row :key="'wishlisted-'.$product->product_id">
                            <flux:table.cell class="font-medium text-zinc-900 dark:text-white">
                                <a href="{{ route('dashboard.products.show', $product->product_id) }}" wire:navigate class="hover:underline hover:text-blue-500">
                                    {{ $product->product_name }}
                                </a>
                            </flux:table.cell>
                            <flux:table.cell>
                                <div class="flex items-center gap-1">
                                    <flux:icon icon="heart" class="size-4 text-rose-500 fill-current" />
                                    <span>{{ $product->wishlist_count }} buyer(s)</span>
                                </div>
                            </flux:table.cell>
                            <flux:table.cell>
                                <flux:badge size="sm" color="amber">High Demand</flux:badge>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="3" class="text-center text-zinc-500">No wishlisted products yet.</flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </div>

        <!-- Inventory / Low Stock Alert Card -->
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <flux:heading size="sm">Low Stock Alerts</flux:heading>
                    <flux:icon icon="exclamation-triangle" class="size-4 text-amber-500" />
                </div>

                <div class="space-y-3">
                    @forelse($this->lowStockProducts as $product)
                        <a href="{{ route('dashboard.products.show', $product) }}" wire:navigate class="flex items-center justify-between gap-2 p-2 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-800 transition">
                            <div class="min-w-0">
                                <div class="text-xs font-semibold truncate text-zinc-900 dark:text-white">{{ $product->name }}</div>
                                <div class="text-[10px] text-zinc-500">{{ $product->sku ?? '-' }}</div>
                            </div>
                            <flux:badge size="sm" color="{{ $product->stock == 0 ? 'red' : 'amber' }}">
                                {{ $product->stock == 0 ? 'Out of stock' : $product->stock . ' left' }}
                            </flux:badge>
                        </a>
                    @empty
                        <div class="py-4 text-center text-zinc-500 text-sm">All products are well stocked.</div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

</div>

@script
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    (function () {
        let revenueChart = null;
        let volumeChart = null;
        let categoryChart = null;

        function renderCharts() {
            // 1. Revenue Chart
            const rCanvas = document.getElementById('revenue-trend-chart');
            if (rCanvas && typeof Chart !== 'undefined') {
                const rLabels = JSON.parse(rCanvas.dataset.labels);
                const rValues = JSON.parse(rCanvas.dataset.values);

                if (revenueChart) {
                    revenueChart.destroy();
                }

                revenueChart = new Chart(rCanvas.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: rLabels,
                        datasets: [{
                            label: 'Revenue',
                            data: rValues,
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
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

            // 2. Order Volume Chart
            const vCanvas = document.getElementById('order-volume-chart');
            if (vCanvas && typeof Chart !== 'undefined') {
                const vLabels = JSON.parse(vCanvas.dataset.labels);
                const vValues = JSON.parse(vCanvas.dataset.values);

                if (volumeChart) {
                    volumeChart.destroy();
                }

                volumeChart = new Chart(vCanvas.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: vLabels,
                        datasets: [{
                            label: 'Order Volume',
                            data: vValues,
                            backgroundColor: '#8b5cf6',
                            borderRadius: 4,
                        }],
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { stepSize: 1 },
                            },
                        },
                    },
                });
            }

            // 3. Category Sales Chart
            const cCanvas = document.getElementById('category-sales-chart');
            if (cCanvas && typeof Chart !== 'undefined') {
                const cLabels = JSON.parse(cCanvas.dataset.labels);
                const cValues = JSON.parse(cCanvas.dataset.values);

                if (categoryChart) {
                    categoryChart.destroy();
                }

                categoryChart = new Chart(cCanvas.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: cLabels.length > 0 ? cLabels : ['No Sales yet'],
                        datasets: [{
                            data: cValues.length > 0 ? cValues : [1],
                            backgroundColor: [
                                '#3b82f6', '#10b981', '#f59e0b', '#ec4899', '#8b5cf6', '#06b6d4'
                            ],
                            borderWidth: 0,
                        }],
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: { boxWidth: 10, padding: 10 },
                            },
                        },
                    },
                });
            }
        }

        renderCharts();
        document.addEventListener('livewire:navigated', renderCharts);
    })();
</script>
@endscript
