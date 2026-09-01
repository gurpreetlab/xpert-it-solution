@props(['categories', 'brands', 'selectedCategoryId', 'selectedBrandId', 'search', 'totalProductsCount'])

<div class="rounded-2xl border border-border bg-surface p-5 shadow-2xs space-y-6">
    <!-- Search Field -->
    <div>
        <flux:field>
            <flux:label class="text-[11px] font-bold uppercase tracking-wider text-zinc-400">Search Hardware</flux:label>
            <div class="relative mt-1">
                <flux:input wire:model.live.debounce.300ms="search" placeholder="Search routers, SSDs..." icon="magnifying-glass" />
            </div>
        </flux:field>
    </div>

    <flux:separator />

    <!-- Price Range Filter -->
    <div>
        <span class="block text-[11px] font-bold uppercase tracking-wider text-zinc-400 mb-2">Price Range</span>
        <div class="space-y-1">
            @foreach([
                '' => 'All Prices',
                'under_5000' => 'Under ₹5,000',
                '5000_15000' => '₹5,000 - ₹15,000',
                '15000_50000' => '₹15,000 - ₹50,000',
                'above_50000' => 'Above ₹50,000',
            ] as $key => $label)
                <button
                    type="button"
                    wire:click="$set('priceRange', '{{ $key }}')"
                    class="w-full text-left px-2.5 py-1.5 rounded-lg text-xs flex justify-between items-center transition cursor-pointer {{ ($priceRange ?? '') === $key ? 'bg-primary/10 text-primary font-bold' : 'text-zinc-600 hover:bg-surface-muted' }}">
                    <span>{{ $label }}</span>
                </button>
            @endforeach
        </div>
    </div>

    <flux:separator />

    <!-- Stock Status Filter -->
    <div>
        <label class="flex items-center gap-2 cursor-pointer text-xs font-semibold text-zinc-700">
            <input type="checkbox" wire:model.live="inStockOnly" class="rounded-md border-border text-primary focus:ring-primary size-4">
            <span>In Stock Only</span>
        </label>
    </div>

    <flux:separator />

    <!-- Categories Filter -->
    <div>
        <span class="block text-[11px] font-bold uppercase tracking-wider text-zinc-400 mb-2">Category</span>
        <div class="space-y-1 max-h-48 overflow-y-auto pr-1 no-scrollbar">
            <button
                type="button"
                wire:click="$set('selectedCategoryId', '')"
                class="w-full text-left px-2.5 py-1.5 rounded-lg text-xs flex justify-between items-center transition cursor-pointer {{ $selectedCategoryId === '' ? 'bg-primary/10 text-primary font-bold' : 'text-zinc-600 hover:bg-surface-muted' }}">
                <span>All Categories</span>
                <span class="text-[10px] px-1.5 py-0.5 rounded-md bg-surface-muted text-zinc-500 font-semibold">{{ $totalProductsCount }}</span>
            </button>
            @foreach($categories as $category)
                <button
                    type="button"
                    wire:click="$set('selectedCategoryId', '{{ $category->id }}')"
                    class="w-full text-left px-2.5 py-1.5 rounded-lg text-xs flex justify-between items-center transition cursor-pointer {{ (string)$selectedCategoryId === (string)$category->id ? 'bg-primary/10 text-primary font-bold' : 'text-zinc-600 hover:bg-surface-muted' }}">
                    <span class="truncate pr-2">{{ $category->name }}</span>
                    <span class="text-[10px] px-1.5 py-0.5 rounded-md bg-surface-muted text-zinc-500 font-semibold">{{ $category->products_count }}</span>
                </button>
            @endforeach
        </div>
    </div>

    <flux:separator />

    <!-- Brands Filter -->
    <div>
        <span class="block text-[11px] font-bold uppercase tracking-wider text-zinc-400 mb-2">Brand</span>
        <div class="space-y-1 max-h-48 overflow-y-auto pr-1 no-scrollbar">
            <button
                type="button"
                wire:click="$set('selectedBrandId', '')"
                class="w-full text-left px-2.5 py-1.5 rounded-lg text-xs flex justify-between items-center transition cursor-pointer {{ $selectedBrandId === '' ? 'bg-primary/10 text-primary font-bold' : 'text-zinc-600 hover:bg-surface-muted' }}">
                <span>All Brands</span>
                <span class="text-[10px] px-1.5 py-0.5 rounded-md bg-surface-muted text-zinc-500 font-semibold">{{ $totalProductsCount }}</span>
            </button>
            @foreach($brands as $brand)
                @if($brand->products_count > 0)
                    <button
                        type="button"
                        wire:click="$set('selectedBrandId', '{{ $brand->id }}')"
                        class="w-full text-left px-2.5 py-1.5 rounded-lg text-xs flex justify-between items-center transition cursor-pointer {{ (string)$selectedBrandId === (string)$brand->id ? 'bg-primary/10 text-primary font-bold' : 'text-zinc-600 hover:bg-surface-muted' }}">
                        <span class="truncate pr-2">{{ $brand->name }}</span>
                        <span class="text-[10px] px-1.5 py-0.5 rounded-md bg-surface-muted text-zinc-500 font-semibold">{{ $brand->products_count }}</span>
                    </button>
                @endif
            @endforeach
        </div>
    </div>

    @if($search !== '' || $selectedCategoryId !== '' || $selectedBrandId !== '' || ($priceRange ?? '') !== '' || ($inStockOnly ?? false))
        <flux:separator />
        <button
            type="button"
            wire:click="clearFilters"
            class="w-full py-2 rounded-xl border border-border text-xs font-semibold text-zinc-600 hover:bg-surface-muted transition cursor-pointer">
            Reset Filters
        </button>
    @endif
</div>
