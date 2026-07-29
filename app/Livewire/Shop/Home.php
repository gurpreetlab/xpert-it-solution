<?php

namespace App\Livewire\Shop;

use App\Livewire\Shop\Concerns\HandlesProductCatalog;
use App\Services\ShopCache;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout("layouts.blank")]
class Home extends Component
{
    use WithPagination, HandlesProductCatalog;

    #[Computed]
    public function featuredProducts(): Collection
    {
        return ShopCache::featuredProducts();
    }

    /**
     * Render the home page view.
     *
     * @return View
     */
    public function render(): View
    {
        return view("livewire.shop.home");
    }
}