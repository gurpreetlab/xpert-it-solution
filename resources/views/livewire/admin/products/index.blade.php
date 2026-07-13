<div class="space-y-6">

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:heading size="xl" level="1">Products</flux:heading>
            <flux:text class="mt-1 text-gray-500">Manage your product catalog</flux:text>
        </div>
        <flux:button href="{{ route('dashboard.products.create') }}" wire:navigate icon="plus">
            Add Product
        </flux:button>
    </div>


    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-5">
        <flux:input
            wire:model.live.debounce.400ms="search"
            placeholder="Search name, SKU, HSN..."
            icon="magnifying-glass"
            class="lg:col-span-2"
        />

        <flux:select wire:model.live="categoryId" placeholder="All categories">
            <flux:select.option value="">All categories</flux:select.option>
            @foreach ($this->categories as $category)
                <flux:select.option value="{{ $category->id }}">{{ $category->name }}</flux:select.option>
            @endforeach
        </flux:select>

        <flux:select wire:model.live="brandId" placeholder="All brands">
            <flux:select.option value="">All brands</flux:select.option>
            @foreach ($this->brands as $brand)
                <flux:select.option value="{{ $brand->id }}">{{ $brand->name }}</flux:select.option>
            @endforeach
        </flux:select>

        <div class="flex gap-2">
            <flux:select wire:model.live="status" placeholder="All statuses" class="flex-1">
                <flux:select.option value="">All statuses</flux:select.option>
                <flux:select.option value="active">Active</flux:select.option>
                <flux:select.option value="inactive">Inactive</flux:select.option>
            </flux:select>

            @if ($search !== '' || $categoryId !== '' || $brandId !== '' || $status !== '')
                <flux:button variant="ghost" icon="x-mark" wire:click="clearFilters" title="Clear filters" />
            @endif
        </div>
    </div>

    <!-- Products Table -->
    <flux:table :paginate="$products">
        <flux:table.columns>
            <flux:table.column>Image</flux:table.column>
            <flux:table.column>Product</flux:table.column>
            <flux:table.column>Sku</flux:table.column>
            <flux:table.column>Hsn Code</flux:table.column>
            <flux:table.column>Category</flux:table.column>
            <flux:table.column>Brand</flux:table.column>
            <flux:table.column>MRP Price</flux:table.column>
            <flux:table.column>Purchase Price</flux:table.column>
            <flux:table.column>Sale Price</flux:table.column>
            <flux:table.column>Stock</flux:table.column>
            <flux:table.column>Status</flux:table.column>
            <flux:table.column>Created At</flux:table.column>
            <flux:table.column>Actions</flux:table.column>
        </flux:table.columns>

        <!-- Product Rows -->
        <flux:table.rows>
            @forelse($products as $product)
                <flux:table.row :key="$product->id">
                    <flux:table.cell class="whitespace-nowrap">
                        <flux:avatar :src="$product->primaryImage ? asset('storage/' . $product->primaryImage->path) : null" name="{{ $product->name }}" />
                    </flux:table.cell>

                    <flux:table.cell class="whitespace-nowrap">
                        <a href="{{ route('dashboard.products.show', $product) }}" class="hover:text-emerald-400">{{ $product->name }}</a>
                    </flux:table.cell>

                    <flux:table.cell class="whitespace-nowrap">{{ $product->sku ?? '-' }}</flux:table.cell>

                    <flux:table.cell class="whitespace-nowrap">{{ $product->hsn_code ?? '-' }}</flux:table.cell>

                    <flux:table.cell class="whitespace-nowrap">{{ $product->category->name }}</flux:table.cell>

                    <flux:table.cell class="whitespace-nowrap">{{ $product->brand?->name ?? '-' }}</flux:table.cell>

                    <flux:table.cell class="whitespace-nowrap">₹{{ number_format($product->mrp, 2) }}</flux:table.cell>

                    <flux:table.cell class="whitespace-nowrap">₹{{ number_format($product->purchase_price, 2) }}</flux:table.cell>

                    <flux:table.cell class="whitespace-nowrap">₹{{ number_format($product->sale_price, 2) }}</flux:table.cell>

                    <flux:table.cell class="whitespace-nowrap">{{ $product->stock }}</flux:table.cell>

                    <flux:table.cell class="whitespace-nowrap">
                        <flux:badge size="sm" color="{{ $product->is_active ? 'emerald' : 'zinc' }}">{{ $product->is_active ? 'Active' : 'Inactive' }}</flux:badge>
                    </flux:table.cell>

                    <flux:table.cell class="whitespace-nowrap">{{ $product->created_at->format('d-m-Y') }}</flux:table.cell>

                    <flux:table.cell class="whitespace-nowrap gap-2 flex">
                        <!-- Edit product button -->
                        <flux:button size="sm" href="{{ route('dashboard.products.edit', $product) }}" wire:navigate>
                            Edit
                        </flux:button>

                        <!-- Delete product button -->
                        <flux:modal.trigger name="delete-product" wire:click="confirmDelete({{ $product->id }})">
                            <flux:button variant="danger" size="sm" class="cursor-pointer">
                                Delete
                            </flux:button>
                        </flux:modal.trigger>
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="10" class="text-center">No categories found.</flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>

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
