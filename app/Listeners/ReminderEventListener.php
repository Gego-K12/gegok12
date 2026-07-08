<?php

namespace App\Listeners;

use App\Events\ReminderEvent;
use App\Models\User;
use App\Traits\ReminderProcess;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReminderEventListener implements ShouldQueue
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
     * @param  ReminderEvent  $events
     * @return void
     */
    public function handle(ReminderEvent $event)
    {
        if ($event->via == 'mail') {
            $users = User::where('school_id', $event->school_id)->ByRole('5')->pluck('email')->toArray();
        } elseif ($event->via == 'sms') {
            $users = User::where('school_id', $event->school_id)->ByRole('5')->pluck('mobile_no')->toArray();
        } else {
            $users = User::where('school_id', $event->school_id)->ByRole('5')->pluck('email')->toArray();
        }

        foreach ($users as $user) {
            $this->createReminder($event->school_id, $event->from, $user, $event->subject, $event->message, $event->entity_id, $event->entity_name, $event->via, $event->data, $event->executed_at);
        }
    }
}
