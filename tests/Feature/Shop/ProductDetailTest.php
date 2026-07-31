<?php

use App\Livewire\Shop\ProductDetail;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductSpecification;
use Livewire\Livewire;

test('product detail page renders successfully with real product data and images', function () {
    $category = Category::create(['name' => 'Networking']);
    $brand = Brand::create(['name' => 'Netgear']);

    $product = Product::create([
        'name' => 'Netgear Nighthawk AX1800 Wi-Fi 6 Router',
        'category_id' => $category->id,
        'brand_id' => $brand->id,
        'is_active' => true,
        'sale_price' => 7500,
        'mrp' => 9000,
        'stock' => 10,
        'short_description' => 'Wi-Fi 6 dual-band gigabit router',
    ]);

    ProductImage::create([
        'product_id' => $product->id,
        'path' => 'products/router-main.png',
        'is_primary' => true,
        'sort_order' => 0,
    ]);

    ProductSpecification::create([
        'product_id' => $product->id,
        'key' => 'Wi-Fi Standard',
        'value' => 'Wi-Fi 6 (802.11ax)',
        'sort_order' => 0,
    ]);

    $response = $this->get(route('shop.product.details', $product->slug));

    $response->assertStatus(200);
    $response->assertSee('Netgear Nighthawk AX1800 Wi-Fi 6 Router');
    $response->assertSee('Wi-Fi Standard');
    $response->assertSee('Wi-Fi 6 (802.11ax)');
});

test('can select image and change quantity', function () {
    $category = Category::create(['name' => 'CCTV']);

    $product = Product::create([
        'name' => 'CP Plus Bullet Camera',
        'category_id' => $category->id,
        'is_active' => true,
        'sale_price' => 2500,
        'stock' => 5,
    ]);

    $img1 = ProductImage::create([
        'product_id' => $product->id,
        'path' => 'products/camera-front.png',
        'is_primary' => true,
    ]);

    $img2 = ProductImage::create([
        'product_id' => $product->id,
        'path' => 'products/camera-side.png',
        'is_primary' => false,
    ]);

    Livewire::test(ProductDetail::class, ['slug' => $product->slug])
        ->assertSet('selectedImage', 'products/camera-front.png')
        ->call('selectImage', 'products/camera-side.png')
        ->assertSet('selectedImage', 'products/camera-side.png')
        ->call('incrementQuantity')
        ->assertSet('quantity', 2)
        ->call('decrementQuantity')
        ->assertSet('quantity', 1);
});
