<?php

namespace App\Livewire\Admin\Products;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    // ---- Relations ----
    public ?int $category_id = null;

    public ?int $brand_id = null;

    // ---- Basic info ----
    public string $name = '';

    // Auto-generated from the name and always kept unique. The field is
    // read-only in the UI, so it should never come from user input directly.
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

    // ---- Images (temporary uploads, not yet persisted) ----
    /** @var TemporaryUploadedFile[] */
    public array $images = [];

    public int $primaryImageIndex = 0;

    // ---- Specifications (key/value repeater) ----
    /** @var array<int, array{key: string, value: string}> */
    public array $specifications = [];

    public function mount(): void
    {
        $this->specifications = [
            ['group_name' => '', 'key' => '', 'value' => ''],
        ];
    }

    /** @return array<string, mixed> */
    protected function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:categories,id'],
            'brand_id' => ['nullable', 'exists:brands,id'],

            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:products,slug'],
            'sku' => ['nullable', 'string', 'max:100', 'unique:products,sku'],
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

            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'max:2048'],

            'specifications.*.group_name' => ['nullable', 'string', 'max:255'],
            'specifications.*.key' => ['nullable', 'string', 'max:255', 'required_with:specifications.*.value'],
            'specifications.*.value' => ['nullable', 'string', 'required_with:specifications.*.key'],
        ];
    }

    /**
     * Friendly, human-readable names used in place of raw property names
     * (e.g. "Sale Price" instead of "sale_price") inside validation messages.
     */
    /** @return array<string, string> */
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
            'images' => 'images',
        ];
    }

    /** @return array<string, string> */
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

            'images.array' => 'Something went wrong with the uploaded images. Please try uploading again.',
            'images.*.image' => 'This file isn\'t a supported image type. Please use JPG, PNG or WEBP.',
            'images.*.max' => 'This image is larger than 2MB. Please choose a smaller file.',

            'specifications.*.key.required_with' => 'Please enter a name for this specification, or remove the row.',
            'specifications.*.value.required_with' => 'Please enter a value for this specification, or remove the row.',
        ];
    }

    /**
     * Auto-generate a unique slug from the name. The slug field is read-only
     * in the UI, so this is the only place its value is ever set.
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

        while (Product::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }

    // ---- Specifications repeater ----

    public function addSpecification(): void
    {
        $this->specifications[] = ['group_name' => '', 'key' => '', 'value' => ''];
    }

    public function removeSpecification(int $index): void
    {
        unset($this->specifications[$index]);
        $this->specifications = array_values($this->specifications);

        if (empty($this->specifications)) {
            $this->specifications = [['group_name' => '', 'key' => '', 'value' => '']];
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

    // ---- Images ----

    public function updatedImages(): void
    {
        // Clear out any stale "failed to upload" errors from a previous
        // attempt so they don't linger next to a file that just succeeded.
        $this->resetErrorBag('images');

        if (empty($this->images)) {
            $this->primaryImageIndex = 0;
        }
    }

    public function removeImage(int $index): void
    {
        unset($this->images[$index]);
        $this->images = array_values($this->images);

        if ($this->primaryImageIndex === $index) {
            $this->primaryImageIndex = 0;
        } elseif ($this->primaryImageIndex > $index) {
            $this->primaryImageIndex--;
        }

        // Indexes shifted, so any error tied to the old position is no
        // longer meaningful.
        $this->resetErrorBag('images');
    }

    public function setPrimaryImage(int $index): void
    {
        $this->primaryImageIndex = $index;
    }

    public function moveImageUp(int $index): void
    {
        if ($index === 0) {
            return;
        }

        $this->swapImages($index, $index - 1);
    }

    public function moveImageDown(int $index): void
    {
        if ($index >= count($this->images) - 1) {
            return;
        }

        $this->swapImages($index, $index + 1);
    }

    protected function swapImages(int $a, int $b): void
    {
        $temp = $this->images[$a];
        $this->images[$a] = $this->images[$b];
        $this->images[$b] = $temp;

        // Keep the "primary" flag pointing at the same file after the swap.
        if ($this->primaryImageIndex === $a) {
            $this->primaryImageIndex = $b;
        } elseif ($this->primaryImageIndex === $b) {
            $this->primaryImageIndex = $a;
        }

        $this->resetErrorBag('images');
    }

    // ---- Save ----

    public function save(): mixed
    {
        // Guarantee the slug is fresh and unique right before validating,
        // in case another product was created since the name was last typed.
        $this->slug = $this->generateUniqueSlug($this->name);

        $validated = $this->validate();

        $product = DB::transaction(function () use ($validated) {
            $product = Product::create([
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

            foreach ($this->images as $index => $image) {
                $path = $image->store('products', 'public');

                $product->images()->create([
                    'path' => $path,
                    'is_primary' => $index === $this->primaryImageIndex,
                    'sort_order' => $index,
                ]);
            }

            $sortOrder = 0;

            foreach ($this->specifications as $spec) {
                $groupName = trim($spec['group_name'] ?? '');
                $key = $spec['key'];
                $value = $spec['value'];

                if (trim($key) === '' || trim($value) === '') {
                    continue;
                }

                $product->specifications()->create([
                    'group_name' => $groupName !== '' ? $groupName : null,
                    'key' => $key,
                    'value' => $value,
                    'sort_order' => $sortOrder++,
                ]);
            }

            return $product;
        });

        Flux::toast("Product \"{$product->name}\" was created successfully.");

        return $this->redirect(route('dashboard.products.index'), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.admin.products.create', [
            'categories' => Category::orderBy('name')->get(['id', 'name']),
            'brands' => Brand::orderBy('name')->get(['id', 'name']),
        ]);
    }
}
