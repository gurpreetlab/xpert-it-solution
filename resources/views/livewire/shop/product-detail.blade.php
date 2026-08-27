<div class="w-full">
    <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6">

        <!-- Breadcrumb Navigation -->
        <nav class="flex items-center gap-2 text-xs font-medium text-zinc-500 mb-6">
            <a href="{{ route('home') }}" class="hover:text-blue-600 transition">Home</a>
            <flux:icon icon="chevron-right" class="size-3 text-zinc-400" />
            <a href="{{ route('home') }}#categories" class="hover:text-blue-600 transition">
                {{ $product->category?->name ?? 'Category' }}
            </a>
            <flux:icon icon="chevron-right" class="size-3 text-zinc-400" />
            <span class="text-zinc-900 font-semibold truncate max-w-xs sm:max-w-md">{{ $product->name }}</span>
        </nav>

        <!-- Main Product Section -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-16">

            <!-- Left: Image Gallery Area (Cols 1-6) -->
            <div class="lg:col-span-6 space-y-4">
                @php
                $categoryIcon = 'shopping-bag';
                $gradientFrom = 'from-zinc-800';
                $gradientTo = 'to-zinc-950';

                if ($product->category?->name === 'Networking') {
                    $gradientFrom = 'from-blue-900'; $gradientTo = 'to-zinc-950'; $categoryIcon = 'wifi';
                } elseif ($product->category?->name === 'CCTV & Security') {
                    $gradientFrom = 'from-emerald-900'; $gradientTo = 'to-zinc-950'; $categoryIcon = 'video-camera';
                } elseif ($product->category?->name === 'Storage') {
                    $gradientFrom = 'from-purple-900'; $gradientTo = 'to-zinc-950'; $categoryIcon = 'circle-stack';
                } elseif ($product->category?->name === 'Computer Peripherals') {
                    $gradientFrom = 'from-amber-900'; $gradientTo = 'to-zinc-950'; $categoryIcon = 'computer-desktop';
                } elseif ($product->category?->name === 'Power & Accessories') {
                    $gradientFrom = 'from-orange-900'; $gradientTo = 'to-zinc-950'; $categoryIcon = 'bolt';
                } elseif ($product->category?->name === 'Printing') {
                    $gradientFrom = 'from-indigo-900'; $gradientTo = 'to-zinc-950'; $categoryIcon = 'printer';
                }

                $getImageUrl = function($path) {
                    if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) return $path;
                    if (str_starts_with($path, 'storage/')) return asset($path);
                    return asset('storage/' . $path);
                };

                $hasImages = $selectedImage || $product->images->isNotEmpty() || $product->primaryImage;
                @endphp

                <!-- Main Viewport Box (App-like Rounded-3xl Card) -->
                <div class="relative aspect-square sm:aspect-4/3 rounded-[2.5rem] bg-white border border-zinc-200/80 shadow-sm overflow-hidden flex items-center justify-center p-6 group">
                    @if($selectedImage)
                        <img src="{{ $getImageUrl($selectedImage) }}" alt="{{ $product->name }}" class="size-full object-contain group-hover:scale-105 transition-transform duration-500" />
                    @else
                        <div class="absolute inset-0 bg-gradient-to-br {{ $gradientFrom }} {{ $gradientTo }} flex items-center justify-center p-8 text-white">
                            <div class="relative z-10 p-8 rounded-3xl bg-white/10 border border-white/20 backdrop-blur-md shadow-2xl scale-125 transition-transform duration-500">
                                <flux:icon icon="{{ $categoryIcon }}" class="size-16 text-white" />
                            </div>
                        </div>
                    @endif

                    <!-- Stock & Wishlist Floating Badges -->
                    <div class="absolute top-4 left-4 right-4 flex items-center justify-between z-20">
                        @php
                            $discount = $product->mrp > 0 ? round((($product->mrp - $product->sale_price) / $product->mrp) * 100) : 0;
                        @endphp
                        @if($discount > 0)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold bg-rose-50 text-rose-600 border border-rose-200 shadow-sm">
                                -{{ $discount }}%
                            </span>
                        @else
                            <div></div>
                        @endif

                        <button type="button" wire:click="toggleWishlist" class="p-2.5 rounded-full bg-white/90 backdrop-blur-md shadow-sm text-zinc-600 hover:text-rose-500 active:scale-95 transition cursor-pointer">
                            <flux:icon icon="heart" class="size-5 {{ \App\Support\WishlistManager::contains($product->id) ? 'fill-rose-500 text-rose-500' : '' }}" />
                        </button>
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
                        <button type="button" wire:click="selectImage('{{ addslashes($img->path) }}')" class="relative aspect-square rounded-2xl border overflow-hidden transition-all duration-200 cursor-pointer bg-white p-1 {{ $isSelected ? 'border-zinc-900 ring-2 ring-zinc-900/20 scale-105' : 'border-zinc-200 hover:border-zinc-400' }}">
                            <img src="{{ $imgUrl }}" alt="Thumbnail" class="size-full object-contain rounded-xl">
                        </button>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Right: Product Information & Mobile Sticky Action Bar -->
            <div class="lg:col-span-6 space-y-6 flex flex-col justify-between">
                <div class="space-y-4">

                    <div class="flex items-center justify-between text-xs">
                        <span class="font-semibold text-zinc-500 uppercase tracking-wider">{{ $product->brand?->name ?? 'Collection' }}</span>
                        <span class="font-mono text-zinc-400">SKU: {{ $product->sku ?? 'N/A' }}</span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-zinc-950 leading-tight">
                        {{ $product->name }}
                    </h1>

                    <!-- Pricing Display -->
                    <div class="p-5 rounded-3xl bg-zinc-100/70 border border-zinc-200/80 flex items-center justify-between gap-4">
                        <div>
                            <span class="text-xs text-zinc-400 font-medium block">Price</span>
                            <div class="flex items-baseline gap-2">
                                <span class="text-3xl font-extrabold text-zinc-950">₹{{ number_format($product->sale_price, 0) }}</span>
                                @if($product->mrp > $product->sale_price)
                                    <span class="text-sm text-zinc-400 line-through">₹{{ number_format($product->mrp, 0) }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="px-3 py-1.5 rounded-full bg-white border border-zinc-200 text-xs font-bold shadow-xs {{ $product->stock > 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ $product->stock > 0 ? 'In Stock' : 'Out of Stock' }}
                        </div>
                    </div>

                    <p class="text-sm text-zinc-600 leading-relaxed font-normal">
                        {{ $product->short_description ?? 'Enterprise-grade device engineered for performance and reliability.' }}
                    </p>

                    <!-- Quantity Control -->
                    <div class="pt-2 flex items-center gap-4">
                        <span class="text-xs font-bold text-zinc-700 uppercase">Quantity:</span>
                        <div class="flex items-center border border-zinc-200 rounded-full bg-white p-1 shadow-xs">
                            <button type="button" wire:click="decrementQuantity" class="size-8 flex items-center justify-center rounded-full hover:bg-zinc-100 text-zinc-700 font-bold">-</button>
                            <span class="w-8 text-center text-sm font-bold text-zinc-900">{{ $quantity }}</span>
                            <button type="button" wire:click="incrementQuantity" class="size-8 flex items-center justify-center rounded-full hover:bg-zinc-100 text-zinc-700 font-bold">+</button>
                        </div>
                    </div>

                    <!-- Native Mobile Bottom Action Buttons (Fixed on Mobile Viewports like Screenshot) -->
                    <div class="fixed bottom-14 left-0 right-0 z-40 bg-white/90 backdrop-blur-md p-3 border-t border-zinc-200 md:static md:bg-transparent md:border-none md:p-0">
                        <div class="max-w-md mx-auto grid grid-cols-2 gap-3">
                            <flux:button wire:click="addToCart" variant="outline" class="w-full rounded-2xl py-3 border-zinc-300 font-bold cursor-pointer text-zinc-900">
                                Add to cart
                            </flux:button>
                            <button type="button" wire:click="addToCart" class="w-full py-3 bg-zinc-900 hover:bg-zinc-800 text-white font-bold rounded-2xl shadow-md cursor-pointer transition">
                                Buy Now
                            </button>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <!-- Technical Specifications & Detailed Description Section -->
        <section class="mb-16 space-y-12">

            <!-- Specifications Table -->
            @if($product->specifications->isNotEmpty())

            <h3 class="text-xl font-bold tracking-tight text-zinc-900 mb-6 flex items-center gap-2">
                <flux:icon icon="cog" class="size-5 text-blue-600" />
                Specifications
            </h3>

            <div class="columns-1 lg:columns-2 gap-6 mb-16">
                @foreach($product->specifications->groupBy('group_name') as $groupName => $specs)
                <div class="break-inside-avoid-column mb-6">
                    <div class="border border-zinc-200 rounded-2xl overflow-hidden shadow-xs">
                        <table class="w-full text-sm text-left border-collapse">
                            <tbody>
                                @if($groupName)
                                <tr class="bg-zinc-100 font-bold">
                                    <td colspan="2" class="px-6 py-3">
                                        {{ $groupName }}
                                    </td>
                                </tr>
                                @endif

                                @foreach($specs as $spec)
                                <tr class="border-b border-zinc-100 last:border-0 hover:bg-zinc-50/80 transition">
                                    <td class="px-6 py-3.5 text-zinc-500 bg-zinc-50/50 border-r border-zinc-100">
                                        {{ $spec->key }}
                                    </td>
                                    <td class="px-6 py-3.5 text-zinc-900 font-semibold">
                                        {{ $spec->value }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            <!-- Full Description Block -->
            @if($product->description)
            <div class="rounded-3xl border border-zinc-200 bg-white p-6 sm:p-8 shadow-sm">
                <h3 class="text-xl font-bold tracking-tight text-zinc-900 mb-4 flex items-center gap-2">
                    <flux:icon icon="book-open-text" class="size-5 text-blue-600" />
                    Detailed Product Information
                </h3>
                <div class="text-sm sm:text-base text-zinc-600 leading-relaxed space-y-4">
                    {!! $product->description !!}
                </div>
            </div>
            @endif

        </section>

    </main>
</div>
