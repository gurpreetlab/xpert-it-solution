<?php

namespace App\Livewire\Shop;

use Livewire\Attributes\Layout;
use Livewire\Component;

class About extends Component
{
    #[Layout('layouts.blank')]
    public function render()
    {
        return view('livewire.shop.about');
    }
}
