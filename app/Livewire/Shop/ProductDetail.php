<?php

namespace App\Livewire\Shop;

use App\Models\Product;
use App\Models\Review;
use Flux\Flux;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

class ProductDetail extends Component
{
    public string $slug;

    public ?string $selectedImage = null;

    public int $quantity = 1;

    public int $rating = 5;

    public string $comment = '';

    public ?int $editingReviewId = null;

    public int $editRating = 5;

    public string $editComment = '';

    /**
     * @return array<string, string|array<int, string>>
     */
    protected function rules(): array
    {
        return [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ];
    }

    /**
     * Initialize the component with the given slug.
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
            'images',
            'specifications',
            'category',
            'brand',
            'primaryImage',
            'reviews.user', // Eager load reviews and review authors
        ])
            ->where('slug', $this->slug)
            ->where('is_active', true)
            ->firstOrFail();
    }

    public function submitReview(): void
    {
        if (! auth()->check()) {
            Flux::toast(text: 'Please login to write a review.', variant: 'danger');

            return;
        }

        $this->validate();

        $product = $this->product();

        // Check if user already reviewed this product
        $existingReview = $product->reviews()->where('user_id', auth()->id())->first();

        if ($existingReview) {
            Flux::toast(text: 'You have already reviewed this product.', variant: 'warning');

            return;
        }

        $product->reviews()->create([
            'user_id' => auth()->id(),
            'rating' => $this->rating,
            'comment' => $this->comment,
        ]);

        $this->reset(['rating', 'comment']);
        $this->rating = 5;

        Flux::toast(text: 'Your review has been submitted successfully!', variant: 'success');
    }

    public function editReview(int $reviewId): void
    {
        $review = Review::where('id', $reviewId)
            ->where('user_id', auth()->id())
            ->first();

        if (! $review) {
            return;
        }

        $this->editingReviewId = $review->id;
        $this->editRating = $review->rating;
        $this->editComment = $review->comment;
    }

    public function cancelEdit(): void
    {
        $this->editingReviewId = null;
        $this->reset(['editRating', 'editComment']);
        $this->editRating = 5;
    }

    public function updateReview(): void
    {
        $this->validate([
            'editRating' => 'required|integer|min:1|max:5',
            'editComment' => 'required|string|min:10|max:1000',
        ]);

        $review = Review::where('id', $this->editingReviewId)
            ->where('user_id', auth()->id())
            ->first();

        if (! $review) {
            Flux::toast(text: 'Unauthorized or review not found.', variant: 'danger');

            return;
        }

        $review->update([
            'rating' => $this->editRating,
            'comment' => $this->editComment,
        ]);

        $this->cancelEdit();
        Flux::toast(text: 'Review updated successfully!', variant: 'success');
    }

    public function deleteReview(int $reviewId): void
    {
        $review = Review::where('id', $reviewId)
            ->where('user_id', auth()->id())
            ->first();

        if (! $review) {
            Flux::toast(text: 'Unauthorized or review not found.', variant: 'danger');

            return;
        }

        $review->delete();

        if ($this->editingReviewId === $reviewId) {
            $this->cancelEdit();
        }

        Flux::toast(text: 'Review deleted successfully.', variant: 'success');
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
            'images',
            'specifications',
            'category',
            'brand',
            'primaryImage',
        ])
            ->where('is_active', true)
            ->where('category_id', $currentProduct->category_id)
            ->where('id', '!=', $currentProduct->id)
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

    public function toggleWishlist(): void
    {
        if (! auth()->check()) {
            Flux::toast(
                text: 'Please login to manage your wishlist.',
                variant: 'danger',
            );

            return;
        }

        $user = auth()->user();
        $productId = $this->product()->id;

        if ($user->wishlistProducts()->where('product_id', $productId)->exists()) {
            $user->wishlistProducts()->detach($productId);
            Flux::toast(text: 'Removed from wishlist.', variant: 'success');
        } else {
            $user->wishlistProducts()->attach($productId);
            Flux::toast(text: 'Added to wishlist.', variant: 'success');
        }

        $this->dispatch('wishlist-updated');
    }

    public function toggleComparison(): void
    {
        \App\Livewire\Shop\Compare::toggleComparisonStatic($this->product()->id);
        $this->dispatch('compare-updated');
    }

    public function addToCart(): void
    {
        if (! auth()->check()) {
            Flux::toast(
                text: 'Please login to add items to your cart.',
                variant: 'danger',
            );

            return;
        }

        // Check if product is out of stock
        if ($this->product()->stock <= 0) {
            Flux::toast(text: 'Product is out of stock.', variant: 'danger');

            return;
        }

        // Check if quantity exceeds stock
        if ($this->quantity > $this->product()->stock) {
            Flux::toast(
                text: "Only {$this->product()->stock} unit(s) available in stock.",
                variant: 'warning',
            );

            return;
        }

        $cart = auth()->user()->cart()->firstOrCreate();

        $item = $cart
            ->items()
            ->where('product_id', $this->product()->id)
            ->first();

        if ($item) {
            $item->increment('quantity', $this->quantity);
        } else {
            $cart->items()->create([
                'product_id' => $this->product()->id,
                'quantity' => $this->quantity,
                'sale_price' => $this->product()->sale_price,
            ]);
        }

        $this->dispatch('cart-updated');
        Flux::toast(
            text: "Added {$this->quantity} unit(s) of {$this->product()->name} to cart.",
            variant: 'success',
        );
    }

    public function placeOrder(): void
    {
        //
    }

    #[Layout('layouts.blank')]
    public function render(): View
    {
        return view('livewire.shop.product-detail', [
            'product' => $this->product(),
            'relatedProducts' => $this->relatedProducts(),
        ]);
    }
}
