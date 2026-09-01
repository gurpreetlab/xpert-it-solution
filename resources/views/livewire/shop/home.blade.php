<div>
    <x-layouts.app>
        <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6 space-y-12">

            <!-- Search & Quick Category Bar -->
            <section class="bg-surface rounded-2xl p-4 border border-border shadow-2xs space-y-4">
                <div class="relative max-w-3xl mx-auto">
                    <div class="relative flex items-center">
                        <flux:icon icon="magnifying-glass" class="absolute left-4 size-5 text-zinc-400" />
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="search"
                            placeholder="Search Wi-Fi 6 routers, 1TB NVMe SSD, 8ch NVR, wireless keyboards, laser printers..."
                            class="w-full pl-12 pr-24 py-3 bg-surface-muted rounded-xl border border-border text-sm text-zinc-900 placeholder-zinc-400 focus:outline-hidden focus:ring-2 focus:ring-primary focus:bg-surface transition" />
                        @if($search !== '')
                            <button type="button" wire:click="$set('search', '')" class="absolute right-12 text-xs font-semibold text-zinc-400 hover:text-zinc-600 cursor-pointer">
                                Clear
                            </button>
                        @endif
                        <button type="button" class="absolute right-2 px-3 py-1.5 rounded-lg bg-primary text-white text-xs font-semibold hover:bg-primary-hover transition">
                            Search
                        </button>
                    </div>
                </div>

                <!-- Primary IT Categories Pills -->
                <div class="flex items-center justify-center gap-2 overflow-x-auto pb-1 no-scrollbar text-xs font-medium">
                    <button
                        type="button"
                        wire:click="clearFilters"
                        class="px-3.5 py-1.5 rounded-full border transition cursor-pointer whitespace-nowrap {{ $selectedCategoryId === '' ? 'bg-primary text-white border-primary' : 'bg-surface-muted text-zinc-700 border-border hover:bg-surface-elevated' }}">
                        All Hardware
                    </button>

                    @foreach(['Networking', 'CCTV & Security', 'Storage', 'Computer Peripherals', 'Power & Accessories', 'Printing'] as $catName)
                        @php
                            $catObj = $this->categories->first(fn($c) => str_contains(strtolower($c->name), strtolower(explode(' ', $catName)[0])));
                        @endphp
                        <button
                            type="button"
                            wire:click="$set('selectedCategoryId', '{{ $catObj?->id ?? '' }}')"
                            class="px-3.5 py-1.5 rounded-full border transition cursor-pointer whitespace-nowrap {{ ($catObj && (string)$selectedCategoryId === (string)$catObj->id) ? 'bg-primary text-white border-primary' : 'bg-surface-muted text-zinc-700 border-border hover:bg-surface-elevated' }}">
                            {{ $catName }}
                        </button>
                    @endforeach
                </div>
            </section>

            @if(!$this->hasActiveFilters)
                <!-- Product-Led Hero Offer Banner -->
                <section class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2 bg-gradient-to-r from-slate-900 to-blue-950 text-white rounded-2xl p-6 sm:p-8 flex flex-col justify-between relative overflow-hidden shadow-sm">
                        <div class="relative z-10 space-y-3 max-w-lg">
                            <span class="inline-block px-2.5 py-1 rounded-md bg-blue-500/20 text-blue-300 text-xs font-bold border border-blue-400/30">
                                Enterprise IT & Networking Solutions
                            </span>
                            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight leading-tight">
                                High-Speed Wi-Fi 6 Routers & PoE Gigabit Switches
                            </h1>
                            <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                                Upgrade your home or office infrastructure with authentic hardware from TP-Link, Hikvision, WD, and Logitech.
                            </p>
                        </div>
                        <div class="relative z-10 pt-6 flex items-center gap-3">
                            <a href="#products" class="px-5 py-2.5 rounded-xl bg-primary hover:bg-primary-hover text-white text-xs font-bold transition shadow-sm">
                                Shop Networking →
                            </a>
                            <span class="text-xs text-slate-400">Next-Day Delivery Available</span>
                        </div>
                    </div>

                    <div class="bg-surface rounded-2xl p-6 border border-border flex flex-col justify-between space-y-4">
                        <div class="space-y-2">
                            <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Storage Deal</span>
                            <h2 class="text-lg font-bold text-zinc-900">NVMe PCIe Gen4 Solid State Drives</h2>
                            <p class="text-xs text-zinc-500">Read speeds up to 7450 MB/s for high performance PC builds.</p>
                        </div>
                        <div class="pt-2 border-t border-border flex items-center justify-between">
                            <div>
                                <span class="text-xs text-zinc-400 block">Starting from</span>
                                <span class="text-xl font-extrabold text-zinc-950">₹3,499</span>
                            </div>
                            <a href="#products" class="px-3.5 py-2 rounded-lg bg-surface-muted hover:bg-border text-zinc-900 text-xs font-semibold transition">
                                Explore
                            </a>
                        </div>
                    </div>
                </section>

                <!-- Merchandising Product Carousel 1: Trending IT Hardware -->
                <x-product.carousel
                    title="Trending IT Hardware"
                    subtitle="Most popular items viewed and purchased by customers today"
                    viewAllUrl="#products"
                    :products="$this->trendingProducts"
                    carouselId="trending-hardware" />

                <!-- Merchandising Product Shelf 1: Deals of the Day -->
                <x-product.carousel
                    title="Limited-Time Deals"
                    subtitle="Special price drops on high-demand storage, routers, and cameras"
                    viewAllUrl="#products"
                    :products="$this->dealsOfDay"
                    carouselId="daily-deals" />

                <!-- Merchandising Category Product Shelves -->
                @php $netProducts = $this->getCategoryProducts('Networking', 4); @endphp
                @if(count($netProducts) > 0)
                    <x-product.shelf
                        title="Networking & Connectivity"
                        subtitle="Routers, switches, access points, and SFP fiber modules"
                        viewAllUrl="#products"
                        :products="$netProducts"
                        :columns="4" />
                @endif

                @php $cctvProducts = $this->getCategoryProducts('CCTV', 4); @endphp
                @if(count($cctvProducts) > 0)
                    <x-product.shelf
                        title="CCTV & Surveillance"
                        subtitle="IP cameras, NVRs, surveillance hard drives, and PoE power"
                        viewAllUrl="#products"
                        :products="$cctvProducts"
                        :columns="4" />
                @endif

                @php $storageProducts = $this->getCategoryProducts('Storage', 4); @endphp
                @if(count($storageProducts) > 0)
                    <x-product.shelf
                        title="High-Speed Storage"
                        subtitle="NVMe SSDs, internal HDDs, external drives, and USB flash storage"
                        viewAllUrl="#products"
                        :products="$storageProducts"
                        :columns="4" />
                @endif

                <!-- Popular Brands Ecosystem -->
                @if(count($this->popularBrands) > 0)
                <section class="py-4 space-y-4">
                    <div class="flex items-end justify-between">
                        <div>
                            <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-zinc-900">Popular Brands</h2>
                            <p class="text-xs sm:text-sm text-zinc-500 mt-0.5">100% authentic products backed by manufacturer warranties</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3">
                        @foreach($this->popularBrands as $brand)
                            <button
                                type="button"
                                wire:click="$set('selectedBrandId', '{{ $brand->id }}')"
                                class="p-4 rounded-xl bg-surface border border-border hover:border-primary hover:shadow-xs transition flex flex-col items-center justify-center text-center group cursor-pointer">
                                <span class="font-extrabold text-sm text-zinc-900 group-hover:text-primary transition">{{ $brand->name }}</span>
                                <span class="text-[10px] text-zinc-400 mt-1">{{ $brand->products_count }} Products</span>
                            </button>
                        @endforeach
                    </div>
                </section>
                @endif
            @endif

            <!-- Main Interactive Product Catalog Section -->
            <section id="products" class="scroll-mt-20 space-y-6 pt-4">
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-2 border-b border-border pb-4">
                    <div>
                        <h2 class="text-2xl font-bold tracking-tight text-zinc-900">Full IT Product Catalog</h2>
                        <p class="text-xs sm:text-sm text-zinc-500 mt-0.5">Filter by brand, category, and technical specifications</p>
                    </div>

                    <div class="text-xs text-zinc-500 font-medium">
                        Showing <span class="font-bold text-zinc-900">{{ $this->products->total() }}</span> Items
                    </div>
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
                    <!-- Sidebar Filters -->
                    <div class="lg:col-span-1 space-y-6">
                        <x-shop.sidebar-filters
                            :categories="$this->categories"
                            :brands="$this->brands"
                            :selected-category-id="$selectedCategoryId"
                            :selected-brand-id="$selectedBrandId"
                            :search="$search"
                            :total-products-count="$this->totalProductsCount" />
                    </div>

                    <!-- Product Grid & Sorting -->
                    <div class="lg:col-span-3 space-y-6">
                        <div class="flex items-center justify-between gap-4 p-3.5 rounded-xl border border-border bg-surface shadow-2xs">
                            <div class="text-xs text-zinc-500 font-medium">
                                Sorted by <span class="font-bold text-zinc-900">{{ $this->sortOptions[$sortBy] ?? 'Featured' }}</span>
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
                                <x-shop.product-card :product="$product" wire:key="home-p-grid-{{ $product->id }}" />
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
            </section>

            <x-shop.trust-banner />
            <x-shop.faq />
        </main>
    </x-layouts.app>
</div>
