<?php

namespace App\Listeners\Notification;

use App\Events\Notification\SchoolNotificationEvent;
use App\Models\Users\StudentUser;
use App\Notifications\NewMessageNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Notification;

class SchoolNotificationEventListener implements ShouldQueue
{
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
    public function handle(SchoolNotificationEvent $event)
    {
        //
        $users = StudentUser::where('school_id', $event->data['school_id'])->ByRole(6)->get();
        foreach ($users as $user) {
            Notification::send($user, new NewMessageNotification($event->data['details']));
        }
    }
}
