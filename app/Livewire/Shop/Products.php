<?php

namespace App\Livewire\Shop;

use App\Livewire\Shop\Concerns\HandlesProductCatalog;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.blank')]
class Products extends Component
{
    use WithPagination, HandlesProductCatalog;

    public function render()
    {
        return view('livewire.shop.products');
    }
}