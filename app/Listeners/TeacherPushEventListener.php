<?php

namespace App\Listeners;

use App\Events\TeacherPushEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
//use App\Traits\SendPushNotification;
use App\Models\User;
use App\Notifications\SendTeacherNotification;
use App\Models\Users\TeacherUser;

class TeacherPushEventListener implements ShouldQueue
{
    //use SendPushNotification;
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
     * @param  TeacherPushEvent  $event
     * @return void
     */
    public function handle(TeacherPushEvent $event)
    {
        //
        $users = TeacherUser::where('school_id',$event->data['school_id'])->ByRole(5)->whereNotNull('platform_token')->get();

        foreach($users as $user)
        {
           // $this->sendNotification($event->data,$user->platform_token);
                  $user->notify(new SendTeacherNotification($event->data,$user->platform_token));
        }
    }
}