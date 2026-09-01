<div class="w-full">
    <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6 space-y-12">

        <!-- Breadcrumb Navigation -->
        <nav class="flex items-center gap-2 text-xs font-medium text-zinc-500">
            <a href="{{ route('home') }}" class="hover:text-primary transition" wire:navigate>Home</a>
            <flux:icon icon="chevron-right" class="size-3 text-zinc-400" />
            <a href="{{ route('shop.products') }}" class="hover:text-primary transition" wire:navigate>
                {{ $product->category?->name ?? 'Hardware' }}
            </a>
            <flux:icon icon="chevron-right" class="size-3 text-zinc-400" />
            <span class="text-zinc-900 font-semibold truncate max-w-xs sm:max-w-md">{{ $product->name }}</span>
        </nav>

        <!-- Main Product Hero Section -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">

            <!-- Gallery Area (Cols 1-6) -->
            <div class="lg:col-span-6 space-y-4">
                @php
                    $getImageUrl = function($path) {
                        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                            return $path;
                        }
                        if (str_starts_with($path, 'storage/')) {
                            return asset($path);
                        }
                        return asset('storage/' . $path);
                    };

                    $discount = ($product->mrp > 0 && $product->mrp > $product->sale_price)
                        ? round((($product->mrp - $product->sale_price) / $product->mrp) * 100)
                        : 0;
                @endphp

                <!-- Main Image Viewport -->
                <div class="relative aspect-square sm:aspect-4/3 rounded-2xl bg-surface border border-border overflow-hidden flex items-center justify-center p-6 group">
                    @if($selectedImage)
                        <img
                            src="{{ $getImageUrl($selectedImage) }}"
                            alt="{{ $product->name }}"
                            class="size-full object-contain group-hover:scale-105 transition-transform duration-300" />
                    @else
                        <div class="flex flex-col items-center justify-center text-zinc-400 p-8 text-center space-y-2">
                            <flux:icon icon="cpu-chip" class="size-16 text-primary/40" />
                            <span class="text-xs font-semibold uppercase tracking-wider text-zinc-500">
                                {{ $product->brand?->name ?? 'Enterprise Hardware' }}
                            </span>
                        </div>
                    @endif

                    <!-- Discount Badge -->
                    @if($discount > 0)
                        <span class="absolute top-4 right-4 inline-flex items-center px-3 py-1 rounded-lg text-xs font-extrabold bg-emerald-600 text-white shadow-xs">
                            {{ $discount }}% OFF
                        </span>
                    @endif
                </div>

                <!-- Thumbnails Gallery -->
                @if($product->images->isNotEmpty())
                <div class="grid grid-cols-5 gap-3 pt-1">
                    @foreach($product->images as $img)
                        @php
                            $imgUrl = $getImageUrl($img->path);
                            $isSelected = $selectedImage === $img->path;
                        @endphp
                        <button
                            type="button"
                            wire:click="selectImage('{{ addslashes($img->path) }}')"
                            class="relative aspect-square rounded-xl border p-1 transition cursor-pointer bg-surface {{ $isSelected ? 'border-primary ring-2 ring-primary/30 scale-105' : 'border-border hover:border-zinc-400' }}">
                            <img src="{{ $imgUrl }}" alt="Thumbnail" class="size-full object-contain rounded-lg">
                        </button>
                    @endforeach
                </div>
                @endif

                <!-- Guarantee Badges -->
                <div class="grid grid-cols-3 gap-3 pt-4 border-t border-border text-center">
                    <div class="p-3 rounded-xl bg-surface-muted border border-border">
                        <flux:icon icon="shield-check" class="size-5 text-primary mx-auto mb-1" />
                        <span class="block text-[11px] font-bold text-zinc-900">100% Authentic</span>
                        <span class="text-[10px] text-zinc-500">Official Brand Warranty</span>
                    </div>
                    <div class="p-3 rounded-xl bg-surface-muted border border-border">
                        <flux:icon icon="truck" class="size-5 text-emerald-600 mx-auto mb-1" />
                        <span class="block text-[11px] font-bold text-zinc-900">Express Delivery</span>
                        <span class="text-[10px] text-zinc-500">Fast Shipping</span>
                    </div>
                    <div class="p-3 rounded-xl bg-surface-muted border border-border">
                        <flux:icon icon="arrow-path" class="size-5 text-amber-600 mx-auto mb-1" />
                        <span class="block text-[11px] font-bold text-zinc-900">Easy Returns</span>
                        <span class="text-[10px] text-zinc-500">7-Day Replacement</span>
                    </div>
                </div>
            </div>

            <!-- Info & Actions Area (Cols 7-12) -->
            <div class="lg:col-span-6 space-y-6 flex flex-col justify-between">
                <div class="space-y-4">
                    <!-- Category & Brand Headers -->
                    <div class="flex flex-wrap items-center gap-2 text-xs">
                        <span class="px-2.5 py-0.5 rounded-md bg-primary/10 text-primary font-bold">
                            {{ $product->category?->name ?? 'IT Hardware' }}
                        </span>
                        @if($product->brand)
                            <span class="px-2.5 py-0.5 rounded-md bg-surface-muted text-zinc-700 font-semibold border border-border">
                                {{ $product->brand->name }}
                            </span>
                        @endif
                        <span class="text-zinc-400 font-mono text-[11px] ml-auto">SKU: {{ $product->sku ?? 'N/A' }}</span>
                    </div>

                    <!-- Title -->
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-zinc-900 tracking-tight leading-snug">
                        {{ $product->name }}
                    </h1>

                    <!-- Rating & Reviews -->
                    @php
                        $reviews = $product->reviews;
                        $totalReviews = count($reviews);
                        $averageRating = $totalReviews > 0 ? round($reviews->avg('rating'), 1) : null;
                    @endphp
                    <div class="flex items-center gap-2 text-xs">
                        @if($totalReviews > 0)
                            <div class="flex items-center gap-1 text-amber-500 font-bold">
                                <flux:icon icon="star" class="size-4 fill-current" />
                                <span>{{ $averageRating }}</span>
                            </div>
                            <span class="text-zinc-400">•</span>
                            <a href="#reviews" class="text-zinc-500 hover:text-primary font-medium">{{ $totalReviews }} {{ Str::plural('Verified Review', $totalReviews) }}</a>
                        @else
                            <a href="#reviews" class="text-zinc-400 hover:text-primary font-medium">No reviews yet</a>
                        @endif
                    </div>

                    <!-- Pricing Box -->
                    <div class="p-5 rounded-2xl bg-surface-muted border border-border space-y-2">
                        <div class="text-xs text-zinc-500 font-medium">Selling Price (Inclusive of Taxes)</div>
                        <div class="flex items-baseline gap-3">
                            <span class="text-3xl font-black text-zinc-950">₹{{ number_format($product->sale_price) }}</span>
                            @if(($product->mrp ?? 0) > $product->sale_price)
                                <span class="text-base text-zinc-400 line-through">₹{{ number_format($product->mrp) }}</span>
                                <span class="text-xs font-bold text-emerald-600">Save ₹{{ number_format($product->mrp - $product->sale_price) }}</span>
                            @endif
                        </div>

                        <div class="pt-2 flex items-center justify-between text-xs">
                            <span class="font-semibold {{ ($product->stock ?? 1) > 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ ($product->stock ?? 1) > 0 ? '✓ In Stock' : 'Out of Stock' }}
                            </span>
                            <span class="text-zinc-500">GST Invoice Available</span>
                        </div>
                    </div>

                    @if($product->short_description || $product->description)
                        <!-- Short Specs / Highlights -->
                        <div class="space-y-2 pt-2">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-zinc-400">Highlights</h3>
                            <p class="text-xs sm:text-sm text-zinc-600 leading-relaxed">
                                {{ $product->short_description ?? Str::limit(strip_tags($product->description), 200) }}
                            </p>
                        </div>
                    @endif

                    <!-- Quantity & Primary CTA Actions -->
                    <div class="space-y-4 pt-4 border-t border-border">
                        <div class="flex items-center gap-4">
                            <span class="text-xs font-bold uppercase tracking-wider text-zinc-400">Quantity</span>
                            <div class="flex items-center border border-border rounded-xl bg-surface p-1 shadow-2xs">
                                <button type="button" wire:click="decrementQuantity" class="size-7 flex items-center justify-center rounded-lg hover:bg-surface-muted text-zinc-700 font-bold cursor-pointer">
                                    -
                                </button>
                                <span class="w-10 text-center text-sm font-bold text-zinc-900">{{ $quantity }}</span>
                                <button type="button" wire:click="incrementQuantity" class="size-7 flex items-center justify-center rounded-lg hover:bg-surface-muted text-zinc-700 font-bold cursor-pointer">
                                    +
                                </button>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <button
                                type="button"
                                wire:click="addToCart"
                                class="w-full py-3 rounded-xl bg-primary hover:bg-primary-hover text-white font-bold text-sm transition flex items-center justify-center gap-2 cursor-pointer shadow-xs">
                                <flux:icon icon="shopping-cart" class="size-4" />
                                <span>Add to Cart</span>
                            </button>

                            <a
                                href="{{ route('shop.cart') }}"
                                wire:click="addToCart"
                                wire:navigate
                                class="w-full py-3 rounded-xl bg-zinc-900 hover:bg-zinc-800 text-white font-bold text-sm transition flex items-center justify-center gap-2 cursor-pointer shadow-xs">
                                <span>Buy Now</span>
                            </a>
                        </div>

                        <div class="flex items-center justify-start text-xs pt-2">
                            <button
                                type="button"
                                wire:click="toggleWishlist"
                                class="text-zinc-600 hover:text-rose-500 font-semibold flex items-center gap-1.5 cursor-pointer">
                                <flux:icon icon="heart" class="size-4 {{ \App\Support\WishlistManager::contains($product->id) ? 'fill-current text-rose-500' : '' }}" />
                                <span>{{ \App\Support\WishlistManager::contains($product->id) ? 'Wishlisted' : 'Add to Wishlist' }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Technical Specifications Matrix Table -->
        <section class="space-y-6 pt-6 border-t border-border">
            <h2 class="text-xl font-bold tracking-tight text-zinc-900 flex items-center gap-2">
                <flux:icon icon="cog" class="size-5 text-primary" />
                <span>Technical Specifications</span>
            </h2>

            @if($product->specifications->isNotEmpty())
                <div class="rounded-2xl border border-border overflow-hidden bg-surface shadow-2xs">
                    <table class="w-full text-xs sm:text-sm text-left border-collapse">
                        <thead>
                            <tr class="bg-surface-muted border-b border-border text-zinc-500 uppercase tracking-wider text-[11px]">
                                <th class="px-6 py-3 font-bold w-1/3">Specification</th>
                                <th class="px-6 py-3 font-bold">Details</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            @foreach($product->specifications as $spec)
                                <tr class="hover:bg-surface-muted/50 transition">
                                    <td class="px-6 py-3.5 font-medium text-zinc-500 bg-surface-muted/30">
                                        {{ $spec->specification_name ?? $spec->key }}
                                    </td>
                                    <td class="px-6 py-3.5 font-bold text-zinc-900">
                                        {{ $spec->specification_value ?? $spec->value }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-6 rounded-2xl border border-border bg-surface text-xs text-zinc-500">
                    Standard manufacturer specification data applied for model {{ $product->sku ?? $product->name }}.
                </div>
            @endif
        </section>

        <!-- Compatibility & What's in the Box -->
        <section class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="p-6 rounded-2xl border border-border bg-surface space-y-3">
                <h3 class="text-base font-bold text-zinc-900 flex items-center gap-2">
                    <flux:icon icon="check-circle" class="size-5 text-emerald-600" />
                    <span>Compatibility</span>
                </h3>
                <ul class="text-xs sm:text-sm text-zinc-600 space-y-1.5">
                    <li class="flex items-center gap-2">✓ Windows 11, Windows 10, macOS, Linux</li>
                    <li class="flex items-center gap-2">✓ Standard RJ45 Gigabit & Fiber Connections</li>
                    <li class="flex items-center gap-2">✓ ONVIF, PoE, and Universal Power Standards</li>
                </ul>
            </div>

            <div class="p-6 rounded-2xl border border-border bg-surface space-y-3">
                <h3 class="text-base font-bold text-zinc-900 flex items-center gap-2">
                    <flux:icon icon="archive-box" class="size-5 text-primary" />
                    <span>What's in the Box</span>
                </h3>
                <ul class="text-xs sm:text-sm text-zinc-600 space-y-1.5">
                    <li class="flex items-center gap-2">• {{ $product->name }} Main Unit</li>
                    <li class="flex items-center gap-2">• Power Adapter & Cable</li>
                    <li class="flex items-center gap-2">• Quick Installation Guide & Warranty Card</li>
                </ul>
            </div>
        </section>

        <!-- Frequently Bought Together Bundle -->
        @if($relatedProducts->isNotEmpty())
        <section class="p-6 rounded-2xl border border-border bg-surface-muted space-y-4">
            <h3 class="text-lg font-bold text-zinc-900">Frequently Bought Together</h3>

            <div class="flex flex-col sm:flex-row items-center gap-4">
                <div class="flex items-center gap-2 overflow-x-auto">
                    <div class="p-3 bg-surface rounded-xl border border-border text-xs font-bold text-zinc-900 shrink-0">
                        {{ Str::limit($product->name, 25) }}
                    </div>
                    <span class="text-zinc-400 font-bold">+</span>
                    @foreach($relatedProducts->take(2) as $relItem)
                        <div class="p-3 bg-surface rounded-xl border border-border text-xs font-bold text-zinc-900 shrink-0">
                            {{ Str::limit($relItem->name, 25) }}
                        </div>
                        @if(!$loop->last) <span class="text-zinc-400 font-bold">+</span> @endif
                    @endforeach
                </div>

                <div class="ml-auto flex items-center gap-4 pt-2 sm:pt-0">
                    <div>
                        <span class="text-xs text-zinc-500 block">Bundle Price</span>
                        <span class="text-lg font-extrabold text-zinc-950">₹{{ number_format($product->sale_price + $relatedProducts->take(2)->sum('sale_price')) }}</span>
                    </div>

                    <button
                        type="button"
                        wire:click="addToCart"
                        class="px-4 py-2.5 rounded-xl bg-primary text-white text-xs font-bold hover:bg-primary-hover transition cursor-pointer">
                        Add All 3 to Cart
                    </button>
                </div>
            </div>
        </section>
        @endif

        <!-- Customer Reviews Section -->
        <section id="reviews" class="space-y-6 pt-6 border-t border-border">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold tracking-tight text-zinc-900">Customer Reviews</h2>
                    <p class="text-xs text-zinc-500 mt-0.5">Verified feedback from corporate IT buyers</p>
                </div>
            </div>

            <!-- Reviews List -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($product->reviews as $rev)
                    <div class="p-4 rounded-xl border border-border bg-surface space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-xs text-zinc-900">{{ $rev->user->name ?? 'Verified Buyer' }}</span>
                            <div class="flex items-center gap-1 text-amber-500 text-xs font-bold">
                                <flux:icon icon="star" class="size-3 fill-current" />
                                <span>{{ $rev->rating }}.0</span>
                            </div>
                        </div>
                        <p class="text-xs text-zinc-600 leading-relaxed">{{ $rev->comment }}</p>
                    </div>
                @empty
                    <div class="col-span-full p-6 text-center rounded-xl border border-dashed border-border bg-surface text-xs text-zinc-500">
                        No reviews yet. Be the first to review this product!
                    </div>
                @endforelse
            </div>
        </section>

        <!-- Related Products Shelf -->
        @if($relatedProducts->isNotEmpty())
            <x-product.shelf
                title="Similar IT Hardware"
                subtitle="Other options in {{ $product->category?->name ?? 'this category' }}"
                viewAllUrl="{{ route('shop.products') }}"
                :products="$relatedProducts"
                :columns="4" />
        @endif

    </main>
</div>
