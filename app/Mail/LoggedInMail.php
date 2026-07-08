<?php

namespace App\Mail;

use App\Models\MailTemplate;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * LoggedInMail
 *
 * Mailable class for sending login notification emails.
 * Notifies users when their account is accessed, useful for security alerts.
 */
class LoggedInMail extends Mailable implements ShouldQueue
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
     * @param  User  $user  The user who logged in
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
     * Retrieves the login template and replaces the placeholder
     * with the user's full name.
     *
     * @return $this
     */
    public function build()
    {
        $template = MailTemplate::where([['name', 'login'], ['status', 'active']])->first();
        $subject = $template->subject;
        $mail_content = $template->mail_content;

        $mail_content = str_replace(':name', $this->user->FullName, $mail_content);

        return $this->markdown('emails.mailcontent')
            ->subject($subject)
            ->with([
                'content' => $mail_content,
            ]);
    }
}
