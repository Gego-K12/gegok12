<?php

namespace App\Listeners;

use App\Events\SinglePushEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
//use App\Traits\SendPushNotification;
use App\Models\User;
use App\Notifications\SendDeviceNotification;
use App\Notifications\SendTeacherNotification;
class SinglePushEventListener implements ShouldQueue
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
     * @param  SinglePushEvent  $event
     * @return void
     */
    public function handle(SinglePushEvent $event)
    {
        //
        $user=User::where([
            ['school_id',$event->data['school_id']],
            ['id',$event->data['user_id']]
        ])->whereNotNull('platform_token')->first();


       //  $user=User::find(1);
       //  $user_device_token=UserDeviceToken::where('user_id',$user->id)->orderby('id','desc')->first();
       //  $data['title']="Test";
       //  $data['body']="Test Notification";
       //  $data['token']=$user_device_token->token;
       // $user->notify(new SendDeviceNotification($data));
      //  \Notification::send($user_device_token->token, new SendDeviceNotification($data));



        if($user != '')
        {
         
            if($user->usergroup_id==7)
            $user->notify(new SendDeviceNotification($event->data,$user->platform_token));
            //$this->sendNotification($event->data,$user->platform_token);

            if($user->usergroup_id==5)
                 $user->notify(new SendTeacherNotification($event->data,$user->platform_token));

        }
    }
}