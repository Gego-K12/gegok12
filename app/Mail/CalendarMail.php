<?php

namespace App\Mail;

use App\Models\Events;
use App\Models\MailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * CalendarMail
 *
 * Mailable class for sending calendar event notifications.
 * Includes event details such as title, location, category, and date range.
 */
class CalendarMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The events instance
     *
     * @var Events
     */
    public $events;

    /**
     * Create a new message instance.
     *
     * @param  Events  $events  The event instance
     * @return void
     */
    public function __construct($events)
    {
        $this->queue = 'emails';
        $this->events = $events;
    }

    /**
     * Build the message.
     *
     * Retrieves the calendar event template and replaces placeholders
     * with event details including title, location, category, and dates.
     *
     * @return $this
     */
    public function build()
    {
        $template = MailTemplate::where([['name', 'calendar_event'], ['status', 'active']])->first();
        $subject = $template->subject;
        $mail_content = $template->mail_content;

        $mail_content = str_replace(':title', $this->events->title, $mail_content);
        $mail_content = str_replace(':location', $this->events->location, $mail_content);
        $mail_content = str_replace(':category', $this->events->category, $mail_content);
        $mail_content = str_replace(':start_date', $this->events->start_date, $mail_content);
        $mail_content = str_replace(':end_date', $this->events->end_date, $mail_content);

        return $this->markdown('emails.mailcontent')
            ->subject($subject)
            ->with([
                'content' => $mail_content,
            ]);
    }
}
