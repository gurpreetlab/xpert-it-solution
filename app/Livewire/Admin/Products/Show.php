<?php

namespace App\Livewire\Admin\Products;

use App\Models\Product;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Livewire\Component;

class Show extends Component
{
    public Product $product;

    public ?Product $deletingProduct = null;

    public function mount(Product $product): void
    {
        $product->load([
            'category',
            'brand',
            'images' => fn ($query) => $query->orderByDesc('is_primary')->orderBy('sort_order'),
            'specifications' => fn ($query) => $query->orderBy('sort_order'),
        ]);

        $this->product = $product;
    }

    public function toggleActive(): void
    {
        $this->product->update(['is_active' => ! $this->product->is_active]);

        // now(), not flash() — this action doesn't redirect, so the message
        // only needs to survive the current render, not the next request.
        Flux::toast($this->product->is_active
            ? "\"{$this->product->name}\" is now active and visible in the storefront."
            : "\"{$this->product->name}\" has been deactivated and hidden from the storefront.");
    }

    public function toggleFeatured(): void
    {
        $this->product->update(['is_featured' => ! $this->product->is_featured]);

        Flux::toast($this->product->is_featured
            ? "\"{$this->product->name}\" is now featured on the homepage."
            : "\"{$this->product->name}\" is no longer featured.");
    }

    public function confirmDelete(Product $product): void
    {
        $this->resetValidation();
        $this->deletingProduct = $product;
    }

    public function delete(): ?RedirectResponse
    {
        if (! $this->deletingProduct) {
            return null;
        }

        $this->deletingProduct->delete();
        $this->reset('deletingProduct');
        $this->dispatch('product-deleted');
        Flux::toast('Product deleted successfully!');

        return $this->redirect(route('dashboard.products.index'), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.admin.products.show');
    }
}
