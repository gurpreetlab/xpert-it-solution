<?php

namespace App\Livewire\Admin\Products;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Flux\Flux;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public string $categoryId = '';

    public string $brandId = '';

    public string $status = '';

    public ?Product $deletingProduct = null;

    /**
     * Reset to page 1 whenever a filter changes, so the user
     * doesn't land on an empty page 4 of a 1-page result set.
     */
    public function updating(string $property): void
    {
        if (in_array($property, ['search', 'categoryId', 'brandId', 'status', 'perPage'], true)) {
            $this->resetPage();
        }
    }

    public function confirmDelete(Product $product): void
    {
        $this->resetValidation();
        $this->deletingProduct = $product;
    }

    public function delete(): void
    {
        if (! $this->deletingProduct) {
            return;
        }

        $this->deletingProduct->delete();
        $this->reset('deletingProduct');
        $this->dispatch('product-deleted');
        Flux::toast('Product deleted successfully!');
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'categoryId', 'brandId', 'status']);
        $this->resetPage();
    }

    /** @return Collection<int, Category> */
    #[Computed]
    public function categories(): Collection
    {
        return Category::orderBy('name')->get(['id', 'name']);
    }

    /** @return Collection<int, Brand> */
    #[Computed]
    public function brands(): Collection
    {
        return Brand::orderBy('name')->get(['id', 'name']);
    }

    /** @return LengthAwarePaginator<int, Product> */
    #[Computed]
    public function products(): LengthAwarePaginator
    {
        return Product::query()
            ->with(['category:id,name', 'brand:id,name', 'primaryImage'])
            ->when($this->search !== '', function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                        ->orWhere('sku', 'like', "%{$this->search}%")
                        ->orWhere('hsn_code', 'like', "%{$this->search}%");
                });
            })
            ->when($this->categoryId !== '', fn ($query) => $query->where('category_id', $this->categoryId))
            ->when($this->brandId !== '', fn ($query) => $query->where('brand_id', $this->brandId))
            ->when($this->status !== '', fn ($query) => $query->where('is_active', $this->status === 'active'))
            ->paginate(10);
    }

    public function render(): View
    {
        return view('livewire.admin.products.index', [
            'products' => $this->products(),
        ]);
    }
}
