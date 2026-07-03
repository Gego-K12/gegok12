<?php

namespace App\Listeners;

use App\Events\PushEvent;
use App\Models\User;
use App\Notifications\SendDeviceNotification;
// use App\Traits\SendPushNotification;
use App\Notifications\SendTeacherNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class PushEventListener implements ShouldQueue
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
    public function handle(PushEvent $event)
    {
        $users = User::where('school_id', $event->data['school_id'])->whereNotNull('platform_token')->get();

        foreach ($users as $user) {
            if ($user->usergroup_id == 5) {
                $user->notify(new SendTeacherNotification($event->data, $user->platform_token));
            }

            if ($user->usergroup_id == 7) {
                $user->notify(new SendDeviceNotification($event->data, $user->platform_token));

            }
        }
        // Mail::to($event->queue->to)->queue(new ReminderMail($event->queue));
    }
}
