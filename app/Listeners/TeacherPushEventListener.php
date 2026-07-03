<?php

namespace App\Listeners;

use App\Events\TeacherPushEvent;
use App\Models\Users\TeacherUser;
// use App\Traits\SendPushNotification;
use App\Notifications\SendTeacherNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class TeacherPushEventListener implements ShouldQueue
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
    public function handle(TeacherPushEvent $event)
    {
        //
        $users = TeacherUser::where('school_id', $event->data['school_id'])->ByRole(5)->whereNotNull('platform_token')->get();

        foreach ($users as $user) {
            // $this->sendNotification($event->data,$user->platform_token);
            $user->notify(new SendTeacherNotification($event->data, $user->platform_token));
        }
    }
}
