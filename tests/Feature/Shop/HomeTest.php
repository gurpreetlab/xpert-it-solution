<?php

use App\Livewire\Shop\Home;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Livewire\Livewire;

test('returns a successful response for the home page', function () {
    $response = $this->get(route('home'));

    $response->assertStatus(200);
    $response->assertSee('Xpert');
});

test('can search products', function () {
    $category = Category::create(['name' => 'Networking']);
    $brand = Brand::create(['name' => 'Netgear']);

    $product1 = Product::create([
        'name' => 'Netgear Nighthawk Wi-Fi Router',
        'category_id' => $category->id,
        'brand_id' => $brand->id,
        'is_active' => true,
        'sale_price' => 5000,
        'mrp' => 6000,
    ]);

    $product2 = Product::create([
        'name' => 'HP Monitor display screen',
        'category_id' => $category->id,
        'brand_id' => $brand->id,
        'is_active' => true,
        'sale_price' => 8000,
        'mrp' => 9000,
    ]);

    Livewire::test(Home::class)
        ->set('search', 'Nighthawk')
        ->assertSee($product1->name)
        ->assertDontSee($product2->name);
});

test('can filter products by category', function () {
    $category1 = Category::create(['name' => 'Networking']);
    $category2 = Category::create(['name' => 'Storage']);
    $brand = Brand::create(['name' => 'Generic']);

    $product1 = Product::create([
        'name' => 'Router Alpha',
        'category_id' => $category1->id,
        'brand_id' => $brand->id,
        'is_active' => true,
        'sale_price' => 2000,
    ]);

    $product2 = Product::create([
        'name' => 'SSD Hard Drive',
        'category_id' => $category2->id,
        'brand_id' => $brand->id,
        'is_active' => true,
        'sale_price' => 4000,
    ]);

    Livewire::test(Home::class)
        ->set('selectedCategoryId', $category1->id)
        ->assertSee($product1->name)
        ->assertDontSee($product2->name);
});

test('can filter products by brand', function () {
    $category = Category::create(['name' => 'Networking']);
    $brand1 = Brand::create(['name' => 'Brand One']);
    $brand2 = Brand::create(['name' => 'Brand Two']);

    $product1 = Product::create([
        'name' => 'Product Alpha',
        'category_id' => $category->id,
        'brand_id' => $brand1->id,
        'is_active' => true,
        'sale_price' => 1500,
    ]);

    $product2 = Product::create([
        'name' => 'Product Beta',
        'category_id' => $category->id,
        'brand_id' => $brand2->id,
        'is_active' => true,
        'sale_price' => 2500,
    ]);

    Livewire::test(Home::class)
        ->set('selectedBrandId', $brand1->id)
        ->assertSee($product1->name)
        ->assertDontSee($product2->name);
});
