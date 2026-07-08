<?php

namespace App\Mail;

use App\Models\MailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * AdmissionApprovalMail
 *
 * Mailable class for sending admission approval confirmation emails.
 * Contains application details and school information to confirm
 * successful admission submission or approval.
 */
class AdmissionApprovalMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The admission data array
     *
     * @var array
     */
    public $data;

    /**
     * Create a new message instance.
     *
     * @param  array  $data  Array containing application_no and school_name
     * @return void
     */
    public function __construct($data)
    {
        $this->queue = 'emails';
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * Retrieves the admission confirmation template and replaces
     * placeholders with application number and school name.
     *
     * @return $this
     */
    public function build()
    {
        $template = MailTemplate::where([['name', 'admission_confirmation'], ['status', 'active']])->first();

        $mail_content = $template->mail_content;

        $mail_content = str_replace(':application_no', $this->data['application_no'], $mail_content);
        $mail_content = str_replace(':school_name', $this->data['school_name'], $mail_content);

        return $this->markdown('emails.mailcontent')->subject($template->subject)->with(['content' => $mail_content]);
    }
}
