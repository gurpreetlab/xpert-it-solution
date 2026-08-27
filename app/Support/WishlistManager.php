<?php

namespace App\Support;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class WishlistManager
{
    /**
     * Merge guest session wishlist items into the user's database wishlist upon login.
     */
    public static function syncGuestWishlistToUser(\App\Models\User $user): void
    {
        $guestWishlist = session()->get('guest_wishlist', []);
        if (empty($guestWishlist)) {
            return;
        }

        $user->clearWishlistMemoization();
        $user->wishlistProducts()->syncWithoutDetaching($guestWishlist);

        session()->forget('guest_wishlist');
    }

    /**
     * Check if a product ID is in the wishlist with O(1) set lookup performance.
     */
    public static function contains(int $productId): bool
    {
        if (auth()->check()) {
            return auth()->user()->isProductWishlisted($productId);
        }

        $guestWishlist = session()->get('guest_wishlist', []);

        return isset(array_flip($guestWishlist)[$productId]);
    }

    /**
     * Toggle a product in/out of the wishlist.
     */
    public static function toggle(int $productId): bool
    {
        if (auth()->check()) {
            $user = auth()->user();
            $user->clearWishlistMemoization();
            if ($user->wishlistProducts()->where('product_id', $productId)->exists()) {
                $user->wishlistProducts()->detach($productId);
                return false; // Removed
            } else {
                $user->wishlistProducts()->attach($productId);
                return true; // Added
            }
        }

        $guestWishlist = session()->get('guest_wishlist', []);
        $set = array_flip($guestWishlist);

        if (isset($set[$productId])) {
            unset($set[$productId]);
            session()->put('guest_wishlist', array_keys($set));
            return false;
        } else {
            $guestWishlist[] = $productId;
            session()->put('guest_wishlist', array_unique($guestWishlist));
            return true;
        }
    }

    /**
     * Remove a product from the wishlist.
     */
    public static function remove(int $productId): void
    {
        if (auth()->check()) {
            $user = auth()->user();
            $user->clearWishlistMemoization();
            $user->wishlistProducts()->detach($productId);
            return;
        }

        $guestWishlist = session()->get('guest_wishlist', []);
        $set = array_flip($guestWishlist);
        unset($set[$productId]);
        session()->put('guest_wishlist', array_keys($set));
    }

    /**
     * Get total count of items in wishlist.
     */
    public static function count(): int
    {
        if (auth()->check()) {
            return auth()->user()->wishlistProducts()->count();
        }

        return count(session()->get('guest_wishlist', []));
    }

    /**
     * Get all wishlisted products via a single batch database query.
     *
     * @return Collection<int, Product>
     */
    public static function getProducts(): Collection
    {
        if (auth()->check()) {
            return auth()->user()->wishlistProducts()
                ->with(['brand', 'primaryImage', 'images', 'category'])
                ->get();
        }

        $ids = session()->get('guest_wishlist', []);
        if (empty($ids)) {
            return new Collection();
        }

        return Product::with(['brand', 'primaryImage', 'images', 'category'])
            ->whereIn('id', $ids)
            ->get();
    }
}
