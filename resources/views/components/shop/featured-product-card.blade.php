@php
use App\Support\CategoryVisuals;
@endphp

@props(['product'])

@php
    $discount = $product->mrp > 0 ? round((($product->mrp - $product->sale_price) / $product->mrp) * 100) : 0;
    $categoryIcon = CategoryVisuals::icon($product->category?->name);
    [$gradientFrom, $gradientTo] = CategoryVisuals::gradient($product->category?->name);
@endphp

<div class="flex flex-col rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-md hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 overflow-hidden group">
    <div class="relative aspect-video bg-gradient-to-br {{ $gradientFrom }} {{ $gradientTo }} flex items-center justify-center p-6 text-white overflow-hidden">
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#80808012_1px,transparent_1px),linear-gradient(to_bottom,#80808012_1px,transparent_1px)] bg-[size:16px_16px]"></div>
        <div class="absolute size-24 rounded-full bg-white/10 blur-xl"></div>

        <div class="relative z-10 p-5 rounded-2xl bg-white/10 border border-white/20 backdrop-blur-md shadow-2xl group-hover:scale-110 transition-transform duration-300">
            <flux:icon icon="{{ $categoryIcon }}" class="size-10 text-white" />
        </div>

        @if($discount > 0)
            <span class="absolute top-4 right-4 inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-rose-500 text-white shadow-sm">
                {{ $discount }}% OFF
            </span>
        @endif

        <span class="absolute bottom-4 left-4 text-xs font-semibold uppercase tracking-wider text-white/50 bg-white/10 px-2 py-0.5 rounded backdrop-blur-xs">
            {{ $product->brand?->name ?? 'Brand' }}
        </span>
    </div>

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
