<?php

namespace App\Livewire\Shop;

use App\Livewire\Shop\Concerns\HandlesProductCatalog;
use App\Models\Product;
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
        return Product::query()
            ->select([
                'id',
                'category_id',
                'brand_id',
                'name',
                'slug',
                'sale_price',
                'mrp',
                'stock',
                'is_featured',
                'sku',
                'short_description',
            ])
            ->with([
                'category:id,name,slug',
                'brand:id,name,logo',
                'primaryImage',
            ])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->latest()
            ->limit(6)
            ->get();
    }

    /**
     * Render the home page view.
     */
    public function render(): View
    {
        // return view('livewire.shop.home');
        return view('shop.home');
    }
}
