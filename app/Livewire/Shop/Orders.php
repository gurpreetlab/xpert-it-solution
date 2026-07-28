<?php

namespace App\Livewire\Shop;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Orders extends Component
{
    use WithPagination;

    public function getOrdersProperty(): LengthAwarePaginator
    {
        return Auth::user()
            ->orders()
            ->withCount("items")
            ->latest()
            ->paginate(8);
    }

    #[Layout("layouts.blank")]
    public function render()
    {
        return view("livewire.shop.orders");
    }
}
