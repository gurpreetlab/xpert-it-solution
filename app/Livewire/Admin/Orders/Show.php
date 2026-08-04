<?php

namespace App\Livewire\Admin\Orders;

use App\Models\Order;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Show extends Component
{
    public Order $order;

    public string $newStatus = '';

    public function mount(Order $order): void
    {
        $this->order = $order->load(['user', 'address', 'items.product']);
        $this->newStatus = $order->status;
    }

    public function updateStatus(): void
    {
        $this->validate([
            'newStatus' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $this->order->update(['status' => $this->newStatus]);

        Flux::toast('Order status updated successfully!');
    }

    public function render(): View
    {
        return view('livewire.admin.orders.show');
    }
}
