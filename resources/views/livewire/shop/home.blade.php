<main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">

    <x-shop.hero :search="$search" />

    <x-shop.category-grid
        :categories="$this->categories"
        :selected-category-id="$selectedCategoryId" />

    <x-shop.featured-products :products="$this->featuredProducts" />

    <section id="products" class="mb-16 scroll-mt-20">


        <div class="mb-6">
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-zinc-900 dark:text-white">Explore Product Catalog</h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Search, filter by brand and category, and sort according to your preference.</p>
        </div>

        <!-- Active Filter Chips -->
        @if($this->hasActiveFilters)
        <div class="flex flex-wrap items-center gap-2 mb-6 p-3 rounded-2xl border border-dashed border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
            <span class="text-xs font-semibold uppercase tracking-wide text-zinc-400 mr-1">Filters:</span>

            @if($search !== '')
            <span class="inline-flex items-center gap-1.5 pl-3 pr-1.5 py-1 rounded-full bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 text-xs font-medium">
                "{{ $search }}"
                <button type="button" wire:click="$set('search', '')" class="rounded-full hover:bg-blue-100 dark:hover:bg-blue-900 p-0.5 transition cursor-pointer">
                    <flux:icon icon="x-mark" class="size-3" />
                </button>
            </span>
            @endif

            @if($this->selectedCategoryName)
            <span class="inline-flex items-center gap-1.5 pl-3 pr-1.5 py-1 rounded-full bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 text-xs font-medium">
                {{ $this->selectedCategoryName }}
                <button type="button" wire:click="$set('selectedCategoryId', '')" class="rounded-full hover:bg-zinc-200 dark:hover:bg-zinc-700 p-0.5 transition cursor-pointer">
                    <flux:icon icon="x-mark" class="size-3" />
                </button>
            </span>
            @endif

            @if($this->selectedBrandName)
            <span class="inline-flex items-center gap-1.5 pl-3 pr-1.5 py-1 rounded-full bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 text-xs font-medium">
                {{ $this->selectedBrandName }}
                <button type="button" wire:click="$set('selectedBrandId', '')" class="rounded-full hover:bg-zinc-200 dark:hover:bg-zinc-700 p-0.5 transition cursor-pointer">
                    <flux:icon icon="x-mark" class="size-3" />
                </button>
            </span>
            @endif

            @if($sortBy !== 'featured')
            <span class="inline-flex items-center gap-1.5 pl-3 pr-1.5 py-1 rounded-full bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 text-xs font-medium">
                {{ $this->sortOptions[$sortBy] }}
                <button type="button" wire:click="$set('sortBy', 'featured')" class="rounded-full hover:bg-zinc-200 dark:hover:bg-zinc-700 p-0.5 transition cursor-pointer">
                    <flux:icon icon="x-mark" class="size-3" />
                </button>
            </span>
            @endif

            <button type="button" wire:click="clearFilters" class="ml-auto text-xs font-semibold text-blue-600 dark:text-blue-400 hover:underline cursor-pointer">
                Clear all
            </button>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <div class="lg:col-span-1 space-y-6">
                <x-shop.sidebar-filters
                    :categories="$this->categories"
                    :brands="$this->brands"
                    :selected-category-id="$selectedCategoryId"
                    :selected-brand-id="$selectedBrandId"
                    :search="$search"
                    :total-products-count="$this->totalProductsCount" />
            </div>

            <div class="lg:col-span-3 space-y-6">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 p-4 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
                    <div class="text-sm font-medium text-zinc-600 dark:text-zinc-400">
                        Showing <span class="text-zinc-950 dark:text-white font-bold">{{ $this->products->total() }}</span> Products
                    </div>

                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <label for="sort-select" class="text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400 whitespace-nowrap">Sort By</label>
                        <select
                            id="sort-select"
                            wire:model.live="sortBy"
                            class="text-sm border border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-900 dark:text-white rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                            @foreach($this->sortOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div wire:loading.class="opacity-50 pointer-events-none" wire:target="search, selectedCategoryId, selectedBrandId, sortBy, gotoPage, previousPage, nextPage" class="transition-opacity duration-150">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($this->products as $product)
                        <x-shop.product-card :product="$product" wire:key="product-{{ $product->id }}" />
                        @empty
                        <div class="col-span-full py-12 text-center rounded-2xl border border-dashed border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
                            <flux:icon icon="square-3-stack-3d" class="size-10 text-zinc-400 mx-auto mb-3" />
                            <h3 class="text-base font-semibold text-zinc-900 dark:text-white">No Products Found</h3>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Try clearing your filters or widening your search query.</p>
                            @if($this->hasActiveFilters)
                            <flux:button wire:click="clearFilters" variant="ghost" size="sm" class="mt-4 text-blue-600 dark:text-blue-400">
                                Clear All Filters
                            </flux:button>
                            @endif
                        </div>
                        @endforelse
                    </div>
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