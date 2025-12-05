<?php

namespace App\Listeners;

use App\Events\UserGroupStatusChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\GroupStatusNotification;

class SendGroupStatusNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserGroupStatusChanged $event)
    {
        $event->user->notify(
            new GroupStatusNotification($event->group, $event->status)
        );
    }
}
