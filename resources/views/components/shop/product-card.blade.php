@props(['product'])

@php
    $discount = $product->mrp > 0 ? round((($product->mrp - $product->sale_price) / $product->mrp) * 100) : 0;
    $categoryIcon = \App\Support\CategoryVisuals::icon($product->category?->name);
    [$gradientFrom, $gradientTo] = \App\Support\CategoryVisuals::gradient($product->category?->name, muted: true);
@endphp

<div class="flex flex-col bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 overflow-hidden group">
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
                <a href="{{ route('shop.product.details', $product->slug) }}" wire:navigate>
                    <flux:button variant="ghost" size="sm" class="cursor-pointer text-zinc-500 hover:text-zinc-900 dark:hover:text-white">
                        View
                    </flux:button>
                </a>
            </div>
        </div>
    </div>
</div>
