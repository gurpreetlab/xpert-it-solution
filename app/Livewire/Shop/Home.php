<?php

namespace App\Livewire\Shop;

use App\Livewire\Shop\Concerns\HandlesProductCatalog;
use App\Models\Product;
use App\Services\ShopCache;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.blank')]
class Home extends Component
{
    use HandlesProductCatalog, WithPagination;

    /**
     * @return EloquentCollection<int, Product>
     */
    #[Computed]
    public function featuredProducts(): EloquentCollection
    {
        return ShopCache::featuredProducts();
    }

    /**
     * Render the home page view.
     */
    public function render(): View
    {
        return view('livewire.shop.home');
    }
}
