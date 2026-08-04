<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;

final class ShopCache
{
    public static function categories()
    {
        return Category::query()
            ->select('id', 'name', 'slug')
            ->withCount([
                'products' => fn ($query) => $query->where('is_active', true),
            ])
            ->orderBy('name')
            ->get();
    }

    public static function brands()
    {
        return Brand::query()
            ->select('id', 'name', 'slug', 'logo')
            ->withCount([
                'products' => fn ($query) => $query->where('is_active', true),
            ])
            ->orderBy('name')
            ->get();
    }

    public static function featuredProducts()
    {
        return Product::query()
            ->select([
                'id',
                'category_id',
                'brand_id',
                'name',
                'slug',
                'sale_price',
                'mrp',
                'stock',
                'is_featured',
                'sku',
                'short_description',
            ])
            ->with([
                'category:id,name,slug',
                'brand:id,name,logo',
                'primaryImage',
            ])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->latest()
            ->limit(6)
            ->get();
    }

    public static function totalProductsCount(): int
    {
        return Product::query()->where('is_active', true)->count();
    }

    public static function productsListKey(
        ?string $categoryId,
        ?string $brandId,
        string $sort,
        int $page,
    ): string {
        return '';
    }

    public static function rememberProducts(
        string $key,
        \Closure $callback,
    ): mixed {
        return $callback();
    }

    public static function flushCatalog(): void
    {
        // No-op
    }

    public static function flushProducts(): void
    {
        // No-op
    }

    public static function flush(): void
    {
        // No-op
    }
}
