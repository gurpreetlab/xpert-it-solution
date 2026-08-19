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
        $productId = $this->product()->id;
        $added = \App\Support\WishlistManager::toggle($productId);

        if ($added) {
            Flux::toast(text: 'Added to wishlist.', variant: 'success');
        } else {
            Flux::toast(text: 'Removed from wishlist.', variant: 'success');
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
        $product = $this->product();

        if ($product->stock <= 0) {
            Flux::toast(text: 'Product is out of stock.', variant: 'danger');
            return;
        }

        $success = \App\Support\CartManager::add($product->id, $this->quantity);

        if (! $success) {
            Flux::toast(text: "Only {$product->stock} unit(s) available in stock.", variant: 'warning');
            return;
        }

        $this->dispatch('cart-updated');
        Flux::toast(
            text: "Added {$this->quantity} unit(s) of {$product->name} to cart.",
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
