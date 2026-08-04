<?php

namespace App\Livewire\Admin\Categories;

use App\Models\Category;
use Flux\Flux;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public ?Category $editingCategory = null;

    public ?Category $deletingCategory = null;

    public string $name = '';

    public string $search = '';

    /**
     * Get the validation rules for the category name.
     */
    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('categories', 'name')->ignore($this->editingCategory)],
        ];
    }

    /**
     * Get the custom validation messages for the category name
     */
    protected function messages(): array
    {
        return [
            'name.required' => 'Category name is required.',
            'name.unique' => 'Category name must be unique.',
            'name.string' => 'Category name must be a string.',
            'name.max' => 'Category name must not exceed 255 characters.',
        ];
    }

    public function create(): void
    {
        $this->resetValidation();
        $this->reset(['editingCategory', 'name']);
    }

    public function edit(Category $category): void
    {
        $this->resetValidation();
        $this->editingCategory = $category;
        $this->name = $category->name;
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->editingCategory) {
            $this->editingCategory->update([
                'name' => $validated['name'],
            ]);

            Flux::toast('Category updated successfully!');
        } else {
            Category::create([
                'name' => $validated['name'],
            ]);

            Flux::toast('Category created successfully!');
        }

        $this->reset(['editingCategory', 'name']);
        $this->dispatch('category-saved');
    }

    public function confirmDelete(Category $category): void
    {
        $this->resetValidation();
        $this->deletingCategory = $category;
    }

    public function delete(): void
    {
        if (! $this->deletingCategory) {
            return;
        }

        $this->deletingCategory->delete();
        $this->reset('deletingCategory');
        $this->dispatch('category-deleted');
        Flux::toast('Category deleted successfully!');
    }

    #[Computed]
    private function categories(): LengthAwarePaginator
    {
        return Category::search($this->search)
            ->withCount('products')
            ->latest()
            ->paginate(10);
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Get the categories based on the search query
     */
    public function render(): View
    {
        return view('livewire.admin.categories.index', [
            'categories' => $this->categories(),
        ]);
    }
}
