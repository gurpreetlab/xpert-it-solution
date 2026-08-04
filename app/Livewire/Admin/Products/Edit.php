<?php

// Path: app/Livewire/Admin/Products/Edit.php
// Register with implicit model binding, e.g.:
// Route::get('/dashboard/products/{product}/edit', Edit::class)->name('dashboard.products.edit');

namespace App\Livewire\Admin\Products;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Flux\Flux;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public Product $product;

    // ---- Relations ----
    public ?int $category_id = null;

    public ?int $brand_id = null;

    // ---- Basic info ----
    public string $name = '';

    // Regenerated automatically whenever the name changes (see updatedName()
    // below) and always kept unique, excluding this product itself. The
    // field is read-only in the UI, so it should never come from user input.
    public string $slug = '';

    public ?string $sku = null;

    public ?string $hsn_code = null;

    // ---- Pricing & inventory ----
    public string $mrp = '';

    public string $purchase_price = '';

    public string $sale_price = '';

    public int $stock = 0;

    // ---- Content ----
    public ?string $short_description = null;

    public ?string $description = null;

    // ---- Flags ----
    public bool $is_featured = false;

    public bool $is_active = true;

    // ---- Images ----
    // Already-persisted images, loaded from the product. Each entry:
    // ['id' => int, 'path' => string]. Reordering/removal here only
    // changes this array — nothing touches the database until save().
    public array $existingImages = [];

    // IDs of existing images the user removed, actually deleted on save().
    public array $removedImageIds = [];

    // Newly staged uploads, not yet persisted.
    /** @var TemporaryUploadedFile[] */
    public array $newImages = [];

    // Which image is primary, across both groups: "existing-{id}" or "new-{index}".
    public ?string $primaryImageKey = null;

    // ---- Specifications (key/value repeater) ----
    public array $specifications = [];

    public function mount(Product $product): void
    {
        $product->load([
            'images' => fn ($query) => $query->orderBy('sort_order'),
            'specifications' => fn ($query) => $query->orderBy('sort_order'),
        ]);

        $this->product = $product;

        $this->category_id = $product->category_id;
        $this->brand_id = $product->brand_id;
        $this->name = $product->name;
        $this->slug = $product->slug;
        $this->sku = $product->sku;
        $this->hsn_code = $product->hsn_code;
        $this->mrp = (string) $product->mrp;
        $this->purchase_price = (string) $product->purchase_price;
        $this->sale_price = (string) $product->sale_price;
        $this->stock = $product->stock;
        $this->short_description = $product->short_description;
        $this->description = $product->description;
        $this->is_featured = $product->is_featured;
        $this->is_active = $product->is_active;

        $this->existingImages = $product->images->map(fn ($image) => [
            'id' => $image->id,
            'path' => $image->path,
        ])->toArray();

        $primary = $product->images->firstWhere('is_primary', true);
        $this->primaryImageKey = match (true) {
            $primary !== null => "existing-{$primary->id}",
            ! empty($this->existingImages) => "existing-{$this->existingImages[0]['id']}",
            default => null,
        };

        $this->specifications = $product->specifications->isNotEmpty()
            ? $product->specifications->map(fn ($spec) => ['key' => $spec->key, 'value' => $spec->value])->toArray()
            : [['key' => '', 'value' => '']];
    }

    protected function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:categories,id'],
            'brand_id' => ['nullable', 'exists:brands,id'],

            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('products', 'slug')->ignore($this->product->id)],
            'sku' => ['nullable', 'string', 'max:100', Rule::unique('products', 'sku')->ignore($this->product->id)],
            'hsn_code' => ['nullable', 'string', 'max:50'],

            'mrp' => ['required', 'numeric', 'min:0'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'sale_price' => [
                'required',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) {
                    if ($this->mrp !== '' && (float) $value > (float) $this->mrp) {
                        $fail('The sale price cannot be higher than the MRP.');
                    }
                },
            ],
            'stock' => ['required', 'integer', 'min:0'],

            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],

            'is_featured' => ['boolean'],
            'is_active' => ['boolean'],

            'newImages' => ['nullable', 'array'],
            'newImages.*' => ['image', 'max:2048'],

            'specifications.*.key' => ['nullable', 'string', 'max:255', 'required_with:specifications.*.value'],
            'specifications.*.value' => ['nullable', 'string', 'required_with:specifications.*.key'],
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'category_id' => 'category',
            'brand_id' => 'brand',
            'name' => 'product name',
            'slug' => 'URL slug',
            'sku' => 'SKU',
            'hsn_code' => 'HSN code',
            'mrp' => 'MRP',
            'purchase_price' => 'purchase price',
            'sale_price' => 'sale price',
            'stock' => 'stock quantity',
            'short_description' => 'short description',
            'description' => 'description',
            'newImages' => 'images',
        ];
    }

    protected function messages(): array
    {
        return [
            'category_id.required' => 'Please choose a category for this product.',
            'category_id.exists' => 'The selected category no longer exists. Please choose another one.',
            'brand_id.exists' => 'The selected brand no longer exists. Please choose another one.',

            'name.required' => 'Please enter a product name.',
            'name.max' => 'The product name is too long (255 characters max).',

            'slug.required' => 'We couldn\'t generate a URL slug — please check the product name.',
            'slug.unique' => 'This product name is already in use. Please tweak it slightly and try again.',

            'sku.unique' => 'This SKU is already assigned to another product.',
            'sku.max' => 'The SKU is too long (100 characters max).',

            'hsn_code.max' => 'The HSN code is too long (50 characters max).',

            'mrp.required' => 'Please enter the MRP.',
            'mrp.numeric' => 'The MRP must be a valid number.',
            'mrp.min' => 'The MRP cannot be negative.',

            'purchase_price.required' => 'Please enter the purchase price.',
            'purchase_price.numeric' => 'The purchase price must be a valid number.',
            'purchase_price.min' => 'The purchase price cannot be negative.',

            'sale_price.required' => 'Please enter the sale price.',
            'sale_price.numeric' => 'The sale price must be a valid number.',
            'sale_price.min' => 'The sale price cannot be negative.',

            'stock.required' => 'Please enter the available stock quantity.',
            'stock.integer' => 'Stock quantity must be a whole number.',
            'stock.min' => 'Stock quantity cannot be negative.',

            'short_description.max' => 'The short description is too long (500 characters max).',

            'newImages.array' => 'Something went wrong with the uploaded images. Please try uploading again.',
            'newImages.*.image' => 'This file isn\'t a supported image type. Please use JPG, PNG or WEBP.',
            'newImages.*.max' => 'This image is larger than 2MB. Please choose a smaller file.',

            'specifications.*.key.required_with' => 'Please enter a name for this specification, or remove the row.',
            'specifications.*.value.required_with' => 'Please enter a value for this specification, or remove the row.',
        ];
    }

    /**
     * Regenerate the slug whenever the name changes. Only fires on
     * user-driven input, not when mount() sets the property directly.
     */
    public function updatedName(string $value): void
    {
        $this->slug = $this->generateUniqueSlug($value);
    }

    protected function generateUniqueSlug(string $name): string
    {
        $base = Str::slug($name);

        if ($base === '') {
            return '';
        }

        $slug = $base;
        $suffix = 2;

        while (Product::where('slug', $slug)->where('id', '!=', $this->product->id)->exists()) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }

    // ---- Specifications repeater ----

    public function addSpecification(): void
    {
        $this->specifications[] = ['key' => '', 'value' => ''];
    }

    public function removeSpecification(int $index): void
    {
        unset($this->specifications[$index]);
        $this->specifications = array_values($this->specifications);

        if (empty($this->specifications)) {
            $this->specifications = [['key' => '', 'value' => '']];
        }
    }

    public function moveSpecificationUp(int $index): void
    {
        if ($index === 0) {
            return;
        }

        $this->swapSpecifications($index, $index - 1);
    }

    public function moveSpecificationDown(int $index): void
    {
        if ($index >= count($this->specifications) - 1) {
            return;
        }

        $this->swapSpecifications($index, $index + 1);
    }

    protected function swapSpecifications(int $a, int $b): void
    {
        $temp = $this->specifications[$a];
        $this->specifications[$a] = $this->specifications[$b];
        $this->specifications[$b] = $temp;
    }

    // ---- Existing images ----

    public function setPrimaryImage(string $key): void
    {
        $this->primaryImageKey = $key;
    }

    public function removeExistingImage(int $imageId): void
    {
        $wasPrimary = $this->primaryImageKey === "existing-{$imageId}";

        $this->existingImages = array_values(array_filter(
            $this->existingImages,
            fn ($image) => $image['id'] !== $imageId
        ));

        $this->removedImageIds[] = $imageId;

        if ($wasPrimary) {
            $this->primaryImageKey = $this->firstAvailableImageKey();
        }
    }

    public function moveExistingImageUp(int $index): void
    {
        if ($index === 0) {
            return;
        }

        $this->swapExistingImages($index, $index - 1);
    }

    public function moveExistingImageDown(int $index): void
    {
        if ($index >= count($this->existingImages) - 1) {
            return;
        }

        $this->swapExistingImages($index, $index + 1);
    }

    protected function swapExistingImages(int $a, int $b): void
    {
        $temp = $this->existingImages[$a];
        $this->existingImages[$a] = $this->existingImages[$b];
        $this->existingImages[$b] = $temp;
    }

    // ---- New images ----

    public function updatedNewImages(): void
    {
        $this->resetErrorBag('newImages');
    }

    public function removeNewImage(int $index): void
    {
        $wasPrimary = $this->primaryImageKey === "new-{$index}";

        unset($this->newImages[$index]);
        $this->newImages = array_values($this->newImages);

        // Shift any "new-N" primary key that pointed past the removed index.
        if ($this->primaryImageKey !== null && str_starts_with($this->primaryImageKey, 'new-')) {
            $oldIndex = (int) str_replace('new-', '', $this->primaryImageKey);

            if ($oldIndex > $index) {
                $this->primaryImageKey = 'new-'.($oldIndex - 1);
            }
        }

        if ($wasPrimary) {
            $this->primaryImageKey = $this->firstAvailableImageKey();
        }

        $this->resetErrorBag('newImages');
    }

    public function moveNewImageUp(int $index): void
    {
        if ($index === 0) {
            return;
        }

        $this->swapNewImages($index, $index - 1);
    }

    public function moveNewImageDown(int $index): void
    {
        if ($index >= count($this->newImages) - 1) {
            return;
        }

        $this->swapNewImages($index, $index + 1);
    }

    protected function swapNewImages(int $a, int $b): void
    {
        $temp = $this->newImages[$a];
        $this->newImages[$a] = $this->newImages[$b];
        $this->newImages[$b] = $temp;

        if ($this->primaryImageKey === "new-{$a}") {
            $this->primaryImageKey = "new-{$b}";
        } elseif ($this->primaryImageKey === "new-{$b}") {
            $this->primaryImageKey = "new-{$a}";
        }

        $this->resetErrorBag('newImages');
    }

    protected function firstAvailableImageKey(): ?string
    {
        if (! empty($this->existingImages)) {
            return "existing-{$this->existingImages[0]['id']}";
        }

        if (! empty($this->newImages)) {
            return 'new-0';
        }

        return null;
    }

    // ---- Save ----

    public function save()
    {
        // Guarantee the slug is fresh and unique right before validating.
        $this->slug = $this->generateUniqueSlug($this->name);

        $validated = $this->validate();

        DB::transaction(function () use ($validated) {
            $this->product->update([
                'category_id' => $validated['category_id'],
                'brand_id' => $validated['brand_id'],
                'name' => $validated['name'],
                'slug' => $validated['slug'],
                'sku' => $validated['sku'],
                'hsn_code' => $validated['hsn_code'],
                'mrp' => $validated['mrp'],
                'purchase_price' => $validated['purchase_price'],
                'sale_price' => $validated['sale_price'],
                'stock' => $validated['stock'],
                'short_description' => $validated['short_description'],
                'description' => $validated['description'],
                'is_featured' => $validated['is_featured'],
                'is_active' => $validated['is_active'],
            ]);

            // Delete images the user removed — deferred until now so
            // nothing is lost if they navigate away without saving.
            if (! empty($this->removedImageIds)) {
                $imagesToDelete = ProductImage::whereKey($this->removedImageIds)->get();

                foreach ($imagesToDelete as $image) {
                    Storage::disk('public')->delete($image->path);
                    $image->delete();
                }
            }

            // Persist the (possibly reordered) existing images and primary flag.
            foreach ($this->existingImages as $order => $image) {
                ProductImage::whereKey($image['id'])->update([
                    'sort_order' => $order,
                    'is_primary' => $this->primaryImageKey === "existing-{$image['id']}",
                ]);
            }

            // Store newly uploaded images, ordered after the existing ones.
            $nextOrder = count($this->existingImages);

            foreach ($this->newImages as $index => $image) {
                $path = $image->store('products', 'public');

                $this->product->images()->create([
                    'path' => $path,
                    'is_primary' => $this->primaryImageKey === "new-{$index}",
                    'sort_order' => $nextOrder++,
                ]);
            }

            // Replace specifications wholesale — simplest way to keep them
            // in sync with the repeater's current rows and order.
            $this->product->specifications()->delete();

            $sortOrder = 0;

            foreach ($this->specifications as $spec) {
                if (trim($spec['key'] ?? '') === '' || trim($spec['value'] ?? '') === '') {
                    continue;
                }

                $this->product->specifications()->create([
                    'key' => $spec['key'],
                    'value' => $spec['value'],
                    'sort_order' => $sortOrder++,
                ]);
            }
        });

        Flux::toast("Product \"{$this->product->name}\" was updated successfully.");

        return $this->redirect(route('dashboard.products.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.products.edit', [
            'categories' => Category::orderBy('name')->get(['id', 'name']),
            'brands' => Brand::orderBy('name')->get(['id', 'name']),
        ]);
    }
}
