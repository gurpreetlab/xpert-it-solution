<main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs font-medium text-zinc-500 mb-2">
        <a href="{{ route('home') }}" class="hover:text-primary transition" wire:navigate>Home</a>
        <flux:icon icon="chevron-right" class="size-3 text-zinc-400" />
        <span class="text-zinc-900 font-semibold">IT Hardware Catalog</span>
    </nav>

    <div class="mb-4">
        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-zinc-900">Explore IT Products</h1>
        <p class="text-xs sm:text-sm text-zinc-500 mt-1">Search, filter by brand and category, and sort according to your preference.</p>
    </div>

    <!-- Active Filter Chips -->
    @if($this->hasActiveFilters)
        <div class="flex flex-wrap items-center gap-2 p-3 rounded-xl border border-dashed border-border bg-surface-muted">
            <span class="text-xs font-semibold uppercase tracking-wide text-zinc-400 mr-1">Active Filters:</span>

            @if($search !== '')
                <span class="inline-flex items-center gap-1.5 pl-3 pr-1.5 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-medium border border-blue-200">
                    "{{ $search }}"
                    <button type="button" wire:click="$set('search', '')" class="rounded-full hover:bg-blue-100 p-0.5 transition cursor-pointer">
                        <flux:icon icon="x-mark" class="size-3" />
                    </button>
                </span>
            @endif

            @if($this->selectedCategoryName)
                <span class="inline-flex items-center gap-1.5 pl-3 pr-1.5 py-1 rounded-full bg-zinc-200 text-zinc-800 text-xs font-medium">
                    Category: {{ $this->selectedCategoryName }}
                    <button type="button" wire:click="$set('selectedCategoryId', '')" class="rounded-full hover:bg-zinc-300 p-0.5 transition cursor-pointer">
                        <flux:icon icon="x-mark" class="size-3" />
                    </button>
                </span>
            @endif

            @if($this->selectedBrandName)
                <span class="inline-flex items-center gap-1.5 pl-3 pr-1.5 py-1 rounded-full bg-zinc-200 text-zinc-800 text-xs font-medium">
                    Brand: {{ $this->selectedBrandName }}
                    <button type="button" wire:click="$set('selectedBrandId', '')" class="rounded-full hover:bg-zinc-300 p-0.5 transition cursor-pointer">
                        <flux:icon icon="x-mark" class="size-3" />
                    </button>
                </span>
            @endif

            <button type="button" wire:click="clearFilters" class="ml-auto text-xs font-semibold text-primary hover:underline cursor-pointer">
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
                :total-products-count="$this->totalProductsCount"
            />
        </div>

        <div class="lg:col-span-3 space-y-6">
            <div class="flex items-center justify-between gap-4 p-3.5 rounded-xl border border-border bg-surface shadow-2xs">
                <div class="text-xs text-zinc-500 font-medium">
                    Showing <span class="text-zinc-950 font-bold">{{ $this->products->total() }}</span> Products
                </div>

                <div class="flex items-center gap-2">
                    <label for="sort-select" class="text-xs font-semibold uppercase tracking-wider text-zinc-400 whitespace-nowrap">Sort</label>
                    <select
                        id="sort-select"
                        wire:model.live="sortBy"
                        class="text-xs border border-border bg-surface-muted rounded-lg px-2.5 py-1.5 focus:outline-hidden focus:ring-2 focus:ring-primary transition font-medium">
                        @foreach($this->sortOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div wire:loading.class="opacity-50 pointer-events-none" class="transition-opacity duration-150">
                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6">
                    @forelse($this->products as $product)
                        <x-shop.product-card :product="$product" wire:key="catalog-p-{{ $product->id }}" />
                    @empty
                        <div class="col-span-full py-12 text-center rounded-2xl border border-dashed border-border bg-surface">
                            <flux:icon icon="square-3-stack-3d" class="size-10 text-zinc-400 mx-auto mb-3" />
                            <h3 class="text-base font-semibold text-zinc-900">No Products Found</h3>
                            <p class="text-xs text-zinc-500 mt-1">Try clearing active search or category filters.</p>
                            @if($this->hasActiveFilters)
                                <flux:button wire:click="clearFilters" variant="ghost" size="sm" class="mt-4 text-primary">
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
</main>
