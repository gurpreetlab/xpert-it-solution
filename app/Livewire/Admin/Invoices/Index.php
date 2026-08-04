<?php

namespace App\Livewire\Admin\Invoices;

use App\Models\Invoice;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public function updating(string $property): void
    {
        if (in_array($property, ['search'], true)) {
            $this->resetPage();
        }
    }

    public function clearFilters(): void
    {
        $this->reset(['search']);
        $this->resetPage();
    }

    #[Computed]
    public function invoices(): LengthAwarePaginator
    {
        return Invoice::query()
            ->with(['order.user:id,name,email'])
            ->when($this->search !== '', function ($query) {
                $query->where(function ($q) {
                    $q->where(
                        'invoice_number',
                        'like',
                        "%{$this->search}%",
                    )->orWhereHas('order', function ($oq) {
                        $oq->where(
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
                });
            })
            ->latest('invoice_date')
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.admin.invoices.index', [
            'invoices' => $this->invoices,
        ]);
    }
}
