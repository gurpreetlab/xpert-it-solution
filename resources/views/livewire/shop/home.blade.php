<div class="w-full">
    <!-- Navigation Bar -->
    <header class="sticky top-0 z-50 w-full border-b border-zinc-200/80 bg-white/80 backdrop-blur-md dark:border-zinc-800/80 dark:bg-zinc-950/80 transition-colors duration-300">
        <div class="mx-auto flex max-w-7xl h-16 items-center justify-between px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-6">
                <!-- Brand Logo & Name -->
                <a href="/" class="flex items-center gap-2.5 group">
                    <div class="flex aspect-square size-9 items-center justify-center rounded-lg bg-zinc-900 text-white dark:bg-white dark:text-zinc-950 shadow-md group-hover:scale-105 transition-transform duration-200">
                        <x-app-logo-icon class="size-5 fill-current" />
                    </div>
                    <span class="text-xl font-bold tracking-tight text-zinc-900 dark:text-white">
                        Xpert <span class="text-blue-600 dark:text-blue-500 font-semibold">IT Solution</span>
                    </span>
                </a>
            </div>

            <!-- Main Nav (Anchor links) -->
            <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-zinc-600 dark:text-zinc-400">
                <a href="#categories" class="hover:text-blue-600 dark:hover:text-blue-500 transition duration-200">Categories</a>
                <a href="#featured" class="hover:text-blue-600 dark:hover:text-blue-500 transition duration-200">Featured</a>
                <a href="#products" class="hover:text-blue-600 dark:hover:text-blue-500 transition duration-200">Products</a>
                <button wire:click="openEnquiry()" class="hover:text-blue-600 dark:hover:text-blue-500 transition duration-200 text-left">Contact & Enquiry</button>
            </nav>

            <!-- Right Buttons (Auth) -->
            <div class="flex items-center gap-4">
                @if (Route::has('login'))
                    @auth
                        @role('super-admin')
                            <flux:button href="{{ route('dashboard') }}" variant="filled" size="sm" class="bg-blue-600 hover:bg-blue-700 text-white border-0 font-medium">
                                Dashboard
                            </flux:button>
                        @endrole
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <flux:button type="submit" variant="ghost" size="sm" class="text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-900">
                                Log out
                            </flux:button>
                        </form>
                    @else
                        <flux:button href="{{ route('login') }}" variant="ghost" size="sm" class="text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-900">
                            Log in
                        </flux:button>
                        @if (Route::has('register'))
                            <flux:button href="{{ route('register') }}" variant="filled" size="sm" class="bg-blue-600 hover:bg-blue-700 text-white border-0 font-medium">
                                Register
                            </flux:button>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </header>

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

                                <h3 class="text-lg font-bold text-zinc-900 dark:text-white line-clamp-1 mb-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                    {{ $product->name }}
                                </h3>

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
                                        <flux:button wire:click="selectProduct({{ $product->id }})" variant="ghost" size="sm" class="text-zinc-600 dark:text-zinc-400 font-semibold">
                                            Details
                                        </flux:button>
                                        <flux:button wire:click="openEnquiry('{{ addslashes($product->name) }}')" variant="filled" size="sm" class="bg-blue-600 hover:bg-blue-700 text-white border-0 font-medium">
                                            Enquire
                                        </flux:button>
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
                                        <h3 class="text-sm font-bold text-zinc-900 dark:text-white line-clamp-1 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                            {{ $product->name }}
                                        </h3>
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
                                            <flux:button wire:click="selectProduct({{ $product->id }})" variant="ghost" size="sm" class="text-zinc-500 hover:text-zinc-900 dark:hover:text-white">
                                                View
                                            </flux:button>
                                            <flux:button wire:click="openEnquiry('{{ addslashes($product->name) }}')" variant="filled" size="sm" class="bg-blue-600 hover:bg-blue-700 text-white border-0 font-medium text-xs">
                                                Enquire
                                            </flux:button>
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
                    <h4 class="font-bold text-zinc-900 dark:text-white mb-2">How can I request a formal quotation for my enterprise?</h4>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">You can click the "Enquire" button on any product or use our main "Contact & Enquiry" form to supply your requirements. Our sales team will get back to you with custom prices within 24 hours.</p>
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

    <!-- Footer -->
    <footer id="contact" class="bg-zinc-900 text-zinc-400 border-t border-zinc-800 py-12 mt-12 transition-colors duration-300">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8 pb-8 border-b border-zinc-800">
                <div class="space-y-4">
                    <div class="flex items-center gap-2 text-white">
                        <div class="flex aspect-square size-8 items-center justify-center rounded bg-blue-600 text-white">
                            <x-app-logo-icon class="size-4.5 fill-current" />
                        </div>
                        <span class="text-lg font-bold">Xpert IT Solution</span>
                    </div>
                    <p class="text-xs leading-relaxed text-zinc-500">Premium IT Infrastructure, CCTV surveillance networking systems, enterprise back-ups, and storage solutions supplier.</p>
                </div>
                <div>
                    <h5 class="text-white text-xs font-semibold uppercase tracking-wider mb-4">Product Domains</h5>
                    <ul class="space-y-2 text-xs">
                        <li><a href="#products" wire:click="$set('selectedCategoryId', '2')" class="hover:text-white transition">CCTV Surveillance Cameras</a></li>
                        <li><a href="#products" wire:click="$set('selectedCategoryId', '1')" class="hover:text-white transition">Enterprise Wifi & Networking</a></li>
                        <li><a href="#products" wire:click="$set('selectedCategoryId', '3')" class="hover:text-white transition">Network Storage & HDDs</a></li>
                        <li><a href="#products" wire:click="$set('selectedCategoryId', '5')" class="hover:text-white transition">Industrial UPS Systems</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="text-white text-xs font-semibold uppercase tracking-wider mb-4">Corporate Info</h5>
                    <ul class="space-y-2 text-xs">
                        <li><a href="#" class="hover:text-white transition">About Us</a></li>
                        <li><a href="#" class="hover:text-white transition">Case Studies</a></li>
                        <li><a href="#" class="hover:text-white transition">Careers</a></li>
                        <li><a href="#" class="hover:text-white transition">Privacy Policy</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="text-white text-xs font-semibold uppercase tracking-wider mb-4">Get In Touch</h5>
                    <ul class="space-y-2 text-xs">
                        <li class="flex items-center gap-2">
                            <flux:icon icon="envelope" class="size-4 shrink-0 text-zinc-500" />
                            <span>info@xpertitsolution.com</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <flux:icon icon="phone" class="size-4 shrink-0 text-zinc-500" />
                            <span>+91 98765 43210</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <flux:icon icon="map-pin" class="size-4 shrink-0 text-zinc-500" />
                            <span>Gurpreet Lab Complex, Phase 8, Mohali, Punjab</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row items-center justify-between text-xs text-zinc-500">
                <span>&copy; {{ date('Y') }} Xpert IT Solution. All rights reserved.</span>
                <span class="mt-2 sm:mt-0">Designed by Senior UX/UI Engineer</span>
            </div>
        </div>
    </footer>

    <!-- Product Detail Modal -->
    <flux:modal wire:model="showProductModal" class="w-full max-w-4xl p-0 overflow-hidden rounded-3xl" @close="closeProductModal">
        @if($this->selectedProduct)
            @php
                $discount = $this->selectedProduct->mrp > 0 ? round((($this->selectedProduct->mrp - $this->selectedProduct->sale_price) / $this->selectedProduct->mrp) * 100) : 0;

                $gradientFrom = 'from-zinc-800';
                $gradientTo = 'to-zinc-950';
                $categoryIcon = 'shopping-bag';

                if ($this->selectedProduct->category?->name === 'Networking') {
                    $gradientFrom = 'from-blue-900';
                    $gradientTo = 'to-zinc-950';
                    $categoryIcon = 'wifi';
                } elseif ($this->selectedProduct->category?->name === 'CCTV & Security') {
                    $gradientFrom = 'from-emerald-900';
                    $gradientTo = 'to-zinc-950';
                    $categoryIcon = 'video-camera';
                } elseif ($this->selectedProduct->category?->name === 'Storage') {
                    $gradientFrom = 'from-purple-900';
                    $gradientTo = 'to-zinc-950';
                    $categoryIcon = 'circle-stack';
                } elseif ($this->selectedProduct->category?->name === 'Computer Peripherals') {
                    $gradientFrom = 'from-amber-900';
                    $gradientTo = 'to-zinc-950';
                    $categoryIcon = 'computer-desktop';
                } elseif ($this->selectedProduct->category?->name === 'Power & Accessories') {
                    $gradientFrom = 'from-orange-900';
                    $gradientTo = 'to-zinc-950';
                    $categoryIcon = 'bolt';
                } elseif ($this->selectedProduct->category?->name === 'Printing') {
                    $gradientFrom = 'from-indigo-900';
                    $gradientTo = 'to-zinc-950';
                    $categoryIcon = 'printer';
                }
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2">
                <!-- Left side (Product Preview / Gradient design) -->
                <div class="relative bg-gradient-to-br {{ $gradientFrom }} {{ $gradientTo }} flex flex-col items-center justify-center p-8 text-white min-h-[300px] md:min-h-full">
                    <!-- Overlay Grid -->
                    <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff05_1px,transparent_1px),linear-gradient(to_bottom,#ffffff05_1px,transparent_1px)] bg-[size:20px_20px]"></div>
                    <div class="absolute top-6 left-6 text-xs font-semibold uppercase tracking-wider text-white/50 bg-white/10 px-2.5 py-0.5 rounded backdrop-blur-xs">
                        {{ $this->selectedProduct->brand?->name }}
                    </div>

                    <div class="relative z-10 p-7 rounded-3xl bg-white/10 border border-white/20 backdrop-blur-md shadow-2xl scale-110">
                        <flux:icon icon="{{ $categoryIcon }}" class="size-16 text-white" />
                    </div>

                    <span class="mt-8 relative z-10 text-sm font-semibold tracking-wide text-zinc-300">
                        {{ $this->selectedProduct->category?->name }} Catalog Item
                    </span>
                </div>

                <!-- Right side (Info & Enquiry) -->
                <div class="p-6 sm:p-8 space-y-6 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white max-h-[85vh] overflow-y-auto">
                    <!-- Modal Header -->
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-bold tracking-wider uppercase bg-blue-50 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400 px-2 py-0.5 rounded">
                                {{ $this->selectedProduct->brand?->name }}
                            </span>
                            <span class="text-xs text-zinc-500">SKU: {{ $this->selectedProduct->sku ?? 'N/A' }}</span>
                        </div>
                        <flux:heading size="lg" class="text-zinc-900 dark:text-white font-bold leading-snug">
                            {{ $this->selectedProduct->name }}
                        </flux:heading>
                    </div>

                    <!-- Pricing Info -->
                    <div class="flex items-baseline gap-3 p-4 rounded-2xl bg-zinc-50 dark:bg-zinc-800/50">
                        <div class="flex flex-col">
                            <span class="text-xs text-zinc-500">Offer Price</span>
                            <span class="text-2xl font-black text-zinc-950 dark:text-white">₹{{ number_format($this->selectedProduct->sale_price, 2) }}</span>
                        </div>
                        @if($this->selectedProduct->mrp > $this->selectedProduct->sale_price)
                            <div class="flex flex-col">
                                <span class="text-xs text-zinc-400 line-through">₹{{ number_format($this->selectedProduct->mrp, 2) }}</span>
                                <span class="text-xs text-rose-500 font-bold">Save {{ $discount }}%</span>
                            </div>
                        @endif
                    </div>

                    <!-- Short Description -->
                    <div class="space-y-1">
                        <span class="text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Overview</span>
                        <p class="text-sm text-zinc-700 dark:text-zinc-300 leading-relaxed font-medium">
                            {{ $this->selectedProduct->short_description ?? 'A high quality enterprise technical hardware solution.' }}
                        </p>
                    </div>

                    <!-- Specifications Table -->
                    @if($this->selectedProduct->specifications->isNotEmpty())
                        <div class="space-y-2">
                            <span class="text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Technical Specifications</span>
                            <div class="border border-zinc-200 dark:border-zinc-800 rounded-xl overflow-hidden shadow-sm">
                                <table class="w-full text-xs text-left border-collapse">
                                    <tbody>
                                        @foreach($this->selectedProduct->specifications as $spec)
                                            <tr class="border-b border-zinc-100 dark:border-zinc-800 last:border-0 hover:bg-zinc-50 dark:hover:bg-zinc-800/30 transition">
                                                <td class="px-4 py-2.5 font-bold text-zinc-500 dark:text-zinc-400 bg-zinc-50/50 dark:bg-zinc-800/20 w-1/3 border-r border-zinc-100 dark:border-zinc-800">{{ $spec->key }}</td>
                                                <td class="px-4 py-2.5 font-medium text-zinc-900 dark:text-zinc-300">{{ $spec->value }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    <!-- Stock & Actions -->
                    <div class="flex items-center justify-between pt-4 border-t border-zinc-100 dark:border-zinc-800">
                        <div class="flex items-center gap-2">
                            <span class="flex size-2 rounded-full {{ $this->selectedProduct->stock > 0 ? 'bg-emerald-500 animate-pulse' : 'bg-rose-500' }}"></span>
                            <span class="text-xs font-semibold text-zinc-500">
                                {{ $this->selectedProduct->stock > 0 ? 'In Stock ('.$this->selectedProduct->stock.' units)' : 'Out of Stock' }}
                            </span>
                        </div>
                        <div class="flex gap-2">
                            <flux:button wire:click="closeProductModal" variant="ghost" size="sm" class="font-semibold text-zinc-500">Close</flux:button>
                            <flux:button wire:click="openEnquiry('{{ addslashes($this->selectedProduct->name) }}')" variant="filled" size="sm" class="bg-blue-600 hover:bg-blue-700 text-white border-0 font-medium">
                                Enquire Now
                            </flux:button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </flux:modal>

    <!-- General Enquiry Form Modal -->
    <flux:modal wire:model="showEnquiryModal" class="w-full max-w-md p-6 rounded-3xl bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg" class="text-zinc-900 dark:text-white font-bold">Request IT Quotation</flux:heading>
                <flux:text class="mt-1 text-zinc-500 text-sm">Send us your technical requirements and our experts will get back to you with custom corporate pricing.</flux:text>
            </div>

            <form wire:submit.prevent="submitEnquiry" class="space-y-4">
                <!-- Name -->
                <flux:field>
                    <flux:label>Contact Name</flux:label>
                    <flux:input wire:model.defer="enquiryName" placeholder="e.g. Gurpreet Singh" />
                    <flux:error name="enquiryName" />
                </flux:field>

                <!-- Email -->
                <flux:field>
                    <flux:label>Corporate Email</flux:label>
                    <flux:input type="email" wire:model.defer="enquiryEmail" placeholder="e.g. gurpreet@example.com" />
                    <flux:error name="enquiryEmail" />
                </flux:field>

                <!-- Phone -->
                <flux:field>
                    <flux:label>Phone Number</flux:label>
                    <flux:input wire:model.defer="enquiryPhone" placeholder="e.g. +91 98765 43210" />
                    <flux:error name="enquiryPhone" />
                </flux:field>

                <!-- Message -->
                <flux:field>
                    <flux:label>Requirement Message</flux:label>
                    <flux:textarea wire:model.defer="enquiryMessage" placeholder="Specify quantities, brand choices, cabling requirements, or model modifications..." rows="4" />
                    <flux:error name="enquiryMessage" />
                </flux:field>

                <div class="flex gap-2 pt-4 border-t border-zinc-100 dark:border-zinc-800">
                    <flux:spacer />
                    <flux:button wire:click="$set('showEnquiryModal', false)" variant="ghost" size="sm" class="font-semibold text-zinc-500">Cancel</flux:button>
                    <flux:button type="submit" variant="filled" size="sm" class="bg-blue-600 hover:bg-blue-700 text-white border-0 font-medium">Submit Request</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>
