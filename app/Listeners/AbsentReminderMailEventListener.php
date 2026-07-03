<?php

namespace App\Listeners;

use App\Events\AbsentReminderMailEvent;
use App\Mail\AbsentReminderMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class AbsentReminderMailEventListener implements ShouldQueue
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
     * @return void
     */
    public function handle(AbsentReminderMailEvent $event)
    {
        //
        Mail::to($event->reminder->to)->queue(new AbsentReminderMail($event->reminder));
    }
}
