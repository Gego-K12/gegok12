<?php

namespace App\Mail;

use App\Models\MailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * NewMessage
 *
 * Mailable class for sending new message notifications.
 * Delivers custom messages and notifications to users with custom subject lines.
 */
class NewMessage extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The message content
     *
     * @var string
     */
    protected $content;

    /**
     * The message subject
     *
     * @var string
     */
    protected $sub;

    /**
     * The recipient user
     *
     * @var mixed
     */
    protected $user;

    /**
     * Create a new message instance.
     *
     * @param  string  $subject  The email subject
     * @param  string  $content  The email body content
     * @param  mixed  $user  The recipient user
     * @return void
     */
    public function __construct($subject, $content, $user)
    {
        $this->queue = 'emails';
        $this->content = $content;
        $this->sub = $subject;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * Builds the email message from the new_message template,
     * replaces content placeholders, and adds custom headers.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->user->email;
        $mailtemplate = MailTemplate::where([['name', 'new_message'], ['status', 'active']])->first();
        $message = $mailtemplate->mail_content;
        $message = str_replace(':name', $this->user->name, $message);
        $message = str_replace(':message', $this->content, $message);

        return $this->markdown('emails.mailcontent')
            ->subject($this->sub)
            ->with([
                'content' => $message,
            ])
            ->withSwiftMessage(function ($message) use ($email) {
                $message->getHeaders()
                    ->addTextHeader('user_email', $email);
            });
    }
}
