<?php

namespace App\Livewire\Shop\Concerns;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Laravel\Scout\Builder as ScoutBuilder;
use Livewire\Attributes\Computed;

trait HandlesProductCatalog
{
    public string $search = '';

    public string $selectedCategoryId = '';

    public string $selectedBrandId = '';

    public string $sortBy = 'featured';

    protected const array SORT_OPTIONS = [
        'featured' => 'Featured First',
        'price_asc' => 'Price: Low to High',
        'price_desc' => 'Price: High to Low',
        'newest' => 'Newest Additions',
    ];

    public function updating(string $property): void
    {
        if (in_array($property, ['search', 'selectedCategoryId', 'selectedBrandId', 'sortBy'], true)) {
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
        $this->reset(['search', 'selectedCategoryId', 'selectedBrandId', 'sortBy']);
        $this->resetPage();
    }

    #[Computed]
    public function hasActiveFilters(): bool
    {
        return $this->search !== ''
            || $this->selectedCategoryId !== ''
            || $this->selectedBrandId !== ''
            || $this->sortBy !== 'featured';
    }

    /**
     * Get the available sorting options.
     *
     * @return array<string, string>
     */
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

    /**
     * DRY helper to construct base Scout builder queries with active state and category/brand filters.
     */
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

    /**
     * Fetch categories from Meilisearch with dynamic product counts using facet distribution (cached for blazing performance).
     */
    #[Computed]
    public function categories(): Collection
    {
        $rawResults = Product::search('', function ($meilisearch, $query, $options) {
            $options['facets'] = ['category_id'];
            $options['filter'] = 'is_active = true';

            return $meilisearch->search($query, $options);
        })->raw();

        $distribution = $rawResults['facetDistribution']['category_id'] ?? [];
        $categoryIds = array_keys(array_filter($distribution, fn ($count) => $count > 0));

        return Category::whereIn('id', $categoryIds)
            ->get()
            ->map(function ($category) use ($distribution) {
                $category->products_count = $distribution[$category->id] ?? 0;

                return $category;
            });
    }

    /**
     * Fetch brands dynamically from Meilisearch based on active category/search filters, with counts.
     */
    #[Computed]
    public function brands(): Collection
    {
        $selectedCatId = $this->selectedCategoryId;
        $searchTerm = $this->search;

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
            ->map(function ($brand) use ($distribution) {
                $brand->products_count = $distribution[$brand->id] ?? 0;

                return $brand;
            });
    }

    #[Computed]
    public function totalProductsCount(): int
    {
        return Product::search('')->where('is_active', true)->raw()['estimatedTotalHits'] ?? 0;
    }

    #[Computed]
    // show selectedCategoryId products_count
    public function currentScopeProductsCount(): int
    {
        return $this->getSearchBuilder()->raw()['estimatedTotalHits'] ?? 0;
    }

    #[Computed]
    public function selectedCategoryProductsCount(): ?int
    {
        return $this->selectedCategoryId === ''
            ? null
            : $this->categories->firstWhere('id', (int) $this->selectedCategoryId)?->products_count;
    }

    #[Computed]
    public function products(): LengthAwarePaginator
    {
        $builder = $this->getSearchBuilder();

        match ($this->sortBy) {
            'price_asc' => $builder->orderBy('sale_price', 'asc'),
            'price_desc' => $builder->orderBy('sale_price', 'desc'),
            'newest' => $builder->orderBy('created_at', 'desc'),
            'featured' => $builder->orderBy('is_featured', 'desc')->orderBy('name', 'asc'),
            default => null,
        };

        return $builder
            ->query(fn ($query) => $query->with(['category:id,name', 'brand:id,name,logo', 'primaryImage']))
            ->paginate(12);
    }
}
