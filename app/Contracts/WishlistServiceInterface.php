<?php

namespace App\Contracts;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

interface WishlistServiceInterface
{
    public static function contains(int $productId): bool;

    public static function toggle(int $productId): bool;

    public static function remove(int $productId): void;

    public static function count(): int;

    /**
     * @return Collection<int, Product>
     */
    public static function getProducts(): Collection;
}
