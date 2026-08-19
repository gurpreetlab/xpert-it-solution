<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface CartServiceInterface
{
    public static function add(int $productId, int $quantity = 1): bool;

    public static function updateQuantity(int|string $itemId, int $quantity): bool;

    public static function removeItem(int|string $itemId): void;

    public static function clear(): void;

    public static function count(): int;

    /**
     * @return Collection<int, mixed>
     */
    public static function getCartItems(): Collection;
}
