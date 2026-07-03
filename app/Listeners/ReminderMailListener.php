<?php

namespace App\Listeners;

use App\Events\ReminderMailEvent;
use App\Mail\ReminderMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class ReminderMailListener implements ShouldQueue
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
    public function handle(ReminderMailEvent $event)
    {
        Mail::to($event->reminder->to)->queue(new ReminderMail($event->reminder));
    }
}
