<?php

namespace App\Listeners;

use App\Events\BirthdayReminderMailEvent;
use App\Mail\BirthdayReminderMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class BirthdayReminderMailEventListener implements ShouldQueue
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
    public function handle(BirthdayReminderMailEvent $event)
    {
        //
        Mail::to($event->reminder->to)->queue(new BirthdayReminderMail($event->reminder));
    }
}
