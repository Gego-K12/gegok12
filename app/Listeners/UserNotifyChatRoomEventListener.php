<?php

namespace App\Listeners;

use App\Events\UserNotifyChatRoomEvent;
use App\Traits\ReminderProcess;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserNotifyChatRoomEventListener implements ShouldQueue
{
    use ReminderProcess;

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
    public function handle(UserNotifyChatRoomEvent $event)
    {
        //
        $this->createReminder($event->school_id, $event->from, $event->mobile_no, $event->subject, $event->message, $event->entity_id, $event->entity_name, $event->via, $event->data, $event->executed_at);
    }
}
