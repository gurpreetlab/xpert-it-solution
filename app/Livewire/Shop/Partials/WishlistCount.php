<?php

namespace App\Livewire\Shop\Partials;

use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class WishlistCount extends Component
{
    public int $count = 0;

    public function mount(): void
    {
        $this->refreshCount();
    }

    #[On('wishlist-updated')]
    public function refreshCount(): void
    {
        $this->count = \App\Support\WishlistManager::count();
    }

    public function render(): View
    {
        return view('livewire.shop._partials.wishlist-count');
    }
}
