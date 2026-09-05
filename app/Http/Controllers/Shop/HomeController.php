<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function __invoke()
    {
        $categories = Cache::remember('category_names', now()->addMinutes(60), function () {
            return Category::where('is_active', true)->pluck('name')->all();
        });

        $newArrivals = Cache::remember('new_arrivals', now()->addMinutes(60), function () {
            return Product::where('is_active', true)
                ->with('primaryImage', 'brand')
                ->orderBy('created_at', 'desc')
                ->take(8)
                ->get()
                ->toArray();
        });

        return view('shop.home', [
            'categories' => $categories,
            'newArrivals' => $newArrivals,
        ]);
    }
}
