<?php

namespace App\Listeners\Notification;

use App\Events\Notification\TeacherNotificationEvent;
use App\Notifications\NewMessageNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Users\TeacherUser;
use App\Models\User;
use Notification;

class TeacherNotificationEventListener implements ShouldQueue
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
     * @param  TeacherNotificationEvent  $event
     * @return void
     */
    public function handle(TeacherNotificationEvent $event)
    {
        //
        $teachers = TeacherUser::where('school_id',$event->data['school_id'])->ByRole(5)->get();
        foreach($teachers as $teacher)
        {
            Notification::send($teacher, new NewMessageNotification($event->data['details']));
        }
    }
}