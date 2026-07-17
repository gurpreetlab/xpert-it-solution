<?php

namespace App\Livewire\Shop;

use App\Models\Product;
use App\Services\ShopCache;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout("layouts.blank")]
class Home extends Component
{
    use WithPagination;

    #[Url(as: "q")]
    public string $search = "";

    #[Url(as: "category")]
    public string $selectedCategoryId = "";

    #[Url(as: "brand")]
    public string $selectedBrandId = "";

    #[Url(as: "sort")]
    public string $sortBy = "featured";

    public ?int $selectedProductId = null;

    public bool $showProductModal = false;

    public function updating(string $property): void
    {
        if (
            in_array(
                $property,
                ["search", "selectedCategoryId", "selectedBrandId", "sortBy"],
                true,
            )
        ) {
            $this->resetPage();
        }
    }

    public function clearFilters(): void
    {
        $this->reset([
            "search",
            "selectedCategoryId",
            "selectedBrandId",
            "sortBy",
        ]);
        $this->resetPage();
    }

    public function selectProduct(int $id): void
    {
        $this->selectedProductId = $id;
        $this->showProductModal = true;
    }

    public function closeProductModal(): void
    {
        $this->selectedProductId = null;
        $this->showProductModal = false;
    }

    #[Computed]
    public function categories(): Collection
    {
        return ShopCache::categories();
    }

    #[Computed]
    public function brands(): Collection
    {
        return ShopCache::brands();
    }

    #[Computed]
    public function featuredProducts(): Collection
    {
        return ShopCache::featuredProducts();
    }

    #[Computed]
    public function totalProductsCount(): int
    {
        return ShopCache::totalProductsCount();
    }

    #[Computed]
    public function selectedProduct(): ?Product
    {
        if (!$this->selectedProductId) {
            return null;
        }

        return Product::query()
            ->with(["category", "brand", "images", "specifications"])
            ->find($this->selectedProductId);
    }

    #[Computed]
    public function products(): LengthAwarePaginator
    {
        if ($this->search !== "") {
            return $this->buildProductsQuery()->paginate(12);
        }

        $key = ShopCache::productsListKey(
            $this->selectedCategoryId,
            $this->selectedBrandId,
            $this->sortBy,
            $this->getPage(),
        );

        return ShopCache::rememberProducts(
            $key,
            fn() => $this->buildProductsQuery()->paginate(12),
        );
    }

    /**
     * Build the products query based on filters and search term.
     *
     * @return Builder
     */
    protected function buildProductsQuery(): Builder
    {
        return Product::query()
            ->with(["category:id,name", "brand:id,name,logo", "primaryImage"])
            ->where("is_active", true)
            ->when($this->search !== "", function ($query) {
                $query->where(function ($q) {
                    $q->where("name", "like", "%{$this->search}%")
                        ->orWhere(
                            "short_description",
                            "like",
                            "%{$this->search}%",
                        )
                        ->orWhere("description", "like", "%{$this->search}%")
                        ->orWhere("sku", "like", "%{$this->search}%");
                });
            })
            ->when(
                $this->selectedCategoryId !== "",
                fn($query) => $query->where(
                    "category_id",
                    $this->selectedCategoryId,
                ),
            )
            ->when(
                $this->selectedBrandId !== "",
                fn($query) => $query->where("brand_id", $this->selectedBrandId),
            )
            ->when(
                $this->sortBy === "price_asc",
                fn($query) => $query->orderBy("sale_price", "asc"),
            )
            ->when(
                $this->sortBy === "price_desc",
                fn($query) => $query->orderBy("sale_price", "desc"),
            )
            ->when(
                $this->sortBy === "newest",
                fn($query) => $query->orderBy("created_at", "desc"),
            )
            ->when(
                $this->sortBy === "featured",
                fn($query) => $query
                    ->orderBy("is_featured", "desc")
                    ->orderBy("name", "asc"),
            );
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
