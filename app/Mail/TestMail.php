<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * TestMail
 *
 * Mailable class for sending test emails.
 * Used for verifying mail configuration and functionality.
 */
class TestMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new test message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the test message.
     *
     * Renders a simple test message to verify that the mailing
     * system is configured and working correctly.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.mailcontent')
            ->subject('Test from GegoK12')
            ->with([
                'content' => 'Mail Send Successfully',
            ]);
    }
}
