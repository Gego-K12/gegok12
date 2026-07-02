<?php

namespace App\Listeners;

use App\Events\BirthdayReminderMailEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\BirthdayReminderMail;

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
     * @param  BirthdayReminderMailEvent  $event
     * @return void
     */
    public function handle(BirthdayReminderMailEvent $event)
    {
        //
        Mail::to($event->reminder->to)->queue(new BirthdayReminderMail($event->reminder));
    }
}