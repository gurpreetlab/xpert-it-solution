<?php

namespace App\View\Components\Shop;

use App\Models\Category;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

class Categories extends Component
{
    public Collection $categories;

    private const CACHE_KEY = "shop:categories";
    private const FRESH_TTL = 1800; // 30 minutes
    private const GRACE_TTL = 3600; // 60 minutes

    public function __construct()
    {
        $this->categories = $this->getCategories();
    }

    private function getCategories(): Collection
    {
        // 1. Raw array DTO cache (Safe from class serialization & HTML rendering issues)
        $cachedCategories = Cache::flexible(
            self::CACHE_KEY,
            [self::FRESH_TTL, self::GRACE_TTL],
            function (): array {
                return Category::query()
                    ->select("name", "slug")
                    ->get()
                    ->map(function ($category) {
                        return [
                            "name" => $category->name,
                            "slug" => $category->slug,
                            "icon" =>
                                config("category-icons.{$category->slug}") ??
                                config("category-icons.default"),
                        ];
                    })
                    ->toArray();
            },
        );

        // 2. Convert primitive array back to light stdClass collection
        return collect($cachedCategories)->map(fn($item) => (object) $item);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view("components.shop.categories");
    }
}
