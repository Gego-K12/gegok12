<?php

namespace App\Mail;

use App\Models\MailTemplate;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * ChangePassword
 *
 * Mailable class for sending password change confirmation emails.
 * Notifies users when their password has been successfully changed.
 */
class ChangePassword extends Mailable implements ShouldQueue
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
     * @param  User  $user  The user whose password was changed
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
     * Retrieves the password change template and replaces
     * the placeholder with the user's full name.
     *
     * @return $this
     */
    public function build()
    {
        $mailtemplate = MailTemplate::where([['name', 'change_password'], ['status', 'active']])->first();

        $subject = $mailtemplate->subject;
        $mail_content = $mailtemplate->mail_content;
        $mail_content = str_replace(':name', $this->user->FullName, $mail_content);

        return $this->markdown('emails.mailcontent')
            ->subject($subject)
            ->with([
                'content' => $mail_content,
            ]);
    }
}
