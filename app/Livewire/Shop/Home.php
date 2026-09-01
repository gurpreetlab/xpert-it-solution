<?php

namespace App\Livewire\Shop;

use App\Livewire\Shop\Concerns\HandlesProductCatalog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
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

    protected function applyActiveFilters(Builder $query): Builder
    {
        $query->where('is_active', true);

        if ($this->search !== '') {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('sku', 'like', '%' . $this->search . '%')
                  ->orWhere('short_description', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->selectedCategoryId !== '') {
            $query->where('category_id', (int) $this->selectedCategoryId);
        }

        if ($this->selectedBrandId !== '') {
            $query->where('brand_id', (int) $this->selectedBrandId);
        }

        return $query;
    }

    #[Computed]
    public function featuredProducts(): EloquentCollection
    {
        $query = Product::query()->with(['category:id,name,slug', 'brand:id,name,logo', 'primaryImage', 'specifications', 'reviews']);
        $this->applyActiveFilters($query);

        return $query->where('is_featured', true)->latest()->limit(8)->get();
    }

    #[Computed]
    public function trendingProducts(): EloquentCollection
    {
        $query = Product::query()->with(['category:id,name,slug', 'brand:id,name,logo', 'primaryImage', 'specifications', 'reviews']);
        $this->applyActiveFilters($query);

        return $query->latest('updated_at')->limit(8)->get();
    }

    #[Computed]
    public function bestSellers(): EloquentCollection
    {
        $query = Product::query()->with(['category:id,name,slug', 'brand:id,name,logo', 'primaryImage', 'specifications', 'reviews']);
        $this->applyActiveFilters($query);

        return $query->orderBy('sale_price', 'asc')->limit(8)->get();
    }

    #[Computed]
    public function dealsOfDay(): EloquentCollection
    {
        $query = Product::query()->with(['category:id,name,slug', 'brand:id,name,logo', 'primaryImage', 'specifications', 'reviews']);
        $this->applyActiveFilters($query);

        return $query->whereRaw('mrp > sale_price')->latest()->limit(8)->get();
    }

    #[Computed]
    public function activeCategoriesWithProducts(): EloquentCollection
    {
        return Category::query()
            ->whereHas('products', function ($q) {
                $q->where('is_active', true);
            })
            ->withCount(['products' => function ($q) {
                $q->where('is_active', true);
            }])
            ->orderBy('products_count', 'desc')
            ->get();
    }

    public function getCategoryProducts(string $categoryName, int $limit = 8): EloquentCollection
    {
        $query = Product::query()
            ->with(['category:id,name,slug', 'brand:id,name,logo', 'primaryImage', 'specifications', 'reviews']);

        $this->applyActiveFilters($query);

        return $query->whereHas('category', function ($q) use ($categoryName) {
            $q->where('name', 'like', '%' . $categoryName . '%');
        })->latest()->limit($limit)->get();
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
