<?php

namespace App\Livewire\Shop;

use App\Models\Order;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Orders extends Component
{
    use WithPagination;

    /**
     * @return LengthAwarePaginator<int, Order>
     */
    public function getOrdersProperty(): LengthAwarePaginator
    {
        return Auth::user()
            ->orders()
            ->withCount('items')
            ->latest()
            ->paginate(8);
    }

    #[Layout('layouts.blank')]
    public function render(): View
    {
        return view('livewire.shop.orders');
    }
}
