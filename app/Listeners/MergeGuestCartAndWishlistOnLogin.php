<?php

namespace App\Listeners;

use App\Support\CartManager;
use App\Support\WishlistManager;
use Illuminate\Auth\Events\Authenticated;
use Illuminate\Auth\Events\Login;

class MergeGuestCartAndWishlistOnLogin
{
    /**
     * Handle authentication/login event.
     */
    public function handle(Login|Authenticated $event): void
    {
        if ($event->user) {
            CartManager::syncGuestCartToUser($event->user);
            WishlistManager::syncGuestWishlistToUser($event->user);
        }
    }
}
