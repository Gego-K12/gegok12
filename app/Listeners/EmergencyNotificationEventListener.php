<?php

namespace App\Listeners;

use App\Events\EmergencyNotificationEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Traits\SendEmergencyNotification;
use App\Traits\LogActivity;
use App\Traits\Common;
use App\Models\User;

class EmergencyNotificationEventListener implements ShouldQueue
{
    use SendEmergencyNotification;
    use LogActivity;
    use Common;
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
     * @param  EmergencyNotificationEvent  $event
     * @return void
     */
    public function handle(EmergencyNotificationEvent $event)
    {
        $users=User::where([['school_id',$event->school_id],['status','active']]);
        if($event->datas->message_type=='teacher')
        {
            $users=$users->ByRole(5);

        }
        if($event->datas->message_type=='parent')
        {
            $users=$users->ByRole(7);
        }
        if($event->datas->message_type=='specific')
        {
            $search=$event->datas->standard_id;
            $users=$users->ByRole(7)->whereHas('children', function($q) use ($search){
    
                $q->whereHas('userStudent', function($q) use ($search)
                {
                    $q->ByStandard($search);
                });
            });

           
        }

        $users=$users->get();

       foreach($users as $user)
        {
            $send = $this->selectEmergencyNotification($event->datas , $event->school_id , $event->admin_email , $user , $event->admin,$user);
        }
        //
    }
}
