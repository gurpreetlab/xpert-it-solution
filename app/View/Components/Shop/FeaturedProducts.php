<?php

namespace App\View\Components\Shop;

use App\Models\Product;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

use function Illuminate\Support\now;

class FeaturedProducts extends Component
{
    public Collection $featuredProducts;

    private const CACHE_KEY = "shop:featured_product";
    private const FRESH_TTL = 1800; // 30 minutes
    private const GRACE_TTL = 3600; // 60 minutes

    public function __construct()
    {
        $this->featuredProducts = $this->getFeaturedProducts();
    }

    private function getFeaturedProducts(): Collection
    {
        // 1. Raw Data Array Cache (Safe from Class Serialization & HTML Layout Breakages)
        $cachedProductsArray = Cache::flexible(
            self::CACHE_KEY,
            [self::FRESH_TTL, self::GRACE_TTL],
            function (): array {
                return Product::query()
                    ->where("is_featured", true)
                    ->with("primaryImage")
                    ->orderBy("created_at", "desc")
                    ->get()
                    ->map(function ($product) {
                        return [
                            "id" => $product->id,
                            "name" => $product->name,
                            "sale_price" => $product->sale_price,
                            "mrp" => $product->mrp,
                            "primary_image_path" =>
                                $product->primaryImage?->path,
                            "discount" =>
                                $product->mrp > 0
                                    ? round(
                                        (($product->mrp -
                                            $product->sale_price) /
                                            $product->mrp) *
                                            100,
                                    )
                                    : 0,
                        ];
                    })
                    ->toArray();
            },
        );

        // 2. Convert primitive arrays to fluid stdClass Collection
        return collect($cachedProductsArray)->map(fn($item) => (object) $item);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view("components.shop.featured-products");
    }
}
