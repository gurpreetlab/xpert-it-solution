<?php

namespace App\Livewire\Shop;

use App\Models\Product;
use Flux\Flux;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Compare extends Component
{
    protected $listeners = ['wishlist-updated' => '$refresh', 'compare-updated' => '$refresh'];

    /**
     * Get the products in comparison.
     *
     * @return Collection<int, Product>
     */
    #[Computed]
    public function comparedProducts(): Collection
    {
        $ids = session()->get('compared_product_ids', []);

        if (empty($ids)) {
            return new Collection();
        }

        return Product::with(['brand', 'primaryImage', 'images', 'category', 'specifications'])
            ->whereIn('id', $ids)
            ->where('is_active', true)
            ->get();
    }

    /**
     * Remove an item from comparison.
     */
    public function removeProduct(int $productId): void
    {
        $ids = session()->get('compared_product_ids', []);

        if (($key = array_search($productId, $ids, true)) !== false) {
            unset($ids[$key]);
            session()->put('compared_product_ids', array_values($ids));
            $this->dispatch('compare-updated');
            Flux::toast(text: 'Product removed from comparison.', variant: 'success');
        }
    }

    /**
     * Clear all compared products.
     */
    public function clearAll(): void
    {
        session()->forget('compared_product_ids');
        $this->dispatch('compare-updated');
        Flux::toast(text: 'Comparison list cleared.', variant: 'success');
    }

    /**
     * Toggle a product in/out of comparison from the frontend.
     */
    public static function toggleComparisonStatic(int $productId): void
    {
        $ids = session()->get('compared_product_ids', []);

        if (in_array($productId, $ids, true)) {
            $ids = array_diff($ids, [$productId]);
            session()->put('compared_product_ids', array_values($ids));
            Flux::toast(text: 'Removed from comparison.', variant: 'success');
        } else {
            if (count($ids) >= 3) {
                Flux::toast(text: 'You can compare up to 3 products at a time.', variant: 'warning');
                return;
            }
            $ids[] = $productId;
            session()->put('compared_product_ids', $ids);
            Flux::toast(text: 'Added to comparison.', variant: 'success');
        }
    }

    /**
     * Instance wrapper to toggle comparison.
     */
    public function toggleComparison(int $productId): void
    {
        self::toggleComparisonStatic($productId);
        $this->dispatch('compare-updated');
    }

    #[Layout('layouts.blank')]
    public function render(): View
    {
        return view('livewire.shop.compare');
    }
}
