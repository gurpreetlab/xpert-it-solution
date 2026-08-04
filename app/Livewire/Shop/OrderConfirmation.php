<?php

namespace App\Livewire\Shop;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class OrderConfirmation extends Component
{
    public Order $order;

    public function mount(Order $order): void
    {
        abort_unless($order->user_id === Auth::id(), 403);

        $this->order = $order->load('items.product', 'address');
    }

    #[Layout('layouts.blank')]
    public function render()
    {
        return view('livewire.shop.order-confirmation');
    }
}
