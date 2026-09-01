@props(['product', 'badge' => null])

@php
    $mrp = $product->mrp ?? 0;
    $salePrice = $product->sale_price ?? 0;
    $discount = ($mrp > 0 && $mrp > $salePrice) ? round((($mrp - $salePrice) / $mrp) * 100) : 0;

    $categoryIcon = \App\Support\CategoryVisuals::icon($product->category?->name);

    $img = $product->primaryImage?->path ?? ($product->images->first()?->path ?? null);
    $imgUrl = null;
    if ($img) {
        if (str_starts_with($img, 'http://') || str_starts_with($img, 'https://')) {
            $imgUrl = $img;
        } elseif (str_starts_with($img, 'storage/')) {
            $imgUrl = asset($img);
        } else {
            $imgUrl = asset('storage/' . $img);
        }
    }

    $reviews = $product->reviews ?? collect();
    $reviewCount = count($reviews);
    $avgRating = $reviewCount > 0 ? round($reviews->avg('rating'), 1) : 0;

    $isWishlisted = \App\Support\WishlistManager::contains($product->id);

    $specs = [];
    if ($product->relationLoaded('specifications') && $product->specifications->isNotEmpty()) {
        $specs = $product->specifications->take(2)->pluck('specification_value', 'specification_name')->toArray();
    }
@endphp

<div class="flex flex-col h-full bg-surface border border-border rounded-2xl shadow-2xs hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 overflow-hidden group">
    <!-- Top Image Container -->
    <div class="relative aspect-square sm:aspect-4/3 bg-surface-muted flex items-center justify-center overflow-hidden p-4">
        @if($imgUrl)
            <img src="{{ $imgUrl }}" alt="{{ $product->name }}" loading="lazy" class="size-full object-contain group-hover:scale-105 transition-transform duration-300">
        @else
            <div class="relative z-10 p-3 rounded-xl bg-surface border border-border shadow-xs group-hover:scale-105 transition-transform duration-300">
                <flux:icon icon="{{ $categoryIcon }}" class="size-8 text-primary" />
            </div>
        @endif

        <!-- Badges -->
        <div class="absolute top-2.5 left-2.5 flex flex-col gap-1 items-start z-10">
            @if($badge)
                <x-ui.badge variant="primary">{{ $badge }}</x-ui.badge>
            @elseif($product->is_featured)
                <x-ui.badge variant="warning">Popular</x-ui.badge>
            @endif

            @if($discount > 0)
                <x-ui.badge variant="success">{{ $discount }}% OFF</x-ui.badge>
            @endif
        </div>

        <!-- Wishlist Action Button -->
        <div class="absolute top-2.5 right-2.5 z-10">
            <button
                type="button"
                wire:click="toggleWishlist({{ $product->id }})"
                class="size-8 rounded-full bg-surface/90 backdrop-blur-xs border border-border flex items-center justify-center text-zinc-400 hover:text-rose-500 transition cursor-pointer shadow-xs"
                title="Wishlist"
                aria-label="Wishlist">
                <flux:icon icon="heart" class="size-4 {{ $isWishlisted ? 'fill-current text-rose-500' : '' }}" />
            </button>
        </div>
    </div>

    <!-- Product Info Content -->
    <div class="flex-1 p-4 flex flex-col justify-between space-y-3">
        <div class="space-y-1.5">
            <div class="flex items-center justify-between text-xs">
                <span class="font-semibold text-zinc-400 uppercase tracking-wider text-[10px]">{{ $product->brand?->name ?? 'IT Product' }}</span>
                @if($reviewCount > 0)
                    <div class="flex items-center gap-1 text-amber-500 font-bold text-[11px]">
                        <flux:icon icon="star" class="size-3 fill-current" />
                        <span>{{ $avgRating }} <span class="text-zinc-400 font-normal text-[10px]">({{ $reviewCount }})</span></span>
                    </div>
                @endif
            </div>

            <a href="{{ route('shop.product.details', $product->slug) }}" wire:navigate class="block">
                <h3 class="text-xs sm:text-sm font-bold text-zinc-900 line-clamp-2 leading-tight group-hover:text-primary transition-colors">
                    {{ $product->name }}
                </h3>
            </a>

            @if(!empty($specs))
                <div class="flex flex-wrap gap-1 pt-1">
                    @foreach($specs as $specName => $specVal)
                        <span class="inline-block px-1.5 py-0.5 rounded-sm bg-surface-muted text-zinc-600 text-[10px] font-medium border border-border">
                            {{ $specName }}: {{ $specVal }}
                        </span>
                    @endforeach
                </div>
            @else
                <p class="text-[11px] text-zinc-500 line-clamp-1 leading-snug">
                    {{ $product->short_description ?? ($product->category?->name ?? 'Genuine IT Hardware') }}
                </p>
            @endif
        </div>

        <!-- Pricing & Stock Footer -->
        <div class="pt-2 border-t border-border flex flex-col space-y-2">
            <div class="flex items-baseline justify-between">
                <div class="flex items-baseline gap-1.5">
                    <span class="text-sm sm:text-base font-extrabold text-zinc-950">₹{{ number_format($salePrice) }}</span>
                    @if($mrp > $salePrice)
                        <span class="text-[11px] text-zinc-400 line-through">₹{{ number_format($mrp) }}</span>
                    @endif
                </div>
                <span class="text-[10px] font-semibold {{ ($product->stock ?? 1) > 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                    {{ ($product->stock ?? 1) > 0 ? 'In Stock' : 'Out of Stock' }}
                </span>
            </div>

            <div class="flex items-center justify-between text-[10px] text-zinc-500">
                <span class="flex items-center gap-1">
                    <flux:icon icon="truck" class="size-3 text-zinc-400" />
                    <span>Free Delivery</span>
                </span>

                <a href="{{ route('shop.product.details', $product->slug) }}" wire:navigate class="font-semibold text-primary hover:underline">
                    View Product →
                </a>
            </div>
        </div>
    </div>
</div>
