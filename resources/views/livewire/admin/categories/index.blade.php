<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:heading size="xl" level="1">Categories</flux:heading>
            <flux:text class="mt-1 text-gray-500">Manage your categories</flux:text>
        </div>
        <!-- Add new category button -->
        <flux:modal.trigger name="category-modal" wire:click="create">
            <flux:button icon="plus">Add Category</flux:button>
        </flux:modal.trigger>
    </div>

    <div class="mb-4 flex gap-2">
        <flux:input wire:model.live="search" placeholder="Search..." />
    </div>

    <!-- Categories Table -->
    <flux:table :paginate="$categories">
        <flux:table.columns>
            <flux:table.column>Category</flux:table.column>
            <flux:table.column>Slug</flux:table.column>
            <flux:table.column>Products Count</flux:table.column>
            <flux:table.column>Created At</flux:table.column>
            <flux:table.column>Actions</flux:table.column>
        </flux:table.columns>

        <!-- Category Rows -->
        <flux:table.rows>
            @forelse($categories as $category)
                <flux:table.row :key="$category->id">
                    <flux:table.cell class="whitespace-nowrap">{{ $category->name }}</flux:table.cell>

                    <flux:table.cell class="whitespace-nowrap">{{ $category->slug }}</flux:table.cell>

                    <flux:table.cell class="py-0">{{ $category->products_count ?? 0 }}</flux:table.cell>

                    <flux:table.cell>{{ $category->created_at->format('d-m-Y') }}</flux:table.cell>

                    <flux:table.cell class="whitespace-nowrap gap-2 flex">
                        <!-- Edit category button -->
                        <flux:modal.trigger name="category-modal" wire:click="edit({{ $category->id }})">
                            <flux:button size="sm" class="cursor-pointer">
                                Edit
                            </flux:button>
                        </flux:modal.trigger>

                        <!-- Delete category button -->
                        <flux:modal.trigger name="delete-category" wire:click="confirmDelete({{ $category->id }})">
                            <flux:button variant="danger" size="sm" class="cursor-pointer">
                                Delete
                            </flux:button>
                        </flux:modal.trigger>
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="5" class="text-center">No categories found.</flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>

    <!-- Add New Category Modal -->
    <flux:modal name="category-modal" @category-saved.window="$flux.modal('category-modal').close()" flyout>
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ $editingCategory ? 'Edit Category' : 'Add Category' }}</flux:heading>
                <flux:text class="mt-2">{{ $editingCategory ? 'Edit the category details below.' : 'Add a new category to the list.' }}</flux:text>
            </div>

            <form wire:submit.prevent="save" class="space-y-6">
                <flux:input wire:model.live="name" label="Category Name" placeholder="Electronics"/>

                <div class="flex">
                    <flux:spacer />

                    <flux:button type="submit" variant="primary">Save changes</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>

    <!-- Delete category modal -->
    <flux:modal name="delete-category" class="min-w-[22rem]" @category-deleted.window="$flux.modal('delete-category').close()">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Delete category?</flux:heading>
                <flux:text class="mt-2">
                    You're about to delete this category.<br>
                    This action cannot be reversed.
                </flux:text>
            </div>
            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="danger" wire:click="delete">Delete category</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
