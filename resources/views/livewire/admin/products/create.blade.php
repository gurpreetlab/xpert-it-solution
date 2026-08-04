<div>
    <div class="flex flex-wrap items-start justify-between gap-4 mb-6">
        <div>
            <flux:heading size="xl" level="1">Create Product</flux:heading>
            <flux:text class="mt-1 text-zinc-500">Add a new product to your catalog.</flux:text>
        </div>

        <div class="flex items-center gap-2">
            <flux:button variant="ghost" href="{{ route('dashboard.products.index') }}" wire:navigate>
                Cancel
            </flux:button>
            <flux:button variant="primary" wire:click="save" wire:loading.attr="disabled" wire:target="save">
                <span wire:loading.remove wire:target="save">Save Product</span>
                <span wire:loading wire:target="save">Saving...</span>
            </flux:button>
        </div>
    </div>

    <form wire:submit="save" class="space-y-6">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Main column --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Basic information --}}
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 p-6 space-y-6">
                    <flux:heading size="lg">Basic Information</flux:heading>

                    <div class="flex flex-col sm:flex-row items-baseline gap-4 w-full">
                        <flux:field class="flex-1 w-full">
                            <flux:label class="gap-1">Category <span class="text-red-500">*</span></flux:label>
                            <flux:select wire:model="category_id">
                                <flux:select.option value="">Select a category</flux:select.option>
                                @foreach ($categories as $category)
                                    <flux:select.option value="{{ $category->id }}">
                                        {{ $category->name }}
                                    </flux:select.option>
                                @endforeach
                            </flux:select>
                            <flux:error name="category_id" class="mt-1!" />
                        </flux:field>

                        <flux:field class="flex-1 w-full">
                            <flux:label>Brand</flux:label>
                            <flux:select wire:model="brand_id">
                                <flux:select.option value="">No brand</flux:select.option>
                                @foreach ($brands as $brand)
                                    <flux:select.option value="{{ $brand->id }}">
                                        {{ $brand->name }}
                                    </flux:select.option>
                                @endforeach
                            </flux:select>
                            <flux:error name="brand_id" class="mt-1!" />
                        </flux:field>
                    </div>

                    <flux:field>
                        <flux:label class="gap-1">Product Name <span class="text-red-500">*</span></flux:label>
                        <flux:input wire:model.live.debounce.400ms="name" placeholder="e.g. Men's Cotton Kurta" />
                        <flux:error name="name" class="mt-1!" />
                    </flux:field>

                    <flux:field>
                        <flux:label>URL Slug</flux:label>
                        <flux:description>Generated automatically from the product name and kept unique. This can't be edited directly.</flux:description>
                        <flux:input wire:model="slug" readonly tabindex="-1" class="cursor-not-allowed opacity-75" />
                        <flux:error name="slug" class="mt-1!" />
                    </flux:field>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <flux:field>
                            <flux:label>SKU</flux:label>
                            <flux:input wire:model="sku" placeholder="e.g. KUR-BLK-M" />
                            <flux:error name="sku" class="mt-1!" />
                        </flux:field>

                        <flux:field>
                            <flux:label>HSN Code</flux:label>
                            <flux:input wire:model="hsn_code" placeholder="e.g. 6109" />
                            <flux:error name="hsn_code" class="mt-1!" />
                        </flux:field>
                    </div>
                </div>

                <!-- Pricing & inventory -->
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 p-6 space-y-6">
                    <flux:heading size="lg">Pricing &amp; Inventory</flux:heading>

                    <div class="flex flex-col sm:flex-row items-baseline gap-4 w-full">
                        <flux:field class="flex-1 w-full">
                            <flux:label class="gap-1">MRP <span class="text-red-500">*</span></flux:label>
                            <flux:input type="number" step="0.01" min="0" icon="currency-rupee" wire:model="mrp" placeholder="0.00" />
                            <flux:error name="mrp" class="mt-1!" />
                        </flux:field>

                        <flux:field class="flex-1 w-full">
                            <flux:label class="gap-1">Purchase Price <span class="text-red-500">*</span></flux:label>
                            <flux:input type="number" step="0.01" min="0" icon="currency-rupee" wire:model="purchase_price" placeholder="0.00" />
                            <flux:error name="purchase_price" class="mt-1!" />
                        </flux:field>

                        <flux:field class="flex-1 w-full">
                            <flux:label class="gap-1">Sale Price <span class="text-red-500">*</span></flux:label>
                            <flux:input type="number" step="0.01" min="0" icon="currency-rupee" wire:model="sale_price" placeholder="0.00" />
                            <flux:error name="sale_price" class="mt-1!" />
                        </flux:field>
                    </div>

                    <flux:field class="sm:w-1/3">
                        <flux:label class="gap-1">Stock Quantity <span class="text-red-500">*</span></flux:label>
                        <flux:input type="number" min="0" wire:model="stock" placeholder="0" />
                        <flux:error name="stock" class="mt-1!" />
                    </flux:field>
                </div>

                {{-- Descriptions --}}
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 p-6 space-y-6">
                    <flux:heading size="lg">Description</flux:heading>

                    <flux:field>
                        <flux:label>Short Description</flux:label>
                        <flux:textarea wire:model="short_description" rows="2" placeholder="One or two lines shown on listing cards" />
                        <flux:error name="short_description" class="mt-1!" />
                    </flux:field>

                    <flux:field>
                        <flux:label>Full Description</flux:label>
                        <flux:textarea wire:model="description" rows="6" placeholder="Detailed product description" />
                        <flux:error name="description" class="mt-1!" />
                    </flux:field>
                </div>

                {{-- Specifications --}}
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <flux:heading size="lg">Specifications</flux:heading>
                        <flux:button size="sm" variant="ghost" icon="plus" wire:click="addSpecification">
                            Add Row
                        </flux:button>
                    </div>
                    <flux:text class="text-zinc-500">Extra attributes like Material, Size, Weight, Color, etc.</flux:text>

                    <div class="space-y-3">
                        @foreach ($specifications as $index => $spec)
                            <div wire:key="spec-{{ $index }}" class="flex items-start gap-2">
                                <div class="flex flex-col">
                                    <flux:button
                                        size="sm" class="h-5!" variant="ghost" icon="chevron-up"
                                        wire:click="moveSpecificationUp({{ $index }})"
                                        :disabled="$index === 0"
                                    />
                                    <flux:button
                                        size="sm" class="h-5!" variant="ghost" icon="chevron-down"
                                        wire:click="moveSpecificationDown({{ $index }})"
                                        :disabled="$index === count($specifications) - 1"
                                    />
                                </div>

                                <div class="flex-1">
                                    <flux:input wire:model="specifications.{{ $index }}.key" placeholder="e.g. Material" />
                                    <flux:error name="specifications.{{ $index }}.key" class="mt-1!" />
                                </div>

                                <div class="flex-1">
                                    <flux:input wire:model="specifications.{{ $index }}.value" placeholder="e.g. 100% Cotton" />
                                    <flux:error name="specifications.{{ $index }}.value" class="mt-1!" />
                                </div>

                                <flux:button
                                    class="mt-0.5" size="sm" variant="ghost" icon="trash"
                                    wire:click="removeSpecification({{ $index }})"
                                />
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Side column --}}
            <div class="space-y-6">

                {{-- Status --}}
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 p-6 space-y-4">
                    <flux:heading size="lg">Status</flux:heading>

                    <div class="flex items-center justify-between">
                        <div>
                            <flux:text class="font-medium">Active</flux:text>
                            <flux:text size="sm" class="text-zinc-500">Visible in the storefront</flux:text>
                        </div>
                        <flux:switch wire:model="is_active" />
                    </div>

                    <flux:separator />

                    <div class="flex items-center justify-between">
                        <div>
                            <flux:text class="font-medium">Featured</flux:text>
                            <flux:text size="sm" class="text-zinc-500">Show on the homepage</flux:text>
                        </div>
                        <flux:switch wire:model="is_featured" />
                    </div>
                </div>

                {{-- Images --}}
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 p-6 space-y-4">
                    <flux:heading size="lg">Images</flux:heading>

                    <flux:field>
                        <flux:input type="file" wire:model="images" multiple accept="image/*" />
                        <flux:description>PNG, JPG or WEBP. Max 2MB each.</flux:description>
                        @error('images')
                            <flux:text size="sm" class="text-red-600 dark:text-red-400 mt-1">
                                Some of your images couldn't be uploaded. Please check the list below.
                            </flux:text>
                        @enderror
                    </flux:field>

                    <div wire:loading wire:target="images" class="text-sm text-zinc-500">
                        Uploading...
                    </div>

                    @if (count($images))
                        <div class="space-y-3">
                            @foreach ($images as $index => $image)
                                <div wire:key="image-{{ $index }}" class="flex items-center gap-3 rounded-lg border border-zinc-200 dark:border-zinc-700 p-2">
                                    <img src="{{ $image->temporaryUrl() }}" class="h-14 w-14 rounded-md object-cover shrink-0" />

                                    <div class="flex-1 min-w-0">
                                        <flux:text class="truncate block text-sm">{{ $image->getClientOriginalName() }}</flux:text>

                                        @error("images.$index")
                                            <flux:text size="sm" class="text-red-600 dark:text-red-400 mt-0.5">
                                                This image is too large or in an unsupported format. Please use a JPG, PNG or WEBP under 2MB.
                                            </flux:text>
                                        @else
                                            <button
                                                type="button"
                                                wire:click="setPrimaryImage({{ $index }})"
                                                class="text-xs mt-1 {{ $index === $primaryImageIndex ? 'text-blue-600 font-medium' : 'text-zinc-500 hover:text-zinc-700' }}"
                                            >
                                                {{ $index === $primaryImageIndex ? '★ Primary image' : 'Set as primary' }}
                                            </button>
                                        @enderror
                                    </div>

                                    <div class="flex flex-col gap-0.5">
                                        <flux:button
                                            size="sm" variant="ghost" icon="chevron-up"
                                            wire:click="moveImageUp({{ $index }})"
                                            :disabled="$index === 0"
                                        />
                                        <flux:button
                                            size="sm" variant="ghost" icon="chevron-down"
                                            wire:click="moveImageDown({{ $index }})"
                                            :disabled="$index === count($images) - 1"
                                        />
                                    </div>

                                    <flux:button
                                        size="sm" variant="ghost" icon="trash"
                                        wire:click="removeImage({{ $index }})"
                                    />
                                </div>
                            @endforeach
                        </div>
                    @else
                        <flux:text class="text-zinc-500 text-sm">No images uploaded yet.</flux:text>
                    @endif
                </div>
            </div>
        </div>

        {{-- Bottom actions (mirrors the top bar, handy on long forms) --}}
        <div class="flex items-center justify-end gap-2 pt-2">
            <flux:button variant="ghost" href="{{ route('dashboard.products.index') }}" wire:navigate>
                Cancel
            </flux:button>
            <flux:button type="submit" variant="primary" wire:loading.attr="disabled" wire:target="save">
                <span wire:loading.remove wire:target="save">Save Product</span>
                <span wire:loading wire:target="save">Saving...</span>
            </flux:button>
        </div>
    </form>
</div>
