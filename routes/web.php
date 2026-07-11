<?php

use App\Livewire\Admin\Categories\Index as CategoryIndex;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

// super-admin routes
Route::middleware(['auth', 'verified', 'role:super-admin'])->prefix('super-admin')->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::prefix('categories')->group(function () {
        Route::get('/', CategoryIndex::class)->name('dashboard.categories.index');
    });
});

require __DIR__.'/settings.php';
