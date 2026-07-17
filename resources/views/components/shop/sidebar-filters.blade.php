@props(['categories', 'brands', 'selectedCategoryId', 'selectedBrandId', 'search', 'totalProductsCount'])

<div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6 shadow-sm space-y-6">
    <div>
        <flux:field>
            <flux:label class="text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Search Products</flux:label>
            <div class="relative mt-1">
                <flux:input wire:model.live.debounce.300ms="search" placeholder="Type query..." icon="magnifying-glass" />
            </div>
        </flux:field>
    </div>

    <flux:separator />

    <div>
        <span class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-2">Filter by Category</span>
        <div class="space-y-1 max-h-48 overflow-y-auto pr-2">
            <button
                wire:click="$set('selectedCategoryId', '')"
                class="w-full text-left px-2 py-1.5 rounded-lg text-sm flex justify-between items-center transition {{ $selectedCategoryId === '' ? 'bg-blue-50 dark:bg-blue-950/20 text-blue-600 dark:text-blue-400 font-semibold' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-800' }}"
            >
                <span>All Categories</span>
                <span class="text-xs px-1.5 py-0.5 rounded-md bg-zinc-100 dark:bg-zinc-800 text-zinc-500">{{ $totalProductsCount }}</span>
            </button>
            @foreach($categories as $category)
                <button
                    wire:click="$set('selectedCategoryId', '{{ $category->id }}')"
                    class="w-full text-left px-2 py-1.5 rounded-lg text-sm flex justify-between items-center transition {{ $selectedCategoryId == $category->id ? 'bg-blue-50 dark:bg-blue-950/20 text-blue-600 dark:text-blue-400 font-semibold' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-800' }}"
                >
                    <span class="truncate pr-2">{{ $category->name }}</span>
                    <span class="text-xs px-1.5 py-0.5 rounded-md bg-zinc-100 dark:bg-zinc-800 text-zinc-500">{{ $category->products_count }}</span>
                </button>
            @endforeach
        </div>
    </div>

    <flux:separator />

    <div>
        <span class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-2">Filter by Brand</span>
        <div class="space-y-1 max-h-48 overflow-y-auto pr-2">
            <button
                wire:click="$set('selectedBrandId', '')"
                class="w-full text-left px-2 py-1.5 rounded-lg text-sm flex justify-between items-center transition {{ $selectedBrandId === '' ? 'bg-blue-50 dark:bg-blue-950/20 text-blue-600 dark:text-blue-400 font-semibold' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-800' }}"
            >
                <span>All Brands</span>
                <span class="text-xs px-1.5 py-0.5 rounded-md bg-zinc-100 dark:bg-zinc-800 text-zinc-500">{{ $totalProductsCount }}</span>
            </button>
            @foreach($brands as $brand)
                @if($brand->products_count > 0)
                    <button
                        wire:click="$set('selectedBrandId', '{{ $brand->id }}')"
                        class="w-full text-left px-2 py-1.5 rounded-lg text-sm flex justify-between items-center transition {{ $selectedBrandId == $brand->id ? 'bg-blue-50 dark:bg-blue-950/20 text-blue-600 dark:text-blue-400 font-semibold' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-800' }}"
                    >
                        <span class="truncate pr-2">{{ $brand->name }}</span>
                        <span class="text-xs px-1.5 py-0.5 rounded-md bg-zinc-100 dark:bg-zinc-800 text-zinc-500">{{ $brand->products_count }}</span>
                    </button>
                @endif
            @endforeach
        </div>
    </div>

    @if($search !== '' || $selectedCategoryId !== '' || $selectedBrandId !== '')
        <flux:separator />
        <flux:button wire:click="clearFilters" variant="ghost" size="sm" class="w-full text-zinc-600 hover:bg-zinc-100 dark:text-zinc-400 dark:hover:bg-zinc-800 font-semibold">
            Reset Filters
        </flux:button>
    @endif
</div>
