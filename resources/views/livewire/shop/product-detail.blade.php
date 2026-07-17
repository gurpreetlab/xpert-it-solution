<main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
        <!-- Image Carousel Area -->
        <div class="space-y-4">
            <div class="aspect-square rounded-3xl bg-zinc-100 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 overflow-hidden flex items-center justify-center">
                <!-- Main Image -->
                <img src="{{ $product->primaryImage ? asset('storage/' . $product->primaryImage->path) : asset('storage/placeholder.png') }}" alt="{{ $product->name }}" class="object-cover size-full">
            </div>
            <!-- Thumbnails -->
            <div class="grid grid-cols-4 gap-4">
                @foreach($product->images as $image)
                    <img src="{{ $image->path }}" class="rounded-xl border border-zinc-200 dark:border-zinc-800 cursor-pointer hover:border-blue-500">
                @endforeach
            </div>
        </div>

        <!-- Product Info -->
        <div class="space-y-6">
            <span class="text-blue-600 font-bold uppercase tracking-wider text-xs">{{ $product->brand->name ?? 'Enterprise' }}</span>
            <h1 class="text-4xl font-extrabold text-zinc-950 dark:text-white">{{ $product->name }}</h1>

            @php
                $productDiscount = $product->mrp > 0 ? round((($product->mrp - $product->sale_price) / $product->mrp) * 100) : 0;
            @endphp
            @if($productDiscount > 0)
                <div class="px-2 w-fit py-0.5 rounded-md text-[14px] font-bold bg-rose-500 text-white shadow-sm">
                    {{ $productDiscount }}% OFF
                </div>
            @endif

            <div class="flex flex-row gap-4">
                @if($product->mrp > $product->sale_price)
                    <div class="text-3xl text-zinc-400 dark:text-zinc-500 line-through">₹{{ number_format($product->mrp, 2) }}</div>
                @endif
                <div class="text-3xl font-black text-zinc-950 dark:text-white">₹{{ number_format($product->sale_price, 2) }}</div>
            </div>

            <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed">{{ $product->description }}</p>

            <div class="flex gap-4">
                <flux:button wire:click="addToCart" variant="outline" class="flex-1">Add to Cart</flux:button>
                <flux:button wire:click="placeOrder" variant="filled" class="flex-1 bg-blue-600 hover:bg-blue-700">Place Order</flux:button>
            </div>
        </div>
    </div>

    <!-- Detailed Specifications -->
    <section class="mt-16">
        <h3 class="text-2xl font-bold mb-6 text-zinc-950 dark:text-white">Specifications</h3>
        <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 overflow-hidden">
            @foreach($product->specifications as $spec)
                <div class="grid grid-cols-3 p-4 border-b border-zinc-100 dark:border-zinc-800">
                    <span class="font-bold text-zinc-500">{{ $spec->key }}</span>
                    <span class="col-span-2 text-zinc-900 dark:text-zinc-300">{{ $spec->value }}</span>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Related Products -->
    <section class="mt-16">
        <h3 class="text-2xl font-bold mb-8 text-zinc-950 dark:text-white">Related Products</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($relatedProducts as $product)
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
                                <flux:button variant="ghost" size="sm" class="text-zinc-500 hover:text-zinc-900 dark:hover:text-white">
                                    View
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
    </section>
</main>
