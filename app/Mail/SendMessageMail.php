<?php

namespace App\Mail;

use App\Models\MailTemplate;
use App\Models\SendMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * SendMessageMail
 *
 * Mailable class for sending custom mail messages with optional attachments.
 * Supports templated messages with dynamic subject and content placeholders.
 */
class SendMessageMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The send mail instance
     *
     * @var SendMail
     */
    public $sendMail;

    /**
     * Create a new message instance.
     *
     * @param  SendMail  $sendMail  The send mail instance
     * @return void
     */
    public function __construct(SendMail $sendMail)
    {
        $this->queue = 'emails';
        $this->sendMail = $sendMail;
    }

    /**
     * Build the message.
     *
     * Retrieves the send mail template, replaces placeholders with
     * the message subject, content, user name, and attachments if present.
     *
     * @return $this
     */
    public function build()
    {
        $template = MailTemplate::where([['name', 'send_mail'], ['status', 'active']])->first();
        $subject = $template->subject;
        $mail_content = $template->mail_content;

        $subject = str_replace(':subject', $this->sendMail->subject, $subject);
        $mail_content = str_replace(':message', $this->sendMail->message, $mail_content);
        $mail_content = str_replace(':name', $this->sendMail->user->name, $mail_content);
        if ($this->sendMail->attachments != '') {
            $mail_content = str_replace(':attachments', url($this->sendMail->attachments), $mail_content);
        } else {
            $mail_content = str_replace(':attachments', '', $mail_content);
        }

        return $this->markdown('emails.mailcontent')
            ->subject($subject)
            ->with([
                'content' => $mail_content,
            ]);
    }
}
