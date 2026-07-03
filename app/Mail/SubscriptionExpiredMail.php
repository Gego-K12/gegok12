<?php

namespace App\Mail;

use App\Models\MailTemplate;
use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * SubscriptionExpiredMail
 *
 * Mailable class for sending subscription expiration alerts.
 * Notifies administrators when a school's subscription is expiring and needs renewal.
 */
class SubscriptionExpiredMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The subscription instance
     *
     * @var Subscription
     */
    public $subscription;

    /**
     * Create a new message instance.
     *
     * @param  Subscription  $subscription  The expiring subscription
     * @return void
     */
    public function __construct(Subscription $subscription)
    {
        $this->queue = 'emails';
        $this->subscription = $subscription;
    }

    /**
     * Build the message.
     *
     * Retrieves the expiration alert template and replaces placeholders
     * with school name, user name, expiration date, and renewal URL.
     *
     * @return $this
     */
    public function build()
    {
        $template = MailTemplate::where([['name', 'expired_approve_alert'], ['status', 'active']])->first();

        $subject = $template->subject;
        $mail_content = $template->mail_content;

        $mail_content = str_replace(':school_name', $this->subscription->school->name, $mail_content);
        $mail_content = str_replace(':name', $this->subscription->user->name, $mail_content);
        $mail_content = str_replace(':end_date', $this->subscription->end_date, $mail_content);
        $mail_content = str_replace(':url', url('/pricing'), $mail_content);

        return $this->markdown('emails.mailcontent')
            ->subject($subject)
            ->with([
                'content' => $mail_content,
            ]);
    }
}
