<?php

namespace App\Mail;

use App\Models\MailTemplate;
use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * AfterSubscriptionExpiredMail
 *
 * Mailable class for sending notifications after a school subscription has expired.
 * Contains subscription details including school name, user name, and expiration date.
 */
class AfterSubscriptionExpiredMail extends Mailable implements ShouldQueue
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
     * @param  Subscription  $subscription  The expired subscription
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
     * Retrieves the subscription expiration template and replaces
     * placeholders with school name, user name, and expiration date.
     *
     * @return $this
     */
    public function build()
    {
        $template = MailTemplate::where([['name', 'site_expired_mail'], ['status', 'active']])->first();
        $subject = $template->subject;
        $mail_content = $template->mail_content;

        $mail_content = str_replace(':school_name', $this->subscription->school->name, $mail_content);
        $mail_content = str_replace(':name', $this->subscription->user->name, $mail_content);
        $mail_content = str_replace(':end_date', $this->subscription->end_date, $mail_content);

        return $this->markdown('emails.mailcontent')
            ->subject($subject)
            ->with([
                'content' => $mail_content,
            ]);
    }
}
