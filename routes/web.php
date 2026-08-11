<?php

use App\Http\Controllers\Admin\InvoiceController;
// Admin
use App\Livewire\Admin\Brands\Index as BrandIndex;
use App\Livewire\Admin\Categories\Index as CategoryIndex;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Icecat\Import as IcecatImport;
use App\Livewire\Admin\ContactMessages\Index as ContactMessagesIndex;
use App\Livewire\Admin\Invoices\Index as InvoiceIndex;
use App\Livewire\Admin\Orders\Index as OrderIndex;
use App\Livewire\Admin\Orders\Show as OrderShow;
use App\Livewire\Admin\Products\Create as ProductCreate;
use App\Livewire\Admin\Products\Edit as ProductEdit;
use App\Livewire\Admin\Products\Index as ProductIndex;
use App\Livewire\Admin\Products\Show as ProductShow;
use App\Livewire\Shop\About;
// Customer
use App\Livewire\Shop\Cart as ShopCart;
use App\Livewire\Shop\Checkout;
use App\Livewire\Shop\Contact;
use App\Livewire\Shop\Home;
use App\Livewire\Shop\OrderConfirmation as ShopOrderConfirmation;
use App\Livewire\Shop\Orders as ShopOrders;
use App\Livewire\Shop\ProductDetail;
use App\Livewire\Shop\Products as ShopProducts;
use App\Livewire\Shop\Wishlist;
use Illuminate\Support\Facades\Route;

Route::get('/', Home::class)->name('home');
Route::get('/about', About::class)->name('about');
Route::get('/contact', Contact::class)->name('contact');
Route::get('/products', ShopProducts::class)->name('shop.products');
Route::get('/products/{slug}', ProductDetail::class)->name(
    'shop.product.details',
);

Route::middleware(['auth', 'verified', 'role:customer'])->group(function () {
    Route::get('/cart', ShopCart::class)->name('shop.cart');

    Route::get('/cart/checkout', Checkout::class)->name('shop.checkout');

    Route::get('/orders', ShopOrders::class)->name('shop.orders');

    Route::get(
        '/order/{order:order_number}/confirmation',
        ShopOrderConfirmation::class,
    )->name('shop.order.confirmation');

    Route::get('/wishlist', Wishlist::class)->name('shop.wishlist');
});

// super-admin routes
Route::middleware(['auth', 'verified', 'role:super-admin'])
    ->prefix('super-admin')
    ->group(function () {
        // Route::view("dashboard", "dashboard")->name("dashboard");
        Route::get('/dashboard', Dashboard::class)->name('dashboard');

        Route::prefix('categories')->group(function () {
            Route::get('/', CategoryIndex::class)->name(
                'dashboard.categories.index',
            );
        });

        Route::prefix('brands')->group(function () {
            Route::get('/', BrandIndex::class)->name('dashboard.brands.index');
        });

        Route::prefix('products')->group(function () {
            Route::get('/', ProductIndex::class)->name(
                'dashboard.products.index',
            );
            Route::get('/create', ProductCreate::class)->name(
                'dashboard.products.create',
            );
            Route::get('/{product}', ProductShow::class)->name(
                'dashboard.products.show',
            );
            Route::get('/{product}/edit', ProductEdit::class)->name(
                'dashboard.products.edit',
            );
        });

        // Orders
        Route::prefix('orders')->group(function () {
            Route::get('/', OrderIndex::class)->name('dashboard.orders.index');
            Route::get('/{order}', OrderShow::class)->name(
                'dashboard.orders.show',
            );
            Route::get('/{order}/invoice', [
                InvoiceController::class,
                'downloadForOrder',
            ])->name('dashboard.orders.invoice');
        });

        // Invoices
        Route::prefix('invoices')->group(function () {
            Route::get('/', InvoiceIndex::class)->name(
                'dashboard.invoices.index',
            );
            Route::get('/{invoice}/download', [
                InvoiceController::class,
                'download',
            ])->name('dashboard.invoices.download');
        });

        Route::prefix('icecat')->group(function () {
            Route::get('/', IcecatImport::class)->name('dashboard.icecat.import');
        });

        Route::prefix('contact-messages')->group(function () {
            Route::get('/', ContactMessagesIndex::class)->name('dashboard.contact-messages.index');
        });
    });

// Legal Pages
Route::livewire('/privacy-policy', 'pages::shop.privacy-policy')->name('shop.privacy-policy');
Route::livewire('/terms-and-conditions', 'pages::shop.terms-and-conditions')->name('shop.terms-and-conditions');
Route::livewire('/refund-policy', 'pages::shop.refund-policy')->name('shop.refund-policy');
Route::livewire('/shipping-policy', 'pages::shop.shipping-policy')->name('shop.shipping-policy');

require __DIR__.'/settings.php';
