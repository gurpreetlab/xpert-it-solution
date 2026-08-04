<?php

namespace App\Livewire\Shop;

use App\Livewire\Shop\Concerns\HandlesProductCatalog;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.blank')]
class Products extends Component
{
    use HandlesProductCatalog, WithPagination;

    public function render(): View
    {
        return view('livewire.shop.products');
    }
}
