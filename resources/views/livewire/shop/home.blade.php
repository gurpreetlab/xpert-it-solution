<main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">

    <x-shop.hero :search="$search" />

    <x-shop.category-grid
        :categories="$this->categories"
        :selected-category-id="$selectedCategoryId"
    />

    <x-shop.featured-products :products="$this->featuredProducts" />

    <section id="products" class="mb-16 scroll-mt-20">
        <div class="border-t border-zinc-200 dark:border-zinc-800 pt-12 mb-8">
            <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-zinc-900 dark:text-white">Explore Product Catalog</h2>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Live search, filter by brand, category and sort according to your preference.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <div class="lg:col-span-1 space-y-6">
                <x-shop.sidebar-filters
                    :categories="$this->categories"
                    :brands="$this->brands"
                    :selected-category-id="$selectedCategoryId"
                    :selected-brand-id="$selectedBrandId"
                    :search="$search"
                    :total-products-count="$this->totalProductsCount"
                />
            </div>

            <div class="lg:col-span-3 space-y-6">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 p-4 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
                    <div class="text-sm font-medium text-zinc-600 dark:text-zinc-400">
                        Showing <span class="text-zinc-950 dark:text-white font-bold">{{ $this->products->total() }}</span> Products
                    </div>

                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <label for="sort-select" class="text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400 whitespace-nowrap">Sort By</label>
                            <select
                                id="sort-select"
                                wire:model.live="sortBy"
                                class="text-sm border border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-900 dark:text-white rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                            >
                                <option value="featured">Featured First</option>
                                <option value="price_asc">Price: Low to High</option>
                                <option value="price_desc">Price: High to Low</option>
                                <option value="newest">Newest Additions</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($this->products as $product)
                        <x-shop.product-card :product="$product" />
                    @empty
                        <div class="col-span-full py-12 text-center rounded-2xl border border-dashed border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
                            <flux:icon icon="square-3-stack-3d" class="size-10 text-zinc-400 mx-auto mb-3" />
                            <h3 class="text-base font-semibold text-zinc-900 dark:text-white">No Products Found</h3>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Try clearing your filters or widening your search query.</p>
                            <flux:button wire:click="clearFilters" variant="ghost" size="sm" class="mt-4 text-blue-600 dark:text-blue-400">
                                Clear All Filters
                            </flux:button>
                        </div>
                    @endforelse
                </div>

                @if($this->products->hasPages())
                    <div class="pt-6">
                        {{ $this->products->links(data: ['scrollTo' => false]) }}
                    </div>
                @endif
            </div>
        </div>
    </section>

    <x-shop.trust-banner />
    <x-shop.faq />
</main>
