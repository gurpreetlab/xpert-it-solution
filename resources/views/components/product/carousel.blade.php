@props([
    'title',
    'subtitle' => null,
    'viewAllUrl' => null,
    'products' => [],
    'carouselId' => 'carousel-' . uniqid(),
])

<section class="space-y-4">
    <div class="flex items-end justify-between">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-zinc-900">{{ $title }}</h2>
            @if($subtitle)
                <p class="text-xs sm:text-sm text-zinc-500 mt-0.5">{{ $subtitle }}</p>
            @endif
        </div>

        <div class="flex items-center gap-2">
            @if($viewAllUrl)
                <a href="{{ $viewAllUrl }}" wire:navigate class="text-xs font-bold text-primary hover:text-primary-hover flex items-center gap-1 transition mr-2">
                    <span>View All</span>
                    <flux:icon icon="arrow-right" class="size-3.5" />
                </a>
            @endif

            <button
                type="button"
                onclick="document.getElementById('{{ $carouselId }}').scrollBy({ left: -300, behavior: 'smooth' })"
                class="size-8 rounded-xl bg-surface border border-border flex items-center justify-center text-zinc-600 hover:text-primary hover:border-primary transition cursor-pointer shadow-2xs">
                <flux:icon icon="chevron-left" class="size-4" />
            </button>
            <button
                type="button"
                onclick="document.getElementById('{{ $carouselId }}').scrollBy({ left: 300, behavior: 'smooth' })"
                class="size-8 rounded-xl bg-surface border border-border flex items-center justify-center text-zinc-600 hover:text-primary hover:border-primary transition cursor-pointer shadow-2xs">
                <flux:icon icon="chevron-right" class="size-4" />
            </button>
        </div>
    </div>

    @if(count($products) > 0)
        <div
            id="{{ $carouselId }}"
            class="flex items-stretch gap-4 overflow-x-auto pb-3 pt-1 no-scrollbar scroll-smooth">
            @foreach($products as $product)
                <div class="w-64 sm:w-72 shrink-0">
                    <x-product.card :product="$product" />
                </div>
            @endforeach
        </div>
    @else
        <x-ui.empty-state title="No products found" description="Check back soon for upcoming product offers." />
    @endif
</section>
