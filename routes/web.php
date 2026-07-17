<?php

use App\Livewire\Admin\Brands\Index as BrandIndex;
use App\Livewire\Admin\Categories\Index as CategoryIndex;
use App\Livewire\Admin\Products\Create as ProductCreate;
use App\Livewire\Admin\Products\Edit as ProductEdit;
use App\Livewire\Admin\Products\Index as ProductIndex;
use App\Livewire\Admin\Products\Show as ProductShow;
use App\Livewire\Shop\Home;
use App\Livewire\Shop\ProductDetail;
use Illuminate\Support\Facades\Route;

Route::get('/', Home::class)->name('home');
Route::get('/{slug}', ProductDetail::class)->name('shop.product.details');

// super-admin routes
Route::middleware(['auth', 'verified', 'role:super-admin'])->prefix('super-admin')->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::prefix('categories')->group(function () {
        Route::get('/', CategoryIndex::class)->name('dashboard.categories.index');
    });

    Route::prefix('brands')->group(function () {
        Route::get('/', BrandIndex::class)->name('dashboard.brands.index');
    });

    Route::prefix('products')->group(function () {
        Route::get('/', ProductIndex::class)->name('dashboard.products.index');
        Route::get('/create', ProductCreate::class)->name('dashboard.products.create');
        Route::get('/{product}', ProductShow::class)->name('dashboard.products.show');
        Route::get('/{product}/edit', ProductEdit::class)->name('dashboard.products.edit');
    });
});

require __DIR__.'/settings.php';
