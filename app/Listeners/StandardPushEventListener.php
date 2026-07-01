<?php

namespace App\Listeners;

use App\Events\StandardPushEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use App\Traits\SendPushNotification;
use App\Models\StudentParentLink;
use App\Models\User;
use App\Notifications\SendDeviceNotification;
use App\Models\Users\StudentUser;

class StandardPushEventListener 
{

     use SendPushNotification;
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
     * @param  StandardPushEvent  $event
     * @return void
     */
    public function handle(StandardPushEvent $event)
    {
        $standard_id=$event->data['standard_id'];

        $users=StudentUser::where('school_id',$event->data['school_id'])->ByRole(6)->whereHas('studentAcademic',function ($query) use ($standard_id)
            {
                $query->where('standardLink_id',$standard_id);
            })->get();

       foreach($users as $user)
        {
            $studentParent = StudentParentLink::where('student_id',$user->id)->first();
            $parent = User::where('id',$studentParent->parent_id)->whereNotNull('platform_token')->first();
            if($parent->platform_token != null)
            {
                //$this->sendNotification($event->data,$parent->platform_token);

                 $parent->notify(new SendDeviceNotification($event->data,$parent->platform_token));
            }
        }
    }
}
