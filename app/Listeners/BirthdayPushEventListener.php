<?php

namespace App\Listeners;

use App\Events\BirthdayPushEvent;
use App\Notifications\SendDeviceNotification;
// use App\Traits\SendPushNotification;
use App\Notifications\SendTeacherNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class BirthdayPushEventListener implements ShouldQueue
{
    // use SendPushNotification;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(BirthdayPushEvent $event)
    {
        //
        // if($user->usergroup_id==7)
        // {
        //     $this->sendNotification($event->queue->data, $event->queue->user->platform_token);
        // }

        // if($user->usergroup_id==5)
        // {
        //     $this->sendTeacherNotification($event->queue->data,$event->queue->user->platform_token);
        // }

        if ($user->usergroup_id == 5) {
            $user->notify(new SendTeacherNotification($event->data, $user->platform_token));
        }

        if ($user->usergroup_id == 7) {
            $user->notify(new SendDeviceNotification($event->data, $user->platform_token));

        }
    }
}
