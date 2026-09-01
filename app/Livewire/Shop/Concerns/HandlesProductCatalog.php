<?php

namespace App\Livewire\Shop\Concerns;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Laravel\Scout\Builder as ScoutBuilder;
use Livewire\Attributes\Computed;

/**
 * @property-read Collection<int, Category> $categories
 * @property-read Collection<int, Brand> $brands
 */
trait HandlesProductCatalog
{
    public string $search = '';

    public string $selectedCategoryId = '';

    public string $selectedBrandId = '';

    public string $sortBy = 'featured';

    public string $priceRange = '';

    public bool $inStockOnly = false;

    public string $minRating = '';

    protected const array SORT_OPTIONS = [
        'featured' => 'Featured First',
        'price_asc' => 'Price: Low to High',
        'price_desc' => 'Price: High to Low',
        'newest' => 'Newest Additions',
    ];

    public function updating(string $property): void
    {
        if (in_array($property, ['search', 'selectedCategoryId', 'selectedBrandId', 'sortBy', 'priceRange', 'inStockOnly', 'minRating'], true)) {
            $this->resetPage();
        }

        if ($property === 'selectedCategoryId' && $this->selectedBrandId !== '') {
            $validBrands = $this->brands;
            if (! $validBrands->contains('id', (int) $this->selectedBrandId)) {
                $this->selectedBrandId = '';
            }
        }
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'selectedCategoryId', 'selectedBrandId', 'sortBy', 'priceRange', 'inStockOnly', 'minRating']);
        $this->resetPage();
    }

    #[Computed]
    public function hasActiveFilters(): bool
    {
        return $this->search !== ''
            || $this->selectedCategoryId !== ''
            || $this->selectedBrandId !== ''
            || $this->priceRange !== ''
            || $this->inStockOnly
            || $this->minRating !== ''
            || $this->sortBy !== 'featured';
    }

    #[Computed]
    public function sortOptions(): array
    {
        return self::SORT_OPTIONS;
    }

    #[Computed]
    public function selectedCategoryName(): ?string
    {
        return $this->selectedCategoryId === ''
            ? null
            : $this->categories->firstWhere('id', (int) $this->selectedCategoryId)?->name;
    }

    #[Computed]
    public function selectedBrandName(): ?string
    {
        return $this->selectedBrandId === ''
            ? null
            : $this->brands->firstWhere('id', (int) $this->selectedBrandId)?->name;
    }

    protected function isMeilisearchActive(): bool
    {
        return config('scout.driver') === 'meilisearch' && ! empty(config('scout.meilisearch.host'));
    }

    protected function getSearchBuilder(?string $categoryId = null, ?string $brandId = null): ScoutBuilder
    {
        $builder = Product::search($this->search);
        $builder->where('is_active', true);

        $catId = $categoryId ?? $this->selectedCategoryId;
        if ($catId !== '') {
            $builder->where('category_id', (int) $catId);
        }

        $bId = $brandId ?? $this->selectedBrandId;
        if ($bId !== '') {
            $builder->where('brand_id', (int) $bId);
        }

        return $builder;
    }

    #[Computed]
    public function categories(): Collection
    {
        if ($this->isMeilisearchActive()) {
            try {
                $rawResults = Product::search('', function ($meilisearch, $query, $options) {
                    $options['facets'] = ['category_id'];
                    $options['filter'] = 'is_active = true';

                    return $meilisearch->search($query, $options);
                })->raw();

                $distribution = $rawResults['facetDistribution']['category_id'] ?? [];
                $categoryIds = array_keys(array_filter($distribution, fn ($count) => $count > 0));

                return Category::whereIn('id', $categoryIds)
                    ->get()
                    ->each(function ($category) use ($distribution) {
                        $category->products_count = $distribution[$category->id] ?? 0;
                    });
            } catch (\Throwable $e) {
                Log::warning('Meilisearch category facet error, falling back to DB: ' . $e->getMessage());
            }
        }

        return Category::withCount(['products' => function ($query) {
            $query->where('is_active', true);
        }])->get();
    }

    #[Computed]
    public function brands(): Collection
    {
        $selectedCatId = $this->selectedCategoryId;
        $searchTerm = $this->search;

        if ($this->isMeilisearchActive()) {
            try {
                $rawResults = Product::search($searchTerm, function ($meilisearch, $query, $options) use ($selectedCatId) {
                    $filters = ['is_active = true'];

                    if ($selectedCatId !== '') {
                        $filters[] = "category_id = {$selectedCatId}";
                    }

                    $options['facets'] = ['brand_id'];
                    $options['filter'] = implode(' AND ', $filters);

                    return $meilisearch->search($query, $options);
                })->raw();

                $distribution = $rawResults['facetDistribution']['brand_id'] ?? [];
                $brandIds = array_keys(array_filter($distribution, fn ($count) => $count > 0));

                return Brand::whereIn('id', $brandIds)
                    ->get()
                    ->each(function ($brand) use ($distribution) {
                        $brand->products_count = $distribution[$brand->id] ?? 0;
                    });
            } catch (\Throwable $e) {
                Log::warning('Meilisearch brand facet error, falling back to DB: ' . $e->getMessage());
            }
        }

        $query = Brand::query()->whereHas('products', function ($q) use ($selectedCatId, $searchTerm) {
            $q->where('is_active', true);
            if ($selectedCatId !== '') {
                $q->where('category_id', (int) $selectedCatId);
            }
            if ($searchTerm !== '') {
                $q->where('name', 'like', '%' . $searchTerm . '%');
            }
        });

        return $query->withCount(['products' => function ($q) use ($selectedCatId) {
            $q->where('is_active', true);
            if ($selectedCatId !== '') {
                $q->where('category_id', (int) $selectedCatId);
            }
        }])->get();
    }

    #[Computed]
    public function totalProductsCount(): int
    {
        if ($this->isMeilisearchActive()) {
            try {
                return Product::search('')->where('is_active', true)->raw()['estimatedTotalHits'] ?? 0;
            } catch (\Throwable $e) {
                // fallback
            }
        }

        return Product::where('is_active', true)->count();
    }

    #[Computed]
    public function currentScopeProductsCount(): int
    {
        return $this->products()->total();
    }

    #[Computed]
    public function selectedCategoryProductsCount(): ?int
    {
        return $this->selectedCategoryId === ''
            ? null
            : $this->categories->firstWhere('id', (int) $this->selectedCategoryId)?->products_count;
    }

    /**
     * @return LengthAwarePaginator<int, Product>
     */
    #[Computed]
    public function products(): LengthAwarePaginator
    {
        $query = Product::query()->where('is_active', true);

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

        if ($this->inStockOnly) {
            $query->where('stock', '>', 0);
        }

        if ($this->priceRange !== '') {
            match ($this->priceRange) {
                'under_5000' => $query->where('sale_price', '<', 5000),
                '5000_15000' => $query->whereBetween('sale_price', [5000, 15000]),
                '15000_50000' => $query->whereBetween('sale_price', [15000, 50000]),
                'above_50000' => $query->where('sale_price', '>', 50000),
                default => null,
            };
        }

        match ($this->sortBy) {
            'price_asc' => $query->orderBy('sale_price', 'asc'),
            'price_desc' => $query->orderBy('sale_price', 'desc'),
            'newest' => $query->orderBy('created_at', 'desc'),
            'featured' => $query->orderBy('is_featured', 'desc')->orderBy('name', 'asc'),
            default => $query->orderBy('created_at', 'desc'),
        };

        return $query
            ->with(['category:id,name', 'brand:id,name,logo', 'primaryImage', 'specifications', 'reviews'])
            ->paginate(12);
    }

    public function toggleWishlist(int $productId): void
    {
        $added = \App\Support\WishlistManager::toggle($productId);

        if ($added) {
            \Flux\Flux::toast(text: 'Added to wishlist.', variant: 'success');
        } else {
            \Flux\Flux::toast(text: 'Removed from wishlist.', variant: 'success');
        }

        $this->dispatch('wishlist-updated');
    }

    public function toggleComparison(int $productId): void
    {
        \App\Livewire\Shop\Compare::toggleComparisonStatic($productId);
        $this->dispatch('compare-updated');
    }
}
