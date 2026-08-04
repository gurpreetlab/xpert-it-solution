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
        $this->count = auth()->check()
            ? auth()->user()->cart?->items()->count() ?? 0
            : 0;
    }

    public function render(): View
    {
        return view('livewire.shop._partials.cart-count');
    }
}
