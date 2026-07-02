<?php

namespace App\Listeners\Notification;

use App\Events\Notification\SingleNotificationEvent;
use App\Notifications\NewMessageNotification;
use App\Notifications\BirthdayNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Notification;

class SingleNotificationEventListener implements ShouldQueue
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
     * @param  SingleNotificationEvent  $event
     * @return void
     */
    public function handle(SingleNotificationEvent $event)
    {
        //
        if($event->data['type'] == 'birthday')
        {
            Notification::send($event->data['user'], new BirthdayNotification($event->data['details'],$event->data['user']['id']));
        }
        Notification::send($event->data['user'], new NewMessageNotification($event->data['details']));
    }
}