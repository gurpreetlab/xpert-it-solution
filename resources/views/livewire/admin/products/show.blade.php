<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-2">
            <flux:button href="{{ route('dashboard.products.index') }}" wire:navigate variant="ghost" size="sm" icon="arrow-left" />
            <flux:heading size="xl" level="1">{{ $product->name }}</flux:heading>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <flux:button variant="ghost" wire:click="toggleFeatured" wire:loading.attr="disabled" wire:target="toggleFeatured">
                {{ $product->is_featured ? 'Unfeature' : 'Mark Featured' }}
            </flux:button>
            <flux:button variant="ghost" wire:click="toggleActive" wire:loading.attr="disabled" wire:target="toggleActive">
                {{ $product->is_active ? 'Deactivate' : 'Activate' }}
            </flux:button>

            <flux:button href="{{ route('dashboard.products.edit', $product) }}" wire:navigate>
                Edit
            </flux:button>

            <flux:modal.trigger name="delete-product" wire:click="confirmDelete({{ $product->id }})">
                <flux:button variant="danger" class="cursor-pointer">
                    Delete
                </flux:button>
            </flux:modal.trigger>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="mt-2 flex flex-wrap items-center gap-2">
        <flux:badge size="sm" color="{{ $product->is_active ? 'emerald' : 'zinc' }}">
            {{ $product->is_active ? 'Active' : 'Inactive' }}
        </flux:badge>
        <flux:badge size="sm" color="zinc">{{ $product->category->name }}</flux:badge>
        @if ($product->brand)
            <flux:badge size="sm" color="zinc">{{ $product->brand->name }}</flux:badge>
        @endif
    </div>

    <!-- Main content -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Left column -->
        <div class="space-y-6 lg:col-span-2">

            <!-- Images -->
            <flux:card class="bg-(--callout-background)">
                <flux:heading size="lg" class="mb-3">Images</flux:heading>
                @if($product->images->isNotEmpty())
                    <div class="grid grid-cols-4 gap-2 sm:grid-cols-6">
                        @foreach($product->images as $image)
                            <div class="aspect-square w-full overflow-hidden ring-1 ring-zinc-200 dark:ring-zinc-700 rounded">
                                <img src='{{ asset("storage/{$image->path}" ) }}' alt="{{ $product->name }}" class="h-full w-full object-cover"/>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="mt-4 flex flex-col items-center justify-center gap-2 rounded-lg border border-dashed border-zinc-300 py-10 dark:border-zinc-600">
                        <flux:icon name="photo" class="size-8 text-zinc-400" />
                        <flux:text class="text-zinc-500">No images uploaded for this product.</flux:text>
                    </div>
                @endif
            </div>

            <!-- Description -->
            <flux:card class="bg-(--callout-background)">
                <flux:heading size="lg">Short Description</flux:heading>
                <flux:text class="mt-3">{{ $product->short_description ?? 'No short description added.' }}</flux:text>

                <flux:separator class="my-4" />

                <flux:heading size="lg">Description</flux:heading>
                <flux:text class="mt-3 whitespace-pre-line">{{ $product->description ?? 'No description added.' }}</flux:text>
            </flux:card>

            <!-- Specifications -->
            <flux:card class="bg-(--callout-background)">
                <flux:heading size="lg">Specifications</flux:heading>

                @forelse ($product->specifications->groupBy(fn ($spec) => $spec->group_name ?: 'Other') as $groupName => $specs)
                    <div class="mt-4">
                        <flux:heading size="sm" class="text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-2">
                            {{ $groupName }}
                        </flux:heading>
                        <div class="divide-y divide-zinc-100 dark:divide-zinc-700">
                            @foreach ($specs as $spec)
                                <div class="flex items-start justify-between gap-4 py-2.5">
                                    <flux:text>{{ $spec->key }}</flux:text>
                                    <flux:text>{{ $spec->value }}</flux:text>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <flux:text class="mt-4">No specifications added.</flux:text>
                @endforelse
            </flux:card>
        </div>

        <!-- Right column -->
        <div class="space-y-6">

            <!-- Pricing -->
            <flux:card class="bg-(--callout-background)">
                <flux:heading size="lg">Pricing</flux:heading>

                <div class="mt-4 space-y-3">
                    <!-- MRP -->
                    <div class="flex items-center justify-between">
                        <flux:text>MRP</flux:text>
                        <flux:text class="font-medium {{ $product->mrp > $product->sale_price ? 'line-through text-zinc-400' : 'text-zinc-900 dark:text-zinc-100' }}">
                            ₹{{ number_format($product->mrp, 2) }}
                        </flux:text>
                    </div>

                    <!-- Sale Price -->
                    <div class="flex items-center justify-between">
                        <flux:text>Sale Price</flux:text>
                        <flux:text class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">
                            ₹{{ number_format($product->sale_price, 2) }}
                        </flux:text>
                    </div>

                    @if ($product->mrp > $product->sale_price)
                        @php $discount = round((($product->mrp - $product->sale_price) / $product->mrp) * 100); @endphp
                        <div class="flex items-center justify-between">
                            <flux:text>Discount</flux:text>
                            <flux:badge size="sm" color="green">{{ $discount }}% off</flux:badge>
                        </div>
                    @endif

                    <flux:separator />

                    <!-- Purchase Price -->
                    <div class="flex items-center justify-between">
                        <flux:text>Purchase Price</flux:text>
                        <flux:text class="font-medium text-zinc-900 dark:text-zinc-100">
                            ₹{{ number_format($product->purchase_price, 2) }}
                        </flux:text>
                    </div>

                    <!-- Margin -->
                    <div class="flex items-center justify-between">
                        <flux:text>Margin</flux:text>
                        @php
                            $margin = $product->sale_price > 0
                                ? round((($product->sale_price - $product->purchase_price) / $product->sale_price) * 100, 1)
                                : 0;
                        @endphp
                        <flux:text class="font-medium text-zinc-900 dark:text-zinc-100">{{ $margin }}%</flux:text>
                    </div>
                </div>
            </flux:card>

            <!-- Inventory -->
            <flux:card class="bg-(--callout-background)">
                <flux:heading size="lg">Inventory</flux:heading>

                <div class="mt-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <flux:text>Stock</flux:text>
                        <flux:badge size="sm" :color="$product->stock === 0 ? 'red' : ($product->stock < 10 ? 'amber' : 'green')">
                            {{ $product->stock }} units
                        </flux:badge>
                    </div>

                    <div class="flex items-center justify-between">
                        <flux:text>SKU</flux:text>
                        <flux:text class="font-medium text-zinc-900 dark:text-zinc-100">{{ $product->sku ?? '-' }}</flux:text>
                    </div>

                    <div class="flex items-center justify-between">
                        <flux:text>HSN Code</flux:text>
                        <flux:text class="font-medium text-zinc-900 dark:text-zinc-100">{{ $product->hsn_code ?? '-' }}</flux:text>
                    </div>

                    <div class="flex items-center justify-between">
                        <flux:text>Slug</flux:text>
                        <flux:text class="font-medium text-zinc-900 dark:text-zinc-100">{{ $product->slug }}</flux:text>
                    </div>
                </div>
            </flux:card>

            <!-- Meta -->
            <flux:card class="bg-(--callout-background)">
                <flux:heading size="lg">Meta</flux:heading>

                <div class="mt-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <flux:text>Created</flux:text>
                        <flux:text class="font-medium text-zinc-900 dark:text-zinc-100">
                            {{ $product->created_at->format('d-m-Y') }}
                        </flux:text>
                    </div>

                    <div class="flex items-center justify-between">
                        <flux:text>Last Updated</flux:text>
                        <flux:text class="font-medium text-zinc-900 dark:text-zinc-100">
                            {{ $product->updated_at->format('d-m-Y') }}
                        </flux:text>
                    </div>
                </div>
            </flux:card>
        </div>
    </div>

    <!-- Delete product modal -->
    <flux:modal name="delete-product" class="min-w-[22rem]" @product-deleted.window="$flux.modal('delete-product').close()">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Delete product?</flux:heading>
                <flux:text class="mt-2">
                    You're about to delete this product.<br>
                    This action cannot be reversed.
                </flux:text>
            </div>
            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="danger" wire:click="delete">Delete product</flux:button>
            </div>
        </div>
    </flux:modal>
</div>