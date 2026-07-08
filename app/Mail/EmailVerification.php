<?php

namespace App\Mail;

use App\Models\MailTemplate;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * EmailVerification
 *
 * Mailable class for sending email verification messages.
 * Contains a verification link that users must click to verify their email address.
 */
class EmailVerification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The user instance
     *
     * @var User
     */
    protected $user;

    /**
     * Create a new message instance.
     *
     * @param  User  $user  The user to verify email for
     * @return void
     */
    public function __construct(User $user)
    {
        $this->queue = 'emails';
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * Generates a verification URL from the user's verification code
     * and replaces template placeholders with the URL and user name.
     *
     * @return $this
     */
    public function build()
    {
        $template = MailTemplate::where([['name', 'email_verification'], ['status', 'active']])->first();

        $url = url('/emailverification/'.$this->user->email_verification_code);

        $subject = $template->subject;
        $mail_content = $template->mail_content;

        $mail_content = str_replace(':url', $url, $mail_content);
        $mail_content = str_replace(':name', $this->user->name, $mail_content);

        return $this->markdown('emails.mailcontent')
            ->subject($subject)
            ->with([
                'content' => $mail_content,
            ]);
    }
}
