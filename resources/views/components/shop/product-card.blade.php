@props(['product'])

@php
    $discount = $product->mrp > 0 ? round((($product->mrp - $product->sale_price) / $product->mrp) * 100) : 0;
    $categoryIcon = \App\Support\CategoryVisuals::icon($product->category?->name);
    [$gradientFrom, $gradientTo] = \App\Support\CategoryVisuals::gradient($product->category?->name, muted: true);

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

    $reviews = $product->reviews;
    $reviewCount = $reviews->count();
    $avgRating = $reviewCount > 0 ? round($reviews->avg('rating'), 1) : 0;
    $isWishlisted = \App\Support\WishlistManager::contains($product->id);
@endphp

<div class="flex flex-col bg-white border border-zinc-200/80 rounded-[2rem] p-3 shadow-sm hover:shadow-md transition-all duration-300 group">
    <!-- Image Card Container (Matching Reference Mobile Rounded Aesthetic) -->
    <div class="relative aspect-square rounded-[1.5rem] bg-zinc-100 flex items-center justify-center overflow-hidden">
        @if($imgUrl)
            <img src="{{ $imgUrl }}" alt="{{ $product->name }}" class="size-full object-cover group-hover:scale-105 transition-transform duration-300">
        @else
            <div class="absolute inset-0 bg-gradient-to-br {{ $gradientFrom }} {{ $gradientTo }} flex items-center justify-center text-white">
                <flux:icon icon="{{ $categoryIcon }}" class="size-10 text-white/80" />
            </div>
        @endif

        <!-- Discount Pill Badge Top-Left -->
        @if($discount > 0)
            <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-rose-50 text-rose-600 border border-rose-200 shadow-sm">
                -{{ $discount }}%
            </span>
        @endif

        <!-- Floating Wishlist Button Top-Right -->
        <button type="button" wire:click="toggleWishlist({{ $product->id }})" class="absolute top-3 right-3 p-2 rounded-full bg-white/90 backdrop-blur-md shadow-sm text-zinc-600 hover:text-rose-500 active:scale-95 transition cursor-pointer" title="Wishlist">
            <flux:icon icon="heart" class="size-4.5 {{ $isWishlisted ? 'fill-rose-500 text-rose-500' : '' }}" />
        </button>
    </div>

    <!-- Details Section -->
    <div class="p-2 pt-3 flex flex-col justify-between flex-1">
        <div>
            <!-- Category & Rating -->
            <div class="flex items-center justify-between text-xs mb-1">
                <span class="text-xs font-semibold text-zinc-500 truncate max-w-[120px]">{{ $product->brand?->name ?? 'Collection' }}</span>
                @if($reviewCount > 0)
                    <div class="flex items-center gap-1 bg-amber-50 text-amber-700 px-2 py-0.5 rounded-full text-[10px] font-bold">
                        <flux:icon icon="star" class="size-3 fill-amber-400 text-amber-400" />
                        <span>{{ $avgRating }}</span>
                    </div>
                @endif
            </div>

            <!-- Title -->
            <a href="{{ route('shop.product.details', $product->slug) }}" wire:navigate>
                <h3 class="text-sm font-bold text-zinc-900 line-clamp-1 group-hover:text-blue-600 transition-colors">
                    {{ $product->name }}
                </h3>
            </a>

            <!-- Subtext / Description -->
            <p class="text-[11px] text-zinc-400 line-clamp-1 mt-0.5">
                {{ $product->short_description ?? 'Premium quality item' }}
            </p>
        </div>

        <!-- Price & Action -->
        <div class="mt-3 flex items-center justify-between pt-2 border-t border-zinc-100">
            <div class="flex items-baseline gap-1.5">
                <span class="text-sm font-extrabold text-zinc-900">₹{{ number_format($product->sale_price, 0) }}</span>
                @if($product->mrp > $product->sale_price)
                    <span class="text-[10px] text-zinc-400 line-through">₹{{ number_format($product->mrp, 0) }}</span>
                @endif
            </div>

            <div class="flex items-center gap-1">
                <button type="button" wire:click="toggleComparison({{ $product->id }})" class="p-1.5 rounded-full text-zinc-400 hover:text-blue-600" title="Compare">
                    <flux:icon icon="scale" class="size-4 {{ in_array($product->id, session()->get('compared_product_ids', []), true) ? 'text-blue-600' : '' }}" />
                </button>
                <a href="{{ route('shop.product.details', $product->slug) }}" wire:navigate class="p-1.5 rounded-full bg-zinc-900 text-white hover:bg-zinc-800 transition">
                    <flux:icon icon="arrow-right" class="size-3.5" />
                </a>
            </div>
        </div>
    </div>
</div>
