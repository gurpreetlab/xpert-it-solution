@props(['product'])

<div class="flex flex-col bg-white border border-zinc-200 rounded-2xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 overflow-hidden group">

    {{-- Card Header & Image --}}
    <div class="relative aspect-video bg-zinc-100 flex items-center justify-center text-zinc-400 overflow-hidden">
        @if($product['primary_image'])
        <img src="{{ asset('storage/' . ($product['primary_image']['path'] ?? 'placeholder.png')) }}" alt="{{ $product['name'] }}" class="size-full object-cover group-hover:scale-105 transition-transform duration-300">
        @else
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#8080800a_1px,transparent_1px),linear-gradient(to_bottom,#8080800a_1px,transparent_1px)] bg-[size:14px_14px]"></div>
        <div class="relative z-10 p-3.5 rounded-xl bg-zinc-200/50 border border-zinc-200 backdrop-blur-md shadow-sm group-hover:scale-105 transition-transform duration-300">
            <flux:icon icon="photo" class="size-7 text-zinc-400" />
        </div>
        @endif

        @if($product['discount_percentage'] > 0)
        <span class="absolute top-3 right-3 inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-rose-500 text-white shadow-sm">
            {{ $product['discount_percentage'] }}% OFF
        </span>
        @endif
    </div>

    {{-- Card Content --}}
    <div class="flex-1 p-5 flex flex-col justify-between space-y-4">
        <div class="space-y-2">
            <div class="flex items-center justify-between text-xs">
                <span class="font-medium text-zinc-500">{{ $product['brand']['name'] ?? 'Unknown Brand' }}</span>

                @if($product['reviews_count'] > 0)
                <div class="flex items-center gap-1 text-amber-500 font-bold text-[11px]" title="{{ $product['average_rating'] }} average rating based on {{ $product['reviews_count'] }} reviews">
                    <flux:icon icon="star" class="size-3 fill-current" />
                    <span>{{ $product['average_rating'] }} <span class="text-zinc-400 font-normal">({{ $product['reviews_count'] }})</span></span>
                </div>
                @endif

                <span class="font-semibold {{ $product['stock'] > 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                    {{ $product['stock'] > 0 ? 'In Stock' : 'Out of Stock' }}
                </span>
            </div>

            <a href="{{ route('shop.product.details', $product['slug']) }}" wire:navigate>
                <h3 class="text-sm font-bold text-zinc-900 line-clamp-1 group-hover:text-rose-500 transition-colors">
                    {{ $product['name'] }}
                </h3>
            </a>

            <p class="text-xs text-zinc-500 line-clamp-2 leading-relaxed">
                {{ $product['short_description'] ?? 'High performance device.' }}
            </p>
        </div>

        {{-- Card Footer & Actions --}}
        <div class="pt-3 border-t border-zinc-100 flex items-center justify-between">
            <div class="flex flex-col">
                @if($product['mrp'] > $product['sale_price'])
                <span class="text-[10px] text-zinc-400 line-through">₹{{ number_format($product['mrp'], 2) }}</span>
                @endif
                <span class="text-base font-extrabold text-rose-500">₹{{ number_format($product['sale_price'], 2) }}</span>
            </div>

            <div class="flex gap-1 items-center">
                <button type="button" wire:click="toggleWishlist({{ $product['id'] }})" class="p-2 min-w-[44px] min-h-[44px] flex items-center justify-center text-zinc-400 hover:text-rose-500 cursor-pointer" title="Toggle Wishlist" aria-label="Toggle Wishlist">
                    <flux:icon icon="heart" class="size-5 {{ $product['is_in_wishlist'] ? 'fill-current text-rose-500' : '' }}" />
                </button>

                <div x-data>
                    <button
                        type="button"
                        @click.stop.prevent="$store.cart.addItem({{ $product['id'] }})"
                        class="p-2 min-w-[44px] min-h-[44px] flex items-center justify-center text-zinc-400 hover:text-rose-500 cursor-pointer"
                        title="Add to Cart"
                        aria-label="Add to Cart">
                        <flux:icon icon="shopping-bag" class="size-5" />
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>