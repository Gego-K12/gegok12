<?php

namespace App\Listeners\Notification;

use App\Events\Notification\ClassNotificationEvent;
use App\Notifications\NewMessageNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Users\StudentUser;
use App\Models\User;
use Notification;

class ClassNotificationEventListener implements ShouldQueue
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
     * @param  ClassNotificationEvent  $event
     * @return void
     */
    public function handle(ClassNotificationEvent $event)
    {
        //
        $standardLink_id=$event->data['standardLink_id'];

        $users=StudentUser::where('school_id',$event->data['school_id'])->ByRole(6)->whereHas('studentAcademic',function ($query) use ($standardLink_id)
            {
                $query->where('standardLink_id',$standardLink_id);
            })->get();
        foreach($users as $user)
        {
            Notification::send($user, new NewMessageNotification($event->data['details']));
        }
    }
}