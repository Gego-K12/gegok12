<?php

namespace App\Listeners;

use App\Events\AfterExpiredEvent;
use App\Mail\SubscriptionExpiredMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class AfterExpiredListener implements ShouldQueue
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
    public function handle(AfterExpiredEvent $event)
    {
        //
        Mail::to($event->subscription->user->email)->queue(new SubscriptionExpiredMail($event->subscription));
    }
}
