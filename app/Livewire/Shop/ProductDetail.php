<?php

namespace App\Livewire\Shop;

use App\Models\Product;
use Flux\Flux;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.blank')]
class ProductDetail extends Component
{
    public string $slug;

    public function product(): Product
    {
        return Product::with(['images', 'specifications', 'category', 'brand'])
            ->where('slug', $this->slug)
            ->firstOrFail();
    }

    public function relatedProducts(): Collection
    {
        return Product::with(['images', 'specifications', 'category', 'brand'])
            ->where('category_id', $this->product()->category_id)
            ->where('id', '!=', $this->product()->id)
            ->inRandomOrder()
            ->take(4)
            ->get();
    }

    public function addToCart(): void
    {
        Flux::toast('Product added to cart successfully.');
    }

    public function placeOrder(): void
    {
        Flux::toast('Order placed successfully.');
    }

    public function render()
    {
        return view('livewire.shop.product-detail', [
            'product' => $this->product(),
            'relatedProducts' => $this->relatedProducts(),
        ]);
    }
}
