@php
use App\Support\CategoryVisuals;
@endphp

@props(['categories', 'selectedCategoryId'])

<section id="categories" class="mb-16 scroll-mt-20">
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-8">
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-zinc-900 dark:text-white">Shop by IT Category</h2>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Select a category to filter products dynamically.</p>
        </div>
        @if($selectedCategoryId)
            <flux:button wire:click="$set('selectedCategoryId', '')" variant="ghost" size="sm" class="mt-4 md:mt-0 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-zinc-900">
                Show All Categories
            </flux:button>
        @endif
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        @foreach($categories as $cat)
            <div
                wire:click="$set('selectedCategoryId', '{{ $selectedCategoryId == $cat->id ? '' : $cat->id }}')"
                class="cursor-pointer group flex flex-col items-center text-center p-5 rounded-2xl border transition-all duration-300 hover:shadow-lg hover:-translate-y-1 {{ CategoryVisuals::pillClasses($cat->name) }} {{ $selectedCategoryId == $cat->id ? 'ring-2 ring-blue-600 dark:ring-blue-500 scale-105' : '' }}"
            >
                <div class="p-3.5 rounded-xl bg-white dark:bg-zinc-800 shadow-sm mb-3 group-hover:scale-110 transition-transform duration-200">
                    <flux:icon icon="{{ CategoryVisuals::icon($cat->name) }}" class="size-6 text-current" />
                </div>
                <span class="text-sm font-semibold tracking-tight">{{ $cat->name }}</span>
                <span class="text-xs text-zinc-500 dark:text-zinc-400 mt-1 font-medium">{{ $cat->products_count }} Products</span>
            </div>
        @endforeach
    </div>
</section>
