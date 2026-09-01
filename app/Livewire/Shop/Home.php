<?php

namespace App\Livewire\Shop;

use App\Livewire\Shop\Concerns\HandlesProductCatalog;
use App\Models\Brand;
use App\Models\Category;
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

    #[Computed]
    public function featuredProducts(): EloquentCollection
    {
        return Product::query()
            ->with(['category:id,name,slug', 'brand:id,name,logo', 'primaryImage', 'specifications', 'reviews'])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->latest()
            ->limit(8)
            ->get();
    }

    #[Computed]
    public function trendingProducts(): EloquentCollection
    {
        return Product::query()
            ->with(['category:id,name,slug', 'brand:id,name,logo', 'primaryImage', 'specifications', 'reviews'])
            ->where('is_active', true)
            ->latest('updated_at')
            ->limit(8)
            ->get();
    }

    #[Computed]
    public function bestSellers(): EloquentCollection
    {
        return Product::query()
            ->with(['category:id,name,slug', 'brand:id,name,logo', 'primaryImage', 'specifications', 'reviews'])
            ->where('is_active', true)
            ->orderBy('sale_price', 'asc')
            ->limit(8)
            ->get();
    }

    #[Computed]
    public function dealsOfDay(): EloquentCollection
    {
        return Product::query()
            ->with(['category:id,name,slug', 'brand:id,name,logo', 'primaryImage', 'specifications', 'reviews'])
            ->where('is_active', true)
            ->whereRaw('mrp > sale_price')
            ->latest()
            ->limit(8)
            ->get();
    }

    /**
     * Get products by major category name
     */
    public function getCategoryProducts(string $categoryName, int $limit = 6): EloquentCollection
    {
        return Product::query()
            ->with(['category:id,name,slug', 'brand:id,name,logo', 'primaryImage', 'specifications', 'reviews'])
            ->where('is_active', true)
            ->whereHas('category', function ($q) use ($categoryName) {
                $q->where('name', 'like', '%' . $categoryName . '%');
            })
            ->latest()
            ->limit($limit)
            ->get();
    }

    #[Computed]
    public function popularBrands(): EloquentCollection
    {
        return Brand::query()
            ->whereHas('products', function ($q) {
                $q->where('is_active', true);
            })
            ->withCount(['products' => function ($q) {
                $q->where('is_active', true);
            }])
            ->orderBy('products_count', 'desc')
            ->limit(10)
            ->get();
    }

    public function render(): View
    {
        return view('livewire.shop.home');
    }
}
