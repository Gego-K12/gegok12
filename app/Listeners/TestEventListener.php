<?php

namespace App\Listeners;

use App\Events\TestEvent;
use App\Mail\InvitesMail;
use Illuminate\Support\Facades\Mail;

class TestEventListener
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
    public function handle(TestEvent $event)
    {
        //
        Mail::to($event->invite->email)->send(new InvitesMail($event->invite));
    }
}
