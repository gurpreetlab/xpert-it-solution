@props([
    'title',
    'subtitle' => null,
    'viewAllUrl' => null,
    'products' => [],
    'columns' => 4,
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

        @if($viewAllUrl)
            <a href="{{ $viewAllUrl }}" wire:navigate class="text-xs sm:text-sm font-semibold text-primary hover:text-primary-hover transition flex items-center gap-1">
                <span>View All</span>
                <flux:icon icon="chevron-right" class="size-4" />
            </a>
        @endif
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-{{ $columns }} gap-4 sm:gap-6">
        @foreach($products as $product)
            <x-shop.product-card :product="$product" wire:key="shelf-p-{{ $product->id }}" />
        @endforeach
    </div>
</section>
@endif
