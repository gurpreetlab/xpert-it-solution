<!-- Main Workspace -->
<main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">

    <!-- Hero Section -->
    <section class="relative overflow-hidden rounded-3xl bg-zinc-950 text-white py-16 px-6 sm:px-12 lg:px-16 mb-16 shadow-2xl border border-zinc-900">
        <!-- Decorative blur spots for premium ambient lighting -->
        <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-blue-500/20 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 rounded-full bg-indigo-600/15 blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/2 left-1/3 w-72 h-72 rounded-full bg-emerald-500/10 blur-3xl pointer-events-none"></div>

        <div class="relative z-10 max-w-3xl mx-auto text-center">
            <!-- Trust badge -->
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-zinc-900 text-blue-400 border border-zinc-800 mb-6">
                <span class="size-2 rounded-full bg-blue-500 animate-pulse"></span>
                Enterprise IT & Security Hardware Partner
            </span>

            <!-- Main Heading -->
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-white mb-6 leading-tight">
                Enterprise-Grade <span class="bg-gradient-to-r from-blue-400 via-indigo-300 to-purple-400 bg-clip-text text-transparent">IT Solutions</span> for Modern Businesses
            </h1>

            <!-- Subtitle -->
            <p class="text-base sm:text-lg text-zinc-300 mb-10 max-w-2xl mx-auto leading-relaxed">
                Discover top-tier networking hardware, advanced CCTV surveillance, resilient power backups, high-speed storage drives, and premium computing essentials.
            </p>

            <!-- Search Input in Hero -->
            <div class="max-w-xl mx-auto mb-12">
                <div class="flex gap-2 p-1.5 bg-zinc-900/90 backdrop-blur-sm border border-zinc-800 rounded-xl shadow-xl focus-within:ring-2 focus-within:ring-blue-500 transition-all">
                    <div class="flex-1 flex items-center px-3">
                        <flux:icon icon="magnifying-glass" class="size-5 text-zinc-400 mr-2 shrink-0" />
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="search"
                            placeholder="Search cameras, routers, hard disks, monitors..."
                            class="w-full bg-transparent border-0 text-white placeholder-zinc-500 focus:outline-none focus:ring-0 text-sm py-2"
                            id="hero-search-input"
                        />
                    </div>
                    <flux:button href="#products" class="bg-blue-600 hover:bg-blue-700 text-white border-0 font-medium px-5 rounded-lg text-sm transition">
                        Browse Store
                    </flux:button>
                </div>
            </div>

            <!-- Stats Row -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 pt-8 border-t border-zinc-900 max-w-4xl mx-auto">
                <div>
                    <div class="text-3xl font-extrabold text-white">43+</div>
                    <div class="text-xs text-zinc-400 uppercase tracking-wider font-semibold mt-1">Leading Brands</div>
                </div>
                <div>
                    <div class="text-3xl font-extrabold text-white">50+</div>
                    <div class="text-xs text-zinc-400 uppercase tracking-wider font-semibold mt-1">Tech Categories</div>
                </div>
                <div>
                    <div class="text-3xl font-extrabold text-white">100%</div>
                    <div class="text-xs text-zinc-400 uppercase tracking-wider font-semibold mt-1">Genuine Products</div>
                </div>
                <div>
                    <div class="text-3xl font-extrabold text-white">24/7</div>
                    <div class="text-xs text-zinc-400 uppercase tracking-wider font-semibold mt-1">IT Expert Support</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Showcase Section -->
    <section id="categories" class="mb-16 scroll-mt-20">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-8">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-zinc-900 dark:text-white">Shop by IT Category</h2>
                <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Select a category to filter products dynamically.</p>
            </div>
            @if($selectedCategoryId)
                <flux:button wire:click="$set('selectedCategoryId', '')" variant="ghost" size="sm" class="mt-4 md:mt-0 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-zinc-900">
                    Show All Categories
                </flux:button>
            @endif
        </div>

        <!-- Categories Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($this->categories as $cat)
                @php
                    $icon = 'square-3-stack-3d';
                    $colorClass = 'text-zinc-600 dark:text-zinc-400 bg-zinc-50 dark:bg-zinc-900 border-zinc-200 dark:border-zinc-800';
                    $activeClass = '';

                    if ($cat->name === 'Networking') {
                        $icon = 'wifi';
                        $colorClass = 'text-blue-600 dark:text-blue-400 bg-blue-50/50 dark:bg-blue-950/20 border-blue-100 dark:border-blue-900/30';
                    } elseif ($cat->name === 'CCTV & Security') {
                        $icon = 'video-camera';
                        $colorClass = 'text-emerald-600 dark:text-emerald-400 bg-emerald-50/50 dark:bg-emerald-950/20 border-emerald-100 dark:border-emerald-900/30';
                    } elseif ($cat->name === 'Storage') {
                        $icon = 'circle-stack';
                        $colorClass = 'text-purple-600 dark:text-purple-400 bg-purple-50/50 dark:bg-purple-950/20 border-purple-100 dark:border-purple-900/30';
                    } elseif ($cat->name === 'Computer Peripherals') {
                        $icon = 'computer-desktop';
                        $colorClass = 'text-amber-600 dark:text-amber-400 bg-amber-50/50 dark:bg-amber-950/20 border-amber-100 dark:border-amber-900/30';
                    } elseif ($cat->name === 'Power & Accessories') {
                        $icon = 'bolt';
                        $colorClass = 'text-orange-600 dark:text-orange-400 bg-orange-50/50 dark:bg-orange-950/20 border-orange-100 dark:border-orange-900/30';
                    } elseif ($cat->name === 'Printing') {
                        $icon = 'printer';
                        $colorClass = 'text-indigo-600 dark:text-indigo-400 bg-indigo-50/50 dark:bg-indigo-950/20 border-indigo-100 dark:border-indigo-900/30';
                    }

                    if ($selectedCategoryId == $cat->id) {
                        $activeClass = 'ring-2 ring-blue-600 dark:ring-blue-500 scale-105';
                    }
                @endphp

                <div
                    wire:click="$set('selectedCategoryId', '{{ $selectedCategoryId == $cat->id ? '' : $cat->id }}')"
                    class="cursor-pointer group flex flex-col items-center text-center p-5 rounded-2xl border transition-all duration-300 hover:shadow-lg hover:-translate-y-1 {{ $colorClass }} {{ $activeClass }}"
                >
                    <div class="p-3.5 rounded-xl bg-white dark:bg-zinc-800 shadow-sm mb-3 group-hover:scale-110 transition-transform duration-200">
                        <flux:icon icon="{{ $icon }}" class="size-6 text-current" />
                    </div>
                    <span class="text-sm font-semibold tracking-tight">{{ $cat->name }}</span>
                    <span class="text-xs text-zinc-500 dark:text-zinc-400 mt-1 font-medium">{{ $cat->products_count }} Products</span>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Featured Products Section -->
    <section id="featured" class="mb-16 scroll-mt-20">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-8">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-zinc-900 dark:text-white">Featured Technology Products</h2>
                <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Specially selected top-performing IT hardware from verified brands.</p>
            </div>
        </div>

        <!-- Featured Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($this->featuredProducts as $product)
                @php
                    // Calculate saving percentage
                    $discount = $product->mrp > 0 ? round((($product->mrp - $product->sale_price) / $product->mrp) * 100) : 0;

                    // Decide card gradient background & icon based on category
                    $gradientFrom = 'from-zinc-800';
                    $gradientTo = 'to-zinc-950';
                    $categoryIcon = 'shopping-bag';

                    if ($product->category?->name === 'Networking') {
                        $gradientFrom = 'from-blue-900';
                        $gradientTo = 'to-zinc-950';
                        $categoryIcon = 'wifi';
                    } elseif ($product->category?->name === 'CCTV & Security') {
                        $gradientFrom = 'from-emerald-900';
                        $gradientTo = 'to-zinc-950';
                        $categoryIcon = 'video-camera';
                    } elseif ($product->category?->name === 'Storage') {
                        $gradientFrom = 'from-purple-900';
                        $gradientTo = 'to-zinc-950';
                        $categoryIcon = 'circle-stack';
                    } elseif ($product->category?->name === 'Computer Peripherals') {
                        $gradientFrom = 'from-amber-900';
                        $gradientTo = 'to-zinc-950';
                        $categoryIcon = 'computer-desktop';
                    } elseif ($product->category?->name === 'Power & Accessories') {
                        $gradientFrom = 'from-orange-900';
                        $gradientTo = 'to-zinc-950';
                        $categoryIcon = 'bolt';
                    } elseif ($product->category?->name === 'Printing') {
                        $gradientFrom = 'from-indigo-900';
                        $gradientTo = 'to-zinc-950';
                        $categoryIcon = 'printer';
                    }
                @endphp

                <div class="flex flex-col rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-md hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 overflow-hidden group">

                    <!-- Product Preview Box (High-End Gradient Design) -->
                    <div class="relative aspect-video bg-gradient-to-br {{ $gradientFrom }} {{ $gradientTo }} flex items-center justify-center p-6 text-white overflow-hidden">
                        <!-- Overlay grids -->
                        <div class="absolute inset-0 bg-[linear-gradient(to_right,#80808012_1px,transparent_1px),linear-gradient(to_bottom,#80808012_1px,transparent_1px)] bg-[size:16px_16px]"></div>

                        <!-- Ambient glowing blob -->
                        <div class="absolute size-24 rounded-full bg-white/10 blur-xl"></div>

                        <!-- Icon inside preview -->
                        <div class="relative z-10 p-5 rounded-2xl bg-white/10 border border-white/20 backdrop-blur-md shadow-2xl group-hover:scale-110 transition-transform duration-300">
                            <flux:icon icon="{{ $categoryIcon }}" class="size-10 text-white" />
                        </div>

                        <!-- Discount badge -->
                        @if($discount > 0)
                            <span class="absolute top-4 right-4 inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-rose-500 text-white shadow-sm">
                                {{ $discount }}% OFF
                            </span>
                        @endif

                        <!-- Brand name watermark -->
                        <span class="absolute bottom-4 left-4 text-xs font-semibold uppercase tracking-wider text-white/50 bg-white/10 px-2 py-0.5 rounded backdrop-blur-xs">
                            {{ $product->brand?->name ?? 'Brand' }}
                        </span>
                    </div>

                    <!-- Card Body -->
                    <div class="flex-1 p-6 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-medium text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950/30 px-2.5 py-0.5 rounded-full">
                                    {{ $product->category?->name }}
                                </span>
                                <span class="text-xs text-zinc-500 dark:text-zinc-400 font-semibold">SKU: {{ $product->sku ?? 'N/A' }}</span>
                            </div>

                            <a href="{{ route('shop.product.details', $product->slug) }}" wire:navigate>
                                <h3 class="text-lg font-bold text-zinc-900 dark:text-white line-clamp-1 mb-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                        {{ $product->name }}
                                </h3>
                            </a>

                            <p class="text-sm text-zinc-600 dark:text-zinc-400 line-clamp-2 mb-4 font-medium leading-relaxed">
                                {{ $product->short_description ?? 'No description available for this product.' }}
                            </p>
                        </div>

                        <div>
                            <!-- Price & CTA -->
                            <div class="flex items-center justify-between pt-4 border-t border-zinc-100 dark:border-zinc-800">
                                <div class="flex flex-col">
                                    @if($product->mrp > $product->sale_price)
                                        <span class="text-xs text-zinc-400 dark:text-zinc-500 line-through">₹{{ number_format($product->mrp, 2) }}</span>
                                    @endif
                                    <span class="text-xl font-extrabold text-zinc-950 dark:text-white">₹{{ number_format($product->sale_price, 2) }}</span>
                                </div>

                                <div class="flex gap-2">
                                    <a href="{{ route('shop.product.details', $product->slug) }}" wire:navigate>
                                        <flux:button variant="ghost" size="sm" class="cursor-pointer text-zinc-600 dark:text-zinc-400 font-semibold">
                                            Details
                                        </flux:button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Product Catalog Section -->
    <section id="products" class="mb-16 scroll-mt-20">
        <div class="border-t border-zinc-200 dark:border-zinc-800 pt-12 mb-8">
            <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-zinc-900 dark:text-white">Explore Product Catalog</h2>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Live search, filter by brand, category and sort according to your preference.</p>
        </div>

        <!-- Filters & Catalog Container -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

            <!-- Sidebar Filters -->
            <div class="lg:col-span-1 space-y-6">
                <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6 shadow-sm space-y-6">

                    <!-- Search input inside sidebar -->
                    <div>
                        <flux:field>
                            <flux:label class="text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Search Products</flux:label>
                            <div class="relative mt-1">
                                <flux:input wire:model.live.debounce.300ms="search" placeholder="Type query..." icon="magnifying-glass" />
                            </div>
                        </flux:field>
                    </div>

                    <flux:separator />

                    <!-- Category Filter -->
                    <div>
                        <span class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-2">Filter by Category</span>
                        <div class="space-y-1 max-h-48 overflow-y-auto pr-2">
                            <button
                                wire:click="$set('selectedCategoryId', '')"
                                class="w-full text-left px-2 py-1.5 rounded-lg text-sm flex justify-between items-center transition {{ $selectedCategoryId === '' ? 'bg-blue-50 dark:bg-blue-950/20 text-blue-600 dark:text-blue-400 font-semibold' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-800' }}"
                            >
                                <span>All Categories</span>
                                <span class="text-xs px-1.5 py-0.5 rounded-md bg-zinc-100 dark:bg-zinc-800 text-zinc-500">{{ \App\Models\Product::count() }}</span>
                            </button>
                            @foreach($this->categories as $category)
                                <button
                                    wire:click="$set('selectedCategoryId', '{{ $category->id }}')"
                                    class="w-full text-left px-2 py-1.5 rounded-lg text-sm flex justify-between items-center transition {{ $selectedCategoryId == $category->id ? 'bg-blue-50 dark:bg-blue-950/20 text-blue-600 dark:text-blue-400 font-semibold' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-800' }}"
                                >
                                    <span class="truncate pr-2">{{ $category->name }}</span>
                                    <span class="text-xs px-1.5 py-0.5 rounded-md bg-zinc-100 dark:bg-zinc-800 text-zinc-500">{{ $category->products_count }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <flux:separator />

                    <!-- Brand Filter -->
                    <div>
                        <span class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-2">Filter by Brand</span>
                        <div class="space-y-1 max-h-48 overflow-y-auto pr-2">
                            <button
                                wire:click="$set('selectedBrandId', '')"
                                class="w-full text-left px-2 py-1.5 rounded-lg text-sm flex justify-between items-center transition {{ $selectedBrandId === '' ? 'bg-blue-50 dark:bg-blue-950/20 text-blue-600 dark:text-blue-400 font-semibold' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-800' }}"
                            >
                                <span>All Brands</span>
                                <span class="text-xs px-1.5 py-0.5 rounded-md bg-zinc-100 dark:bg-zinc-800 text-zinc-500">{{ \App\Models\Product::count() }}</span>
                            </button>
                            @foreach($this->brands as $brand)
                                @if($brand->products_count > 0)
                                    <button
                                        wire:click="$set('selectedBrandId', '{{ $brand->id }}')"
                                        class="w-full text-left px-2 py-1.5 rounded-lg text-sm flex justify-between items-center transition {{ $selectedBrandId == $brand->id ? 'bg-blue-50 dark:bg-blue-950/20 text-blue-600 dark:text-blue-400 font-semibold' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-800' }}"
                                    >
                                        <span class="truncate pr-2">{{ $brand->name }}</span>
                                        <span class="text-xs px-1.5 py-0.5 rounded-md bg-zinc-100 dark:bg-zinc-800 text-zinc-500">{{ $brand->products_count }}</span>
                                    </button>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- Reset Buttons -->
                    @if($search !== '' || $selectedCategoryId !== '' || $selectedBrandId !== '')
                        <flux:separator />
                        <flux:button wire:click="clearFilters" variant="ghost" size="sm" class="w-full text-zinc-600 hover:bg-zinc-100 dark:text-zinc-400 dark:hover:bg-zinc-800 font-semibold">
                            Reset Filters
                        </flux:button>
                    @endif

                </div>
            </div>

            <!-- Products Grid & Controls -->
            <div class="lg:col-span-3 space-y-6">

                <!-- Controls toolbar -->
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 p-4 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
                    <div class="text-sm font-medium text-zinc-600 dark:text-zinc-400">
                        Showing <span class="text-zinc-950 dark:text-white font-bold">{{ $this->products->total() }}</span> Products
                    </div>

                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <!-- Sort Dropdown -->
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

                <!-- Products Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($this->products as $product)
                        @php
                            $discount = $product->mrp > 0 ? round((($product->mrp - $product->sale_price) / $product->mrp) * 100) : 0;

                            $gradientFrom = 'from-zinc-700';
                            $gradientTo = 'to-zinc-900';
                            $categoryIcon = 'shopping-bag';

                            if ($product->category?->name === 'Networking') {
                                $gradientFrom = 'from-blue-800';
                                $gradientTo = 'to-zinc-900';
                                $categoryIcon = 'wifi';
                            } elseif ($product->category?->name === 'CCTV & Security') {
                                $gradientFrom = 'from-emerald-800';
                                $gradientTo = 'to-zinc-900';
                                $categoryIcon = 'video-camera';
                            } elseif ($product->category?->name === 'Storage') {
                                $gradientFrom = 'from-purple-800';
                                $gradientTo = 'to-zinc-900';
                                $categoryIcon = 'circle-stack';
                            } elseif ($product->category?->name === 'Computer Peripherals') {
                                $gradientFrom = 'from-amber-800';
                                $gradientTo = 'to-zinc-900';
                                $categoryIcon = 'computer-desktop';
                            } elseif ($product->category?->name === 'Power & Accessories') {
                                $gradientFrom = 'from-orange-800';
                                $gradientTo = 'to-zinc-900';
                                $categoryIcon = 'bolt';
                            } elseif ($product->category?->name === 'Printing') {
                                $gradientFrom = 'from-indigo-800';
                                $gradientTo = 'to-zinc-900';
                                $categoryIcon = 'printer';
                            }
                        @endphp

                        <div class="flex flex-col bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 overflow-hidden group">
                            <!-- Image block -->
                            <div class="relative aspect-video bg-gradient-to-br {{ $gradientFrom }} {{ $gradientTo }} flex items-center justify-center p-6 text-white">
                                <div class="absolute inset-0 bg-[linear-gradient(to_right,#8080800a_1px,transparent_1px),linear-gradient(to_bottom,#8080800a_1px,transparent_1px)] bg-[size:14px_14px]"></div>
                                <div class="relative z-10 p-3.5 rounded-xl bg-white/10 border border-white/10 backdrop-blur-md shadow-lg group-hover:scale-105 transition-transform duration-300">
                                    <flux:icon icon="{{ $categoryIcon }}" class="size-7 text-white" />
                                </div>
                                @if($discount > 0)
                                    <span class="absolute top-3 right-3 inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-rose-500 text-white shadow-sm">
                                        {{ $discount }}% OFF
                                    </span>
                                @endif
                            </div>

                            <!-- Body -->
                            <div class="flex-1 p-5 flex flex-col justify-between space-y-4">
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="font-medium text-zinc-500 dark:text-zinc-400">{{ $product->brand?->name }}</span>
                                        <span class="font-semibold {{ $product->stock > 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                            {{ $product->stock > 0 ? 'In Stock' : 'Out of Stock' }}
                                        </span>
                                    </div>
                                    <a href="{{ route('shop.product.details', $product->slug) }}" wire:navigate>
                                        <h3 class="text-sm font-bold text-zinc-900 dark:text-white line-clamp-1 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                            {{ $product->name }}
                                        </h3>
                                    </a>
                                    <p class="text-xs text-zinc-500 dark:text-zinc-400 line-clamp-2 leading-relaxed">
                                        {{ $product->short_description ?? 'High performance device.' }}
                                    </p>
                                </div>

                                <div class="pt-3 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-between">
                                    <div class="flex flex-col">
                                        @if($product->mrp > $product->sale_price)
                                            <span class="text-[10px] text-zinc-400 dark:text-zinc-500 line-through">₹{{ number_format($product->mrp, 2) }}</span>
                                        @endif
                                        <span class="text-base font-extrabold text-zinc-950 dark:text-white">₹{{ number_format($product->sale_price, 2) }}</span>
                                    </div>

                                    <div class="flex gap-1.5">
                                        <a href="{{ route('shop.product.details', $product->slug) }}" wire:navigate>
                                            <flux:button variant="ghost" size="sm" class="cursor-pointer text-zinc-500 hover:text-zinc-900 dark:hover:text-white">
                                                View
                                            </flux:button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
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

                <!-- Pagination -->
                @if($this->products->hasPages())
                    <div class="pt-6">
                        {{ $this->products->links(data: ['scrollTo' => false]) }}
                    </div>
                @endif
            </div>

        </div>
    </section>

    <!-- Trust & Features Banner -->
    <section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16 py-12 px-6 rounded-3xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm text-center md:text-left">
        <div class="flex flex-col md:flex-row items-center gap-4">
            <div class="p-3 rounded-2xl bg-blue-50 dark:bg-blue-950/20 text-blue-600 dark:text-blue-400">
                <flux:icon icon="shield-check" class="size-7" />
            </div>
            <div>
                <h4 class="font-bold text-zinc-900 dark:text-white text-base">Authorized Distributor</h4>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">100% genuine products sourced directly from manufacturers like Netgear, CP Plus, and WD.</p>
            </div>
        </div>
        <div class="flex flex-col md:flex-row items-center gap-4">
            <div class="p-3 rounded-2xl bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600 dark:text-emerald-400">
                <flux:icon icon="circle-stack" class="size-7" />
            </div>
            <div>
                <h4 class="font-bold text-zinc-900 dark:text-white text-base">Bulk Corporate Pricing</h4>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">Custom quotation rates available for large-scale enterprise deployments and installations.</p>
            </div>
        </div>
        <div class="flex flex-col md:flex-row items-center gap-4">
            <div class="p-3 rounded-2xl bg-purple-50 dark:bg-purple-950/20 text-purple-600 dark:text-purple-400">
                <flux:icon icon="bolt" class="size-7" />
            </div>
            <div>
                <h4 class="font-bold text-zinc-900 dark:text-white text-base">Expert Consultation</h4>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">Speak with our IT solutions engineers to configure networks, server storage, or surveillance layouts.</p>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="mb-16">
        <div class="text-center max-w-2xl mx-auto mb-10">
            <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-zinc-900 dark:text-white">Frequently Asked Questions</h2>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Get quick answers regarding procurement, pricing, and installation.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-5xl mx-auto">
            <div class="p-6 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
                <h4 class="font-bold text-zinc-900 dark:text-white mb-2">Do you offer bulk or wholesale pricing?</h4>
                <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                    Yes, we support large-scale orders and business partnerships. Please share your project details and requirements through our <a href="/contact" class="text-blue-600 dark:text-blue-400 underline">Contact Page</a>, and a member of our team will reach out to discuss the best pricing options for you.
                </p>
            </div>
            <div class="p-6 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
                <h4 class="font-bold text-zinc-900 dark:text-white mb-2">Do you provide on-site configuration and installation services?</h4>
                <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">Yes, Xpert IT Solution provides complete deployment, structured cabling, IP camera installation, and networking setup across the region through our certified engineers.</p>
            </div>
            <div class="p-6 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
                <h4 class="font-bold text-zinc-900 dark:text-white mb-2">Are all the products genuine and covered by warranty?</h4>
                <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">Absolutely. We are direct partners with all major tech brands shown. Every purchase includes official invoices and standard brand warranty cards valid across authorized service centers.</p>
            </div>
            <div class="p-6 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
                <h4 class="font-bold text-zinc-900 dark:text-white mb-2">What is the estimated delivery timeframe for bulk orders?</h4>
                <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">Local orders are processed and delivered within 1-2 business days. Larger freight shipments or remote warehouse dispatches take between 3-5 working days.</p>
            </div>
        </div>
    </section>
</main>
