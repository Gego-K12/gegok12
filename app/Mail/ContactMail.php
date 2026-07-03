<?php

namespace App\Mail;

use App\Models\MailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * ContactMail
 *
 * Mailable class for sending contact form submission emails.
 * Contains contact details including phone, email, role, and inquiry information.
 */
class ContactMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The contact form data
     *
     * @var mixed
     */
    protected $contact;

    /**
     * Create a new message instance.
     *
     * @param  mixed  $contact  The contact form submission data
     * @return void
     */
    public function __construct($contact)
    {
        $this->queue = 'emails';
        $this->contact = $contact;
    }

    /**
     * Build the message.
     *
     * Retrieves the contact template and replaces placeholders
     * with contact form data including phone, email, name, role, and location.
     *
     * @return $this
     */
    public function build()
    {
        $template = MailTemplate::where([['name', 'contact'], ['status', 'active']])->first();
        $subject = $template->subject;
        $mail_content = $template->mail_content;

        $mail_content = str_replace(':contact_no', $this->contact->contact_no, $mail_content);
        $mail_content = str_replace(':email', $this->contact->email, $mail_content);
        $mail_content = str_replace(':select', $this->contact->select, $mail_content);
        $mail_content = str_replace(':fullname', $this->contact->fullname, $mail_content);
        $mail_content = str_replace(':role', $this->contact->role, $mail_content);
        $mail_content = str_replace(':serve_at', $this->contact->serve_at, $mail_content);

        return $this->markdown('emails.mailcontent')
            ->subject($subject)
            ->with([
                'content' => $mail_content,
            ]);
    }
}
