<?php

namespace App\Listeners;

use App\Events\VerificationMailEvent;
use App\Mail\EmailVerification;
use Illuminate\Support\Facades\Mail;

class VerificationMailEventListener
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
    public function handle(VerificationMailEvent $event)
    {
        //
        Mail::to($event->user->email)->queue(new EmailVerification($event->user));
    }
}
