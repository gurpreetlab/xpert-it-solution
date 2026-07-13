<div>
    <div class="flex-1 max-md:pt-6 self-stretch">
        <flux:heading size="xl" level="1" class="mb-6">Brands</flux:heading>
    </div>

    <div class="mb-4 flex gap-2">
        <flux:input wire:model.live="search" placeholder="Search..." />

        <!-- Add new brand button -->
        <flux:modal.trigger name="brand-modal" wire:click="create">
            <flux:button>Add New Brand</flux:button>
        </flux:modal.trigger>
    </div>

    <!-- Brands Table -->
    <flux:table :paginate="$brands">
        <flux:table.columns>
            <flux:table.column>Logo</flux:table.column>
            <flux:table.column>Name</flux:table.column>
            <flux:table.column>Slug</flux:table.column>
            <flux:table.column>Products Count</flux:table.column>
            <flux:table.column>Created At</flux:table.column>
            <flux:table.column>Actions</flux:table.column>
        </flux:table.columns>

        <!-- Category Rows -->
        <flux:table.rows>
            @forelse($brands as $brand)
                <flux:table.row :key="$brand->id">
                    <flux:table.cell class="whitespace-nowrap">
                        <flux:avatar :src="$brand->logo ? asset('storage/' . $brand->logo) : null" name="{{ $brand->name }}" />
                    </flux:table.cell>

                    <flux:table.cell class="whitespace-nowrap">{{ $brand->name }}</flux:table.cell>

                    <flux:table.cell class="whitespace-nowrap">{{ $brand->slug }}</flux:table.cell>

                    <flux:table.cell class="py-0">{{ $brand->product_count ?? 0 }}</flux:table.cell>

                    <flux:table.cell>{{ $brand->created_at->format('d-m-Y') }}</flux:table.cell>

                    <flux:table.cell class="whitespace-nowrap gap-2 flex">
                        <!-- Edit brand button -->
                        <flux:modal.trigger name="brand-modal" wire:click="edit({{ $brand->id }})">
                            <flux:button size="sm" class="cursor-pointer">
                                Edit
                            </flux:button>
                        </flux:modal.trigger>

                        <!-- Delete brand button -->
                        <flux:modal.trigger name="delete-brand" wire:click="confirmDelete({{ $brand->id }})">
                            <flux:button variant="danger" size="sm" class="cursor-pointer">
                                Delete
                            </flux:button>
                        </flux:modal.trigger>
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="5" class="text-center">No brands found.</flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>

    <!-- Add New Brand Modal -->
    <flux:modal name="brand-modal" @brand-saved.window="$flux.modal('brand-modal').close()" flyout>
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ $editingBrand ? 'Edit Brand' : 'Add New Brand' }}</flux:heading>
                <flux:text class="mt-2">{{ $editingBrand ? 'Edit the brand details below.' : 'Add a new brand to the list.' }}</flux:text>
            </div>

            <form wire:submit.prevent="save" class="space-y-6">
                <!-- Brand Name -->
                <flux:input wire:model.live="name" label="Brand Name" placeholder="CP PLUS"/>

                <!-- Logo -->
                @if ($logo)
                    <img src="{{ $logo->temporaryUrl() }}" class="h-20 w-20 rounded-lg border object-contain">
                @elseif ($editingBrand?->logo)
                    <img src="{{ Storage::url($editingBrand->logo) }}" class="h-20 w-20 rounded-lg border object-contain">
                @else
                    <div class="flex h-20 w-20 items-center justify-center rounded-lg border">
                        {{ $brand->initials ?? 'CP' }}
                    </div>
                @endif
                <flux:input type="file" wire:model.live="logo" label="Logo"/>

                <!-- Description -->
                <flux:textarea wire:model.live="description" label="Description" placeholder=""/>

                <div class="flex">
                    <flux:spacer />

                    <flux:button type="submit" variant="primary">Save changes</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>

    <!-- Delete brand modal -->
    <flux:modal name="delete-brand" class="min-w-[22rem]" @brand-deleted.window="$flux.modal('delete-brand').close()">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Delete brand?</flux:heading>
                <flux:text class="mt-2">
                    You're about to delete this brand.<br>
                    This action cannot be reversed.
                </flux:text>
            </div>
            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="danger" wire:click="delete">Delete brand</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
