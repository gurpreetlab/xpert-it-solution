<?php

namespace App\Livewire\Shop;

use App\Models\Product;
use Flux\Flux;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Component;

class ProductDetail extends Component
{
    public string $slug;

    public ?string $selectedImage = null;

    public int $quantity = 1;

    /**
     * Initialize the component with the given slug.
     *
     * @param string $slug
     * @return void
     */
    public function mount(string $slug): void
    {
        $this->slug = $slug;

        $product = $this->product();

        if ($product->primaryImage) {
            $this->selectedImage = $product->primaryImage->path;
        } elseif ($product->images->isNotEmpty()) {
            $this->selectedImage = $product->images->first()->path;
        }
    }

    public function product(): Product
    {
        return Product::with([
            "images",
            "specifications",
            "category",
            "brand",
            "primaryImage",
        ])
            ->where("slug", $this->slug)
            ->where("is_active", true)
            ->firstOrFail();
    }

    /**
     * Get the related products for the current product.
     *
     * @return Collection<int, Product>
     */
    public function relatedProducts(): Collection
    {
        $currentProduct = $this->product();

        return Product::with([
            "images",
            "specifications",
            "category",
            "brand",
            "primaryImage",
        ])
            ->where("is_active", true)
            ->where("category_id", $currentProduct->category_id)
            ->where("id", "!=", $currentProduct->id)
            ->inRandomOrder()
            ->take(4)
            ->get();
    }

    public function selectImage(string $path): void
    {
        $this->selectedImage = $path;
    }

    public function incrementQuantity(): void
    {
        if ($this->quantity < 99) {
            $this->quantity++;
        }
    }

    public function decrementQuantity(): void
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function addToCart(): void
    {
        if (!auth()->check()) {
            Flux::toast(
                text: "Please login to add items to your cart.",
                variant: "danger",
            );
            return;
        }

        // Check if product is out of stock
        if ($this->product()->stock <= 0) {
            Flux::toast(text: "Product is out of stock.", variant: "danger");
            return;
        }

        // Check if quantity exceeds stock
        if ($this->quantity > $this->product()->stock) {
            Flux::toast(
                text: "Only {$this->product()->stock} unit(s) available in stock.",
                variant: "warning",
            );
            return;
        }

        $cart = auth()->user()->cart()->firstOrCreate();

        $item = $cart
            ->items()
            ->where("product_id", $this->product()->id)
            ->first();

        if ($item) {
            $item->increment("quantity", $this->quantity);
        } else {
            $cart->items()->create([
                "product_id" => $this->product()->id,
                "quantity" => $this->quantity,
                "sale_price" => $this->product()->sale_price,
            ]);
        }

        $this->dispatch("cart-updated");
        Flux::toast(
            text: "Added {$this->quantity} unit(s) of {$this->product()->name} to cart.",
            variant: "success",
        );
    }

    public function placeOrder(): void
    {
        //
    }

    #[Layout("layouts.blank")]
    public function render()
    {
        return view("livewire.shop.product-detail", [
            "product" => $this->product(),
            "relatedProducts" => $this->relatedProducts(),
        ]);
    }
}
