<?php

namespace App\Mail;

use App\Models\MailTemplate;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * AdminNotifyNewUserMail
 *
 * Mailable class for notifying administrators about newly registered users.
 * Sends an email notification containing the new user's email address.
 */
class AdminNotifyNewUserMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The user instance
     *
     * @var User|array
     */
    protected $user;

    /**
     * Create a new message instance.
     *
     * @param  User|array  $user  The newly registered user
     * @return void
     */
    public function __construct($user)
    {
        $this->queue = 'emails';
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * Retrieves the 'new_user_register' template and replaces the email
     * placeholder with the user's email address.
     *
     * @return $this
     */
    public function build()
    {
        $template = MailTemplate::where([['name', 'new_user_register'], ['status', 'active']])->first();
        $subject = $template->subject;
        $mail_content = $template->mail_content;
        $mail_content = str_replace(':mail', $this->user->email, $mail_content);

        return $this->markdown('emails.mailcontent')
            ->subject($subject)
            ->with([
                'content' => $mail_content,
            ]);
    }
}
