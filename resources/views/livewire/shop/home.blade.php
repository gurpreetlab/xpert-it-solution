<div>
    <x-layouts.app>
        <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6 space-y-10">

            <!-- Search & Quick Category Shortcuts Bar -->
            <section class="bg-surface rounded-2xl p-5 border border-border shadow-2xs space-y-4">
                <div class="relative max-w-3xl mx-auto">
                    <form action="{{ route('shop.products') }}" method="GET" class="relative flex items-center">
                        <flux:icon icon="magnifying-glass" class="absolute left-4 size-5 text-zinc-400" />
                        <input
                            type="text"
                            name="search"
                            wire:model.live.debounce.300ms="search"
                            placeholder="Search Wi-Fi 6 routers, 1TB NVMe SSD, 8ch NVR, wireless keyboards, laser printers..."
                            class="w-full pl-12 pr-28 py-3 bg-surface-muted rounded-xl border border-border text-sm text-zinc-900 placeholder-zinc-400 focus:outline-hidden focus:ring-2 focus:ring-primary focus:bg-surface transition" />
                        <button type="submit" class="absolute right-2 px-4 py-2 rounded-lg bg-primary hover:bg-primary-hover text-white text-xs font-bold transition cursor-pointer shadow-2xs">
                            Search
                        </button>
                    </form>
                </div>

                <!-- Only Show Categories That Have Active Products -->
                @if(count($this->activeCategoriesWithProducts) > 0)
                <div class="flex items-center justify-center gap-2 overflow-x-auto pb-1 no-scrollbar text-xs font-medium">
                    <a
                        href="{{ route('shop.products') }}"
                        wire:navigate
                        class="px-4 py-1.5 rounded-full border border-border bg-surface-muted hover:bg-primary hover:text-white hover:border-primary text-zinc-700 transition cursor-pointer whitespace-nowrap font-bold">
                        All Categories
                    </a>

                    @foreach($this->activeCategoriesWithProducts as $category)
                        <a
                            href="{{ route('shop.products') }}?category={{ $category->id }}"
                            wire:navigate
                            class="px-4 py-1.5 rounded-full border border-border bg-surface-muted hover:bg-primary hover:text-white hover:border-primary text-zinc-700 transition cursor-pointer whitespace-nowrap font-semibold">
                            {{ $category->name }} ({{ $category->products_count }})
                        </a>
                    @endforeach
                </div>
                @endif
            </section>

            <!-- Light-Theme Product Offer Banner (Plix / Amazon / Flipkart style) -->
            <section class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2 bg-gradient-to-r from-blue-50 via-slate-50 to-indigo-50 border border-blue-100 text-zinc-900 rounded-2xl p-6 sm:p-8 flex flex-col justify-between relative overflow-hidden shadow-2xs">
                    <div class="relative z-10 space-y-3 max-w-lg">
                        <span class="inline-block px-3 py-1 rounded-md bg-primary/10 text-primary text-xs font-extrabold border border-primary/20">
                            Enterprise IT & Networking Solutions
                        </span>
                        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-zinc-900 leading-tight">
                            High-Speed Wi-Fi 6 Routers & Gigabit PoE Switches
                        </h1>
                        <p class="text-xs sm:text-sm text-zinc-600 leading-relaxed font-normal">
                            Upgrade your home or office infrastructure with 100% authentic hardware backed by official brand warranties.
                        </p>
                    </div>
                    <div class="relative z-10 pt-6 flex items-center gap-4">
                        <a href="{{ route('shop.products') }}" wire:navigate class="px-5 py-2.5 rounded-xl bg-primary hover:bg-primary-hover text-white text-xs font-bold transition shadow-xs flex items-center gap-1">
                            <span>Shop Enterprise Catalog</span>
                            <flux:icon icon="arrow-right" class="size-4" />
                        </a>
                        <span class="text-xs font-semibold text-emerald-700">✓ Free Express Delivery</span>
                    </div>
                </div>

                <div class="bg-surface rounded-2xl p-6 border border-border flex flex-col justify-between space-y-4 shadow-2xs">
                    <div class="space-y-2">
                        <span class="text-xs font-extrabold uppercase tracking-wider text-emerald-600">Storage Special</span>
                        <h2 class="text-base sm:text-lg font-bold text-zinc-900">NVMe PCIe Gen4 Solid State Drives</h2>
                        <p class="text-xs text-zinc-500">Read speeds up to 7,450 MB/s for high performance PC builds.</p>
                    </div>
                    <div class="pt-3 border-t border-border flex items-center justify-between">
                        <div>
                            <span class="text-[10px] text-zinc-400 block font-semibold">Starting From</span>
                            <span class="text-xl font-black text-zinc-950">₹3,499</span>
                        </div>
                        <a href="{{ route('shop.products') }}" wire:navigate class="px-4 py-2 rounded-xl bg-primary/10 hover:bg-primary hover:text-white text-primary text-xs font-bold transition">
                            Explore
                        </a>
                    </div>
                </div>
            </section>

            <!-- Product Shelf 1: Trending IT Hardware -->
            <x-product.carousel
                title="Trending IT Hardware"
                subtitle="Most viewed and purchased items by system integrators and IT buyers"
                viewAllUrl="{{ route('shop.products') }}"
                :products="$this->trendingProducts"
                carouselId="trending-hardware" />

            <!-- Product Shelf 2: Limited-Time Deals -->
            <x-product.carousel
                title="Limited-Time Offers & Deals"
                subtitle="Special discount prices on high-demand storage, routers, and IP cameras"
                viewAllUrl="{{ route('shop.products') }}"
                :products="$this->dealsOfDay"
                carouselId="daily-deals" />

            <!-- Product Shelf 3: Best Sellers -->
            <x-product.shelf
                title="Best Selling Electronics"
                subtitle="Top rated IT hardware with proven performance and reliability"
                viewAllUrl="{{ route('shop.products') }}"
                :products="$this->bestSellers"
                :columns="4" />

            <!-- Category Specific Shelves (Only show categories with products) -->
            @php $netProducts = $this->getCategoryProducts('Networking', 4); @endphp
            @if(count($netProducts) > 0)
                <x-product.shelf
                    title="Networking & Connectivity"
                    subtitle="Wi-Fi 6 routers, managed switches, access points, and SFP fiber modules"
                    viewAllUrl="{{ route('shop.products') }}"
                    :products="$netProducts"
                    :columns="4" />
            @endif

            @php $cctvProducts = $this->getCategoryProducts('CCTV', 4); @endphp
            @if(count($cctvProducts) > 0)
                <x-product.shelf
                    title="CCTV & Surveillance Systems"
                    subtitle="4K IP cameras, NVRs, surveillance hard drives, and PoE power supplies"
                    viewAllUrl="{{ route('shop.products') }}"
                    :products="$cctvProducts"
                    :columns="4" />
            @endif

            @php $storageProducts = $this->getCategoryProducts('Storage', 4); @endphp
            @if(count($storageProducts) > 0)
                <x-product.shelf
                    title="High-Speed Storage Solutions"
                    subtitle="NVMe M.2 SSDs, internal HDDs, external portable drives, and USB flash storage"
                    viewAllUrl="{{ route('shop.products') }}"
                    :products="$storageProducts"
                    :columns="4" />
            @endif

            @php $periProducts = $this->getCategoryProducts('Peripheral', 4); @endphp
            @if(count($periProducts) > 0)
                <x-product.shelf
                    title="Computer Peripherals & Office Gear"
                    subtitle="Mechanical keyboards, ergonomic mice, 4K webcams, and USB-C docking stations"
                    viewAllUrl="{{ route('shop.products') }}"
                    :products="$periProducts"
                    :columns="4" />
            @endif

            <!-- Popular Brands Ecosystem -->
            @if(count($this->popularBrands) > 0)
            <section class="py-4 space-y-4">
                <div class="flex items-end justify-between">
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-zinc-900">Popular Brands</h2>
                        <p class="text-xs sm:text-sm text-zinc-500 mt-0.5">100% authentic products backed by official brand warranties</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3">
                    @foreach($this->popularBrands as $brand)
                        <a
                            href="{{ route('shop.products') }}?brand={{ $brand->id }}"
                            wire:navigate
                            class="p-4 rounded-xl bg-surface border border-border hover:border-primary hover:shadow-xs transition flex flex-col items-center justify-center text-center group cursor-pointer">
                            <span class="font-extrabold text-sm text-zinc-900 group-hover:text-primary transition">{{ $brand->name }}</span>
                            <span class="text-[10px] text-zinc-400 mt-1 font-semibold">{{ $brand->products_count }} Products</span>
                        </a>
                    @endforeach
                </div>
            </section>
            @endif

            <x-shop.trust-banner />
        </main>
    </x-layouts.app>
</div>
