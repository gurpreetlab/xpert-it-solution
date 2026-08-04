<?php

namespace App\Livewire\Admin\Orders;

use App\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public string $status = '';

    public string $paymentStatus = '';

    /**
     * Reset to page 1 whenever a filter changes, so the user
     * doesn't land on an empty page 4 of a 1-page result set.
     */
    public function updating(string $property): void
    {
        if (in_array($property, ['search', 'status', 'paymentStatus'], true)) {
            $this->resetPage();
        }
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'status', 'paymentStatus']);
        $this->resetPage();
    }

    #[Computed]
    public function orders(): LengthAwarePaginator
    {
        return Order::query()
            ->with(['user:id,name,email'])
            ->withCount('items')
            ->when($this->search !== '', function ($query) {
                $query->where(function ($q) {
                    $q->where(
                        'order_number',
                        'like',
                        "%{$this->search}%",
                    )->orWhereHas('user', function ($uq) {
                        $uq->where(
                            'name',
                            'like',
                            "%{$this->search}%",
                        )->orWhere('email', 'like', "%{$this->search}%");
                    });
                });
            })
            ->when(
                $this->status !== '',
                fn ($query) => $query->where('status', $this->status),
            )
            ->when(
                $this->paymentStatus !== '',
                fn ($query) => $query->where(
                    'payment_status',
                    $this->paymentStatus,
                ),
            )
            ->latest()
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.admin.orders.index', [
            'orders' => $this->orders(),
        ]);
    }
}
