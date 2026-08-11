<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Dashboard extends Component
{
    protected int $lowStockThreshold = 10;

    /**
     * @return array<string, int|float>
     */
    #[Computed]
    public function stats(): array
    {
        $totalRevenue = (float) Order::where('payment_status', 'paid')->sum('total');

        $thisMonthRevenue = Order::where('payment_status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');

        $lastMonthRevenue = Order::where('payment_status', 'paid')
            ->whereMonth('created_at', now()->subMonthNoOverflow()->month)
            ->whereYear('created_at', now()->subMonthNoOverflow()->year)
            ->sum('total');

        $revenueGrowth =
            $lastMonthRevenue > 0
                ? round(
                    (($thisMonthRevenue - $lastMonthRevenue) /
                        $lastMonthRevenue) *
                        100,
                    1,
                )
                : ($thisMonthRevenue > 0
                    ? 100.0
                    : 0.0);

        $ordersThisMonth = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $ordersLastMonth = Order::whereMonth(
            'created_at',
            now()->subMonthNoOverflow()->month,
        )
            ->whereYear('created_at', now()->subMonthNoOverflow()->year)
            ->count();

        $orderGrowth =
            $ordersLastMonth > 0
                ? round(
                    (($ordersThisMonth - $ordersLastMonth) / $ordersLastMonth) *
                        100,
                    1,
                )
                : ($ordersThisMonth > 0
                    ? 100.0
                    : 0.0);

        $totalUsers = \App\Models\User::count();
        $usersThisMonth = \App\Models\User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $usersLastMonth = \App\Models\User::whereMonth('created_at', now()->subMonthNoOverflow()->month)
            ->whereYear('created_at', now()->subMonthNoOverflow()->year)
            ->count();
        $userGrowth = $usersLastMonth > 0
            ? round((($usersThisMonth - $usersLastMonth) / $usersLastMonth) * 100, 1)
            : ($usersThisMonth > 0 ? 100.0 : 0.0);

        return [
            'total_revenue' => $totalRevenue,
            'revenue_growth' => $revenueGrowth,
            'orders_this_month' => $ordersThisMonth,
            'order_growth' => $orderGrowth,
            'pending_orders' => Order::whereIn('status', [
                'pending',
                'processing',
            ])->count(),
            'failed_payments' => Order::where(
                'payment_status',
                'failed',
            )->count(),
            'total_products' => Product::count(),
            'low_stock_count' => Product::where('is_active', true)
                ->where('stock', '<=', $this->lowStockThreshold)
                ->count(),
            'total_users' => $totalUsers,
            'user_growth' => $userGrowth,
            'total_inquiries' => \App\Models\ContactMessage::count(),
        ];
    }

    /**
     * Get most frequently wishlisted products.
     */
    #[Computed]
    public function topWishlisted(): Collection
    {
        return \Illuminate\Support\Facades\DB::table('wishlist_items')
            ->selectRaw('product_id, products.name as product_name, COUNT(*) as wishlist_count')
            ->join('products', 'products.id', '=', 'wishlist_items.product_id')
            ->groupBy('product_id', 'products.name')
            ->orderByDesc('wishlist_count')
            ->limit(5)
            ->get();
    }

    /**
     * Get the recent contact inquiries.
     */
    #[Computed]
    public function recentInquiries(): Collection
    {
        return \App\Models\ContactMessage::latest()->limit(5)->get();
    }

    /**
     * Paid revenue for the trailing 6 months, oldest first, for the trend chart.
     */
    /**
     * @return array{labels: array<int, string>, data: array<int, float>}
     */
    #[Computed]
    public function revenueTrend(): array
    {
        $labels = [];
        $data = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonthsNoOverflow($i);

            $labels[] = $month->format('M Y');

            $data[] = (float) Order::where('payment_status', 'paid')
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->sum('total');
        }

        return ['labels' => $labels, 'data' => $data];
    }

    /** @return Collection<string, int> */
    #[Computed]
    public function ordersByStatus(): Collection
    {
        return Order::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, Order>
     */
    #[Computed]
    public function recentOrders(): Collection
    {
        return Order::with('user:id,name,email')->latest()->limit(8)->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, OrderItem>
     */
    #[Computed]
    public function topProducts(): Collection
    {
        return OrderItem::selectRaw(
            'product_id, product_name, SUM(quantity) as total_qty, SUM(unit_price * quantity) as total_revenue',
        )
            ->whereHas('order', fn ($q) => $q->where('payment_status', 'paid'))
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, Product>
     */
    #[Computed]
    public function lowStockProducts(): Collection
    {
        return Product::where('is_active', true)
            ->where('stock', '<=', $this->lowStockThreshold)
            ->orderBy('stock')
            ->limit(6)
            ->get(['id', 'name', 'sku', 'stock']);
    }

    public function render(): View
    {
        return view('livewire.admin.dashboard');
    }
}
