<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Models\User;
use App\Notifications\AdminNewOrderNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendAdminOrderNotificationListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(OrderPlaced $event): void
    {
        $superAdminUsers = User::role('super-admin')->get();

        if ($superAdminUsers->isNotEmpty()) {
            Notification::send($superAdminUsers, new AdminNewOrderNotification($event->order));
        }
    }
}
