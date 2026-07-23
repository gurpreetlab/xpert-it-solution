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

    public function openEnquiry(): void
    {
        $product = $this->product();
        $this->enquiryMessage = "Hello, I would like to enquire about \"{$product->name}\" (Quantity: {$this->quantity}). Please provide pricing, tax details, and lead time.";
        $this->showEnquiryModal = true;
    }

    public function submitEnquiry(): void
    {
        $this->validate();

        $this->reset([
            "enquiryName",
            "enquiryEmail",
            "enquiryPhone",
            "enquiryMessage",
            "showEnquiryModal",
        ]);

        Flux::toast(
            text: "Thank you! Your enquiry has been received. Our IT experts will contact you shortly.",
            variant: "success",
        );
    }

    public function addToCart(): void
    {
        Flux::toast(
            text: "Added {$this->quantity} unit(s) of {$this->product()->name} to cart.",
            variant: "success",
        );
    }

    public function placeOrder(): void
    {
        $this->openEnquiry();
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
