<?php

namespace App\Livewire\Shop;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

class About extends Component
{
    #[Layout('layouts.blank')]
    public function render(): View
    {
        return view('livewire.shop.about');
    }
}
