<div class="w-full">
    <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">

        <!-- Breadcrumb Navigation -->
        <nav class="flex items-center gap-2 text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-8">
            <a href="{{ route('home') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">Home</a>
            <flux:icon icon="chevron-right" class="size-3 text-zinc-400" />
            <a href="{{ route('home') }}#categories" class="hover:text-blue-600 dark:hover:text-blue-400 transition">
                {{ $product->category?->name ?? 'Category' }}
            </a>
            <flux:icon icon="chevron-right" class="size-3 text-zinc-400" />
            <span class="text-zinc-900 dark:text-white font-semibold truncate max-w-xs sm:max-w-md">{{ $product->name }}</span>
        </nav>

        <!-- Main Product Section -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 mb-16">

            <!-- Left: Image Gallery Area (Cols 1-6) -->
            <div class="lg:col-span-6 space-y-4">
                @php
                    $categoryIcon = 'shopping-bag';
                    $gradientFrom = 'from-zinc-800';
                    $gradientTo = 'to-zinc-950';

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

                    // Helper to get image URL
                    $getImageUrl = function($path) {
                        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                            return $path;
                        }
                        if (str_starts_with($path, 'storage/')) {
                            return asset($path);
                        }
                        return asset('storage/' . $path);
                    };

                    $hasImages = $selectedImage || $product->images->isNotEmpty() || $product->primaryImage;
                @endphp

                <!-- Main Viewport Box -->
                <div class="relative aspect-4/3 rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-xl overflow-hidden flex items-center justify-center p-6 group">
                    @if($selectedImage)
                        <img
                            src="{{ $getImageUrl($selectedImage) }}"
                            alt="{{ $product->name }}"
                            class="size-full object-contain group-hover:scale-105 transition-transform duration-500"
                        />
                    @else
                        <!-- High-End Gradient Placeholder if product image does not exist -->
                        <div class="absolute inset-0 bg-gradient-to-br {{ $gradientFrom }} {{ $gradientTo }} flex items-center justify-center p-8 text-white">
                            <!-- Overlay Grid -->
                            <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff08_1px,transparent_1px),linear-gradient(to_bottom,#ffffff08_1px,transparent_1px)] bg-[size:20px_20px]"></div>
                            <div class="absolute size-40 rounded-full bg-blue-500/10 blur-3xl pointer-events-none"></div>

                            <div class="relative z-10 p-8 rounded-3xl bg-white/10 border border-white/20 backdrop-blur-md shadow-2xl scale-125 group-hover:scale-135 transition-transform duration-500">
                                <flux:icon icon="{{ $categoryIcon }}" class="size-16 text-white" />
                            </div>

                            <span class="absolute bottom-6 left-6 text-xs font-semibold uppercase tracking-wider text-white/60 bg-white/10 px-3 py-1 rounded-full backdrop-blur-xs">
                                {{ $product->brand?->name ?? 'Enterprise Hardware' }}
                            </span>
                        </div>
                    @endif

                    <!-- Stock & Discount Badges over Main Viewport -->
                    <div class="absolute top-4 right-4 flex flex-col gap-2 items-end z-20">
                        @php
                            $discount = $product->mrp > 0 ? round((($product->mrp - $product->sale_price) / $product->mrp) * 100) : 0;
                        @endphp
                        @if($discount > 0)
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-extrabold bg-rose-500 text-white shadow-md">
                                {{ $discount }}% OFF
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Thumbnails Gallery -->
                @if($product->images->isNotEmpty())
                    <div class="grid grid-cols-5 gap-3 pt-2">
                        @foreach($product->images as $img)
                            @php
                                $imgUrl = $getImageUrl($img->path);
                                $isSelected = $selectedImage === $img->path;
                            @endphp
                            <button
                                type="button"
                                wire:click="selectImage('{{ addslashes($img->path) }}')"
                                class="relative aspect-square rounded-xl border overflow-hidden transition-all duration-200 cursor-pointer bg-white dark:bg-zinc-900 p-1 {{ $isSelected ? 'border-blue-600 ring-2 ring-blue-600/50 scale-105' : 'border-zinc-200 dark:border-zinc-800 hover:border-zinc-400 dark:hover:border-zinc-600' }}"
                            >
                                <img src="{{ $imgUrl }}" alt="Thumbnail" class="size-full object-contain rounded-lg">
                            </button>
                        @endforeach
                    </div>
                @endif

                <!-- Guarantee / Trust Icons Under Gallery -->
                <div class="grid grid-cols-3 gap-4 pt-6 border-t border-zinc-200 dark:border-zinc-800 text-center">
                    <div class="p-3 rounded-2xl bg-zinc-100/70 dark:bg-zinc-900/70 border border-zinc-200/50 dark:border-zinc-800/50">
                        <flux:icon icon="shield-check" class="size-5 text-blue-600 dark:text-blue-400 mx-auto mb-1" />
                        <span class="block text-[11px] font-bold text-zinc-900 dark:text-white">100% Genuine</span>
                        <span class="text-[10px] text-zinc-500">Official Brand Warranty</span>
                    </div>
                    <div class="p-3 rounded-2xl bg-zinc-100/70 dark:bg-zinc-900/70 border border-zinc-200/50 dark:border-zinc-800/50">
                        <flux:icon icon="bolt" class="size-5 text-emerald-600 dark:text-emerald-400 mx-auto mb-1" />
                        <span class="block text-[11px] font-bold text-zinc-900 dark:text-white">Fast Dispatch</span>
                        <span class="text-[10px] text-zinc-500">Express Freight Shipping</span>
                    </div>
                    <div class="p-3 rounded-2xl bg-zinc-100/70 dark:bg-zinc-900/70 border border-zinc-200/50 dark:border-zinc-800/50">
                        <flux:icon icon="circle-stack" class="size-5 text-purple-600 dark:text-purple-400 mx-auto mb-1" />
                        <span class="block text-[11px] font-bold text-zinc-900 dark:text-white">Bulk Discount</span>
                        <span class="text-[10px] text-zinc-500">Corporate Quotes</span>
                    </div>
                </div>
            </div>

            <!-- Right: Product Information & CTAs (Cols 7-12) -->
            <div class="lg:col-span-6 space-y-6 flex flex-col justify-between">
                <div class="space-y-6">

                    <!-- Header badges -->
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-900/30">
                            <flux:icon icon="{{ $categoryIcon }}" class="size-3.5" />
                            {{ $product->category?->name ?? 'Hardware' }}
                        </span>
                        @if($product->brand)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300">
                                {{ $product->brand->name }}
                            </span>
                        @endif
                        <span class="text-xs text-zinc-400 font-medium ml-auto">
                            SKU: <span class="font-mono text-zinc-600 dark:text-zinc-300">{{ $product->sku ?? 'N/A' }}</span>
                        </span>
                    </div>

                    <!-- Title -->
                    <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-zinc-950 dark:text-white leading-tight">
                        {{ $product->name }}
                    </h1>

                    <!-- Pricing Box -->
                    <div class="p-6 rounded-3xl bg-zinc-100/80 dark:bg-zinc-900/80 border border-zinc-200/80 dark:border-zinc-800/80 backdrop-blur-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <div class="text-xs text-zinc-500 dark:text-zinc-400 font-medium mb-1">Corporate Offer Price</div>
                            <div class="flex items-baseline gap-3">
                                <span class="text-3xl sm:text-4xl font-black tracking-tight text-zinc-950 dark:text-white">
                                    ₹{{ number_format($product->sale_price, 2) }}
                                </span>
                                @if($product->mrp > $product->sale_price)
                                    <span class="text-lg text-zinc-400 line-through">
                                        ₹{{ number_format($product->mrp, 2) }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Stock Badge -->
                        <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 w-fit">
                            <span class="size-2.5 rounded-full {{ $product->stock > 0 ? 'bg-emerald-500 animate-pulse' : 'bg-rose-500' }}"></span>
                            <span class="text-xs font-bold {{ $product->stock > 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                {{ $product->stock > 0 ? 'In Stock ('.$product->stock.' available)' : 'Out of Stock' }}
                            </span>
                        </div>
                    </div>

                    <!-- Overview / Short Description -->
                    <div class="space-y-2">
                        <h3 class="text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Overview</h3>
                        <p class="text-sm sm:text-base text-zinc-600 dark:text-zinc-300 leading-relaxed font-normal">
                            {{ $product->short_description ?? 'Enterprise-grade hardware device designed for maximum reliability and seamless performance.' }}
                        </p>
                    </div>

                    <!-- Quantity Control & Enquire CTAs -->
                    <div class="space-y-4 pt-4 border-t border-zinc-200 dark:border-zinc-800">
                        <div class="flex items-center gap-4">
                            <span class="text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Quantity</span>
                            <div class="flex items-center border border-zinc-200 dark:border-zinc-800 rounded-xl bg-white dark:bg-zinc-900 p-1 shadow-sm">
                                <button type="button" wire:click="decrementQuantity" class="size-6 flex items-center justify-center rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 text-zinc-600 dark:text-zinc-300 font-bold transition">
                                    -
                                </button>
                                <span class="w-10 text-center text-sm font-bold text-zinc-900 dark:text-white">{{ $quantity }}</span>
                                <button type="button" wire:click="incrementQuantity" class="size-6 flex items-center justify-center rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 text-zinc-600 dark:text-zinc-300 font-bold transition">
                                    +
                                </button>
                            </div>
                        </div>

                        <!-- Buttons Row -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <flux:button icon="shopping-bag" class="cursor-pointer">
                                Place Order
                            </flux:button>

                            <flux:button wire:click="addToCart" icon="shopping-cart" class="cursor-pointer">
                                Add to Cart
                            </flux:button>
                        </div>
                    </div>

                </div>

                <!-- HSN & Tax Note -->
                @if($product->hsn_code)
                    <div class="text-xs text-zinc-500 dark:text-zinc-400 pt-4 border-t border-zinc-200 dark:border-zinc-800 flex items-center justify-between">
                        <span>HSN Code: <strong class="text-zinc-700 dark:text-zinc-300 font-mono">{{ $product->hsn_code }}</strong></span>
                        <span>Applicable GST & Tax Included</span>
                    </div>
                @endif

            </div>

        </div>

        <!-- Technical Specifications & Detailed Description Section -->
        <section class="mb-16 space-y-12">

            <!-- Specifications Table -->
            @if($product->specifications->isNotEmpty())
                <div class="rounded-3xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6 sm:p-8 shadow-sm">
                    <h3 class="text-xl font-bold tracking-tight text-zinc-900 dark:text-white mb-6 flex items-center gap-2">
                        <flux:icon icon="cog" class="size-5 text-blue-600" />
                        Technical Specifications
                    </h3>

                    <div class="border border-zinc-200 dark:border-zinc-800 rounded-2xl overflow-hidden shadow-xs">
                        <table class="w-full text-sm text-left border-collapse">
                            <tbody>
                                @foreach($product->specifications as $spec)
                                    <tr class="border-b border-zinc-100 dark:border-zinc-800 last:border-0 hover:bg-zinc-50/80 dark:hover:bg-zinc-800/40 transition">
                                        <td class="px-6 py-3.5 font-bold text-zinc-500 dark:text-zinc-400 bg-zinc-50/50 dark:bg-zinc-800/20 w-1/3 sm:w-1/4 border-r border-zinc-100 dark:border-zinc-800">
                                            {{ $spec->key }}
                                        </td>
                                        <td class="px-6 py-3.5 font-medium text-zinc-900 dark:text-zinc-200">
                                            {{ $spec->value }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Full Description Block -->
            @if($product->description)
                <div class="rounded-3xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6 sm:p-8 shadow-sm">
                    <h3 class="text-xl font-bold tracking-tight text-zinc-900 dark:text-white mb-4 flex items-center gap-2">
                        <flux:icon icon="book-open-text" class="size-5 text-blue-600" />
                        Detailed Product Information
                    </h3>
                    <div class="text-sm sm:text-base text-zinc-600 dark:text-zinc-300 leading-relaxed space-y-4">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>
            @endif

        </section>

        <!-- Related Products Section (Matching Homepage Card Layout) -->
        @if($relatedProducts->isNotEmpty())
            <section class="border-t border-zinc-200 dark:border-zinc-800 pt-12">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">Related IT Hardware</h2>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">Other devices in {{ $product->category?->name ?? 'this category' }}</p>
                    </div>
                    <a href="{{ route('home') }}#products" class="text-xs font-semibold text-blue-600 dark:text-blue-400 hover:underline">View All Products &rarr;</a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($relatedProducts as $relProduct)
                        @php
                            $relDiscount = $relProduct->mrp > 0 ? round((($relProduct->mrp - $relProduct->sale_price) / $relProduct->mrp) * 100) : 0;

                            $relGradientFrom = 'from-zinc-700';
                            $relGradientTo = 'to-zinc-900';
                            $relCategoryIcon = 'shopping-bag';

                            if ($relProduct->category?->name === 'Networking') {
                                $relGradientFrom = 'from-blue-800';
                                $relGradientTo = 'to-zinc-900';
                                $relCategoryIcon = 'wifi';
                            } elseif ($relProduct->category?->name === 'CCTV & Security') {
                                $relGradientFrom = 'from-emerald-800';
                                $relGradientTo = 'to-zinc-900';
                                $relCategoryIcon = 'video-camera';
                            } elseif ($relProduct->category?->name === 'Storage') {
                                $relGradientFrom = 'from-purple-800';
                                $relGradientTo = 'to-zinc-900';
                                $relCategoryIcon = 'circle-stack';
                            } elseif ($relProduct->category?->name === 'Computer Peripherals') {
                                $relGradientFrom = 'from-amber-800';
                                $relGradientTo = 'to-zinc-900';
                                $relCategoryIcon = 'computer-desktop';
                            } elseif ($relProduct->category?->name === 'Power & Accessories') {
                                $relGradientFrom = 'from-orange-800';
                                $relGradientTo = 'to-zinc-900';
                                $relCategoryIcon = 'bolt';
                            } elseif ($relProduct->category?->name === 'Printing') {
                                $relGradientFrom = 'from-indigo-800';
                                $relGradientTo = 'to-zinc-900';
                                $relCategoryIcon = 'printer';
                            }

                            $relImg = $relProduct->primaryImage?->path ?? ($relProduct->images->first()?->path ?? null);
                        @endphp

                        <div class="flex flex-col bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 overflow-hidden group">
                            <!-- Image / Gradient preview -->
                            <div class="relative aspect-video bg-gradient-to-br {{ $relGradientFrom }} {{ $relGradientTo }} flex items-center justify-center p-6 text-white overflow-hidden">
                                @if($relImg)
                                    <img src="{{ $getImageUrl($relImg) }}" alt="{{ $relProduct->name }}" class="size-full object-cover group-hover:scale-105 transition-transform duration-300" />
                                @else
                                    <div class="absolute inset-0 bg-[linear-gradient(to_right,#8080800a_1px,transparent_1px),linear-gradient(to_bottom,#8080800a_1px,transparent_1px)] bg-[size:14px_14px]"></div>
                                    <div class="relative z-10 p-3.5 rounded-xl bg-white/10 border border-white/10 backdrop-blur-md shadow-lg group-hover:scale-105 transition-transform duration-300">
                                        <flux:icon icon="{{ $relCategoryIcon }}" class="size-7 text-white" />
                                    </div>
                                @endif

                                @if($relDiscount > 0)
                                    <span class="absolute top-3 right-3 inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-rose-500 text-white shadow-sm">
                                        {{ $relDiscount }}% OFF
                                    </span>
                                @endif
                            </div>

                            <!-- Body -->
                            <div class="flex-1 p-5 flex flex-col justify-between space-y-4">
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="font-medium text-zinc-500 dark:text-zinc-400">{{ $relProduct->brand?->name }}</span>
                                        <span class="font-semibold {{ $relProduct->stock > 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                            {{ $relProduct->stock > 0 ? 'In Stock' : 'Out of Stock' }}
                                        </span>
                                    </div>
                                    <a href="{{ route('shop.product.details', $relProduct->slug) }}" wire:navigate>
                                        <h3 class="text-sm font-bold text-zinc-900 dark:text-white line-clamp-1 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                            {{ $relProduct->name }}
                                        </h3>
                                    </a>
                                </div>

                                <div class="pt-3 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-between">
                                    <div class="flex flex-col">
                                        @if($relProduct->mrp > $relProduct->sale_price)
                                            <span class="text-[10px] text-zinc-400 dark:text-zinc-500 line-through">₹{{ number_format($relProduct->mrp, 2) }}</span>
                                        @endif
                                        <span class="text-base font-extrabold text-zinc-950 dark:text-white">₹{{ number_format($relProduct->sale_price, 2) }}</span>
                                    </div>

                                    <flux:button href="{{ route('shop.product.details', $relProduct->slug) }}" wire:navigate variant="ghost" size="sm" class="text-zinc-500 hover:text-zinc-900 dark:hover:text-white">
                                        View
                                    </flux:button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

    </main>
</div>
