<?php

namespace App\Providers;

use App\Contracts\CartServiceInterface;
use App\Contracts\WishlistServiceInterface;
use App\Events\OrderPlaced;
use App\Listeners\SendAdminOrderNotificationListener;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Observers\BrandObserver;
use App\Observers\CategoryObserver;
use App\Observers\ProductObserver;
use App\Support\CartManager;
use App\Support\WishlistManager;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    #[\Override]
    public function register(): void
    {
        $this->app->bind(
            WishlistServiceInterface::class,
            WishlistManager::class
        );

        $this->app->bind(
            CartServiceInterface::class,
            CartManager::class
        );

        $this->app->bind(
            \App\Contracts\PaymentGatewayInterface::class,
            \App\Services\Payment\RazorpayPaymentGateway::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('production')) {
            \URL::forceScheme('https');
        }

        $this->configureDefaults();

        Brand::observe(BrandObserver::class);
        Category::observe(CategoryObserver::class);
        Product::observe(ProductObserver::class);

        Event::listen(
            OrderPlaced::class,
            SendAdminOrderNotificationListener::class
        );

        \Illuminate\Support\Facades\Event::listen(
            \Illuminate\Auth\Events\Login::class,
            \App\Listeners\MergeGuestCartAndWishlistOnLogin::class
        );

        \Illuminate\Support\Facades\Event::listen(
            \Illuminate\Auth\Events\Authenticated::class,
            \App\Listeners\MergeGuestCartAndWishlistOnLogin::class
        );
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(8)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
