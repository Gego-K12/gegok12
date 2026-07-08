<?php

namespace App\Mail;

use App\Models\MailTemplate;
use App\Models\Reminder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * ReminderMail
 *
 * Mailable class for sending event reminder notifications.
 * Contains event details such as title, description, location, and schedule.
 */
class ReminderMail extends Mailable implements ShouldQueue
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
     * @param  Reminder  $reminder  The reminder instance
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
     * Retrieves the event reminder template and replaces placeholders
     * with event details including school name, title, description, location, and dates.
     *
     * @return $this
     */
    public function build()
    {
        $template = MailTemplate::where([['name', 'event_reminder'], ['status', 'active']])->first();
        $subject = $template->subject;
        $mail_content = $template->mail_content;
        $mail_content = str_replace(':school_name', $this->reminder->school->name, $mail_content);
        $mail_content = str_replace(':title', $this->reminder->events->title, $mail_content);
        $mail_content = str_replace(':description', $this->reminder->events->description, $mail_content);
        $mail_content = str_replace(':location', $this->reminder->events->location, $mail_content);
        $mail_content = str_replace(':start_date', $this->reminder->events->start_date, $mail_content);
        $mail_content = str_replace(':end_date', $this->reminder->events->end_date, $mail_content);

        return $this->markdown('emails.mailcontent')
            ->subject($subject)
            ->with([
                'content' => $mail_content,
            ]);
    }
}
