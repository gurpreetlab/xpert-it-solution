<?php

namespace App\Livewire\Shop\Partials;

use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class CartCount extends Component
{
    public int $count = 0;

    public function mount(): void
    {
        $this->refreshCount();
    }

    #[On('cart-updated')]
    public function refreshCount(): void
    {
        $this->count = \App\Support\CartManager::count();
    }

    public function render(): View
    {
        return view('livewire.shop._partials.cart-count');
    }
}
