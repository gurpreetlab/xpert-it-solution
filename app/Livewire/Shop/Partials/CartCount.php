<?php

namespace App\Livewire\Shop\Partials;

use Livewire\Attributes\On;
use Livewire\Component;

class CartCount extends Component
{
    public int $count = 0;

    public function mount()
    {
        $this->refreshCount();
    }

    #[On('cart-updated')]
    public function refreshCount()
    {
        $this->count = auth()->check()
            ? auth()->user()->cart?->items()->count() ?? 0
            : 0;
    }

    public function render()
    {
        return view('livewire.shop._partials.cart-count');
    }
}
