<?php

namespace App\Livewire\Admin\Brands;

use App\Models\Brand;
use Flux\Flux;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{
    use WithFileUploads, WithPagination;

    public ?Brand $editingBrand = null;

    public ?Brand $deletingBrand = null;

    public string $name = '';

    public ?TemporaryUploadedFile $logo = null;

    public ?string $description = null;

    public string $search = '';

    /**
     * Get the validation rules for the category name.
     */
    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('brands', 'name')->ignore($this->editingBrand)],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'description' => ['nullable', 'string'],
        ];
    }

    /**
     * Get the custom validation messages for the brand name
     */
    protected function messages(): array
    {
        return [
            'name.required' => 'Brand name is required.',
            'name.unique' => 'Brand name must be unique.',
            'name.string' => 'Brand name must be a string.',
            'name.max' => 'Brand name must not exceed 255 characters.',
            'logo.string' => 'Logo must be a string.',
            'description.string' => 'Description must be a string.',
        ];
    }

    public function create(): void
    {
        $this->resetValidation();
        $this->reset(['editingBrand', 'name']);
    }

    public function edit(Brand $brand): void
    {
        $this->resetValidation();
        $this->editingBrand = $brand;
        $this->logo = null;
        $this->description = $brand->description;
        $this->name = $brand->name;
    }

    public function save(): void
    {
        $validated = $this->validate();

        $data = [
            'name' => $validated['name'],
            'description' => $validated['description'],
        ];

        if ($this->logo) {
            if ($this->editingBrand?->logo) {
                Storage::disk('public')->delete($this->editingBrand->logo);
            }

            $data['logo'] = $this->logo->store('brands', 'public');
        }

        if ($this->editingBrand) {
            $this->editingBrand->update($data);

            Flux::toast('Brand updated successfully!');
        } else {
            Brand::create($data);

            Flux::toast('Brand created successfully!');
        }

        $this->reset(['editingBrand', 'name', 'logo', 'description']);
        $this->dispatch('brand-saved');
    }

    public function confirmDelete(Brand $brand): void
    {
        $this->resetValidation();
        $this->deletingBrand = $brand;
    }

    public function delete(): void
    {
        if (! $this->deletingBrand) {
            return;
        }

        $this->deletingBrand->delete();
        $this->reset('deletingBrand');
        $this->dispatch('brand-deleted');
        Flux::toast('Brand deleted successfully!');
    }

    #[Computed]
    private function brands(): LengthAwarePaginator
    {
        return Brand::search($this->search)
            ->withCount('products')
            ->latest()
            ->paginate(10);
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Get the brands based on the search query
     *
     * @return View
     */
    public function render()
    {
        return view('livewire.admin.brands.index', [
            'brands' => $this->brands(),
        ]);
    }
}
