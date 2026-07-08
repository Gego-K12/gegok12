<?php

namespace App\Mail;

use App\Models\MailTemplate;
use App\Models\Reminder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * BirthdayReminderMail
 *
 * Mailable class for sending birthday reminder notifications.
 * Sends personalized birthday greetings and messages to recipients.
 */
class BirthdayReminderMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The reminder instance
     *
     * @var Reminder
     */
    public $reminder;

    /**
     * Create a new message instance.
     *
     * @param  Reminder  $reminder  The reminder instance containing subject and message data
     * @return void
     */
    public function __construct(Reminder $reminder)
    {
        $this->queue = 'emails';
        $this->reminder = $reminder;
    }

    /**
     * Build the message.
     *
     * Retrieves the birthday reminder template and replaces
     * placeholders with reminder message content.
     *
     * @return $this
     */
    public function build()
    {
        $template = MailTemplate::where([['name', 'birthday_reminder'], ['status', 'active']])->first();

        $mail_content = $template->mail_content;

        $subject = $this->reminder->data['subject'];

        $mail_content = str_replace(':message', $this->reminder->data['message'], $mail_content);

        return $this->markdown('emails.mailcontent')
            ->subject($subject)
            ->with([
                'content' => $mail_content,
            ]);
    }
}
