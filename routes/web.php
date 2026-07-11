<?php

use App\Livewire\Admin\Categories\Index as CategoryIndex;
use App\Livewire\Admin\Brands\Index as BrandIndex;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

// super-admin routes
Route::middleware(['auth', 'verified', 'role:super-admin'])->prefix('super-admin')->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::prefix('categories')->group(function () {
        Route::get('/', CategoryIndex::class)->name('dashboard.categories.index');
    });

    Route::prefix('brands')->group(function () {
        Route::get('/', BrandIndex::class)->name('dashboard.brands.index');
    });
});

require __DIR__.'/settings.php';
