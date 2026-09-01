@props([
    'title',
    'subtitle' => null,
    'viewAllUrl' => null,
    'products' => [],
    'carouselId' => 'carousel-' . Str::random(8),
])

@if(count($products) > 0)
<section class="py-4 space-y-4">
    <div class="flex items-end justify-between px-1">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-zinc-900">{{ $title }}</h2>
            @if($subtitle)
                <p class="text-xs sm:text-sm text-zinc-500 mt-0.5">{{ $subtitle }}</p>
            @endif
        </div>

        <div class="flex items-center gap-3">
            @if($viewAllUrl)
                <a href="{{ $viewAllUrl }}" wire:navigate class="text-xs sm:text-sm font-semibold text-primary hover:text-primary-hover transition flex items-center gap-1">
                    <span>View All</span>
                    <flux:icon icon="chevron-right" class="size-4" />
                </a>
            @endif

            <div class="hidden sm:flex items-center gap-1">
                <button
                    type="button"
                    onclick="document.getElementById('{{ $carouselId }}').scrollBy({ left: -320, behavior: 'smooth' })"
                    class="p-2 rounded-full border border-border bg-surface text-zinc-600 hover:bg-surface-muted hover:text-zinc-900 transition cursor-pointer shadow-xs"
                    aria-label="Previous Products">
                    <flux:icon icon="chevron-left" class="size-4" />
                </button>
                <button
                    type="button"
                    onclick="document.getElementById('{{ $carouselId }}').scrollBy({ left: 320, behavior: 'smooth' })"
                    class="p-2 rounded-full border border-border bg-surface text-zinc-600 hover:bg-surface-muted hover:text-zinc-900 transition cursor-pointer shadow-xs"
                    aria-label="Next Products">
                    <flux:icon icon="chevron-right" class="size-4" />
                </button>
            </div>
        </div>
    </div>

    <!-- Scrollable Horizontal Container -->
    <div
        id="{{ $carouselId }}"
        class="flex gap-4 sm:gap-6 overflow-x-auto snap-x snap-mandatory scroll-smooth pb-4 pt-1 px-1 no-scrollbar scrollbar-none"
        style="scrollbar-width: none; -ms-overflow-style: none;">
        @foreach($products as $product)
            <div class="w-[260px] sm:w-[290px] shrink-0 snap-start">
                <x-shop.product-card :product="$product" wire:key="carousel-p-{{ $carouselId }}-{{ $product->id }}" />
            </div>
        @endforeach
    </div>
</section>
@endif
