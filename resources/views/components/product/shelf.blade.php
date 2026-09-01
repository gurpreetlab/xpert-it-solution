@props([
    'title',
    'subtitle' => null,
    'viewAllUrl' => null,
    'products' => [],
    'columns' => 4,
])

@php
    $gridCols = [
        2 => 'grid-cols-1 sm:grid-cols-2',
        3 => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3',
        4 => 'grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4',
        5 => 'grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5',
    ][$columns] ?? 'grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4';
@endphp

<section class="space-y-4">
    <div class="flex items-end justify-between">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-zinc-900">{{ $title }}</h2>
            @if($subtitle)
                <p class="text-xs sm:text-sm text-zinc-500 mt-0.5">{{ $subtitle }}</p>
            @endif
        </div>

        @if($viewAllUrl)
            <a href="{{ $viewAllUrl }}" wire:navigate class="text-xs font-bold text-primary hover:text-primary-hover flex items-center gap-1 transition">
                <span>View All</span>
                <flux:icon icon="arrow-right" class="size-3.5" />
            </a>
        @endif
    </div>

    @if(count($products) > 0)
        <div class="grid {{ $gridCols }} gap-4">
            @foreach($products as $product)
                <x-product.card :product="$product" />
            @endforeach
        </div>
    @else
        <x-ui.empty-state title="No products available" description="Check back soon for new additions to this collection." />
    @endif
</section>
