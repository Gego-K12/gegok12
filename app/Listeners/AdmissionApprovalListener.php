<?php

namespace App\Listeners;

use App\Events\AdmissionApprovalEvent;
use App\Mail\AdmissionApprovalMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class AdmissionApprovalListener implements ShouldQueue
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
    public function handle(AdmissionApprovalEvent $event)
    {
        //
        Mail::to($event->data['email'])->queue(new AdmissionApprovalMail($event->data));
    }
}
