<?php

namespace App\Mail;

use App\Models\MailTemplate;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * ResetPassword
 *
 * Mailable class for sending password reset confirmation emails.
 * Contains a password reset link that users must click to create a new password.
 */
class ResetPassword extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The password reset token
     *
     * @var string
     */
    protected $token;

    /**
     * The user instance
     *
     * @var User
     */
    protected $userdetails;

    /**
     * Create a new message instance.
     *
     * @param  User  $userdetails  The user requesting password reset
     * @param  string  $token  The password reset token
     * @return void
     */
    public function __construct(User $userdetails, $token)
    {
        $this->queue = 'emails';
        $this->userdetails = $userdetails;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * Generates a password reset URL from the token and replaces
     * template placeholders with user details and reset link.
     *
     * @return $this
     */
    public function build()
    {
        $user = User::where('id', $this->userdetails->id)->first();

        $mailtemplate = MailTemplate::where([['name', 'reset_password'], ['status', 'active']])->first();
        $url = url('/password/reset/'.$this->token);
        $subject = $mailtemplate->subject;
        $mail_content = $mailtemplate->mail_content;
        $mail_content = str_replace(':name', $this->userdetails->FullName, $mail_content);
        $mail_content = str_replace(':resetlink', $url, $mail_content);
        $mail_content = str_replace(':url', $url, $mail_content);

        return $this->markdown('emails.mailcontent')
            ->subject($subject)
            ->with([
                'content' => $mail_content,
            ]);
    }
}
