<?php

namespace App\Mail;

use App\Models\MailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

/**
 * RoomInvitationMail
 *
 * Mailable class for sending video conference room invitations.
 * Contains conference details and a join link for participants.
 */
class RoomInvitationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The recipient user
     *
     * @var mixed
     */
    protected $user;

    /**
     * The conference room information
     *
     * @var mixed
     */
    protected $info;

    /**
     * Create a new message instance.
     *
     * @param  mixed  $user  The recipient user
     * @param  mixed  $info  The conference room information
     * @return void
     */
    public function __construct($user, $info)
    {
        $this->queue = 'emails';
        $this->user = $user;
        $this->info = $info;
    }

    /**
     * Build the message.
     *
     * Generates a conference room URL based on user role,
     * retrieves the room invitation template, and replaces placeholders
     * with user details and conference information.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->user->email;
        $groupID = $this->user->usergroup_id;
        // append conference url
        $url = url('teacher/video-conference/'.$this->info->slug);
        if (Auth::user()->usergroup_id == '5') {
            $url = url('student/video-conference/'.$this->info->slug);
        }

        $mailtemplate = MailTemplate::where([['name', 'room_invitation'], ['status', 'active']])->first();
        $message = $mailtemplate->mail_content;
        $subject = $mailtemplate->subject;
        $message = str_replace(':name', $this->user->name, $message);
        $message = str_replace(':title', $this->info->name, $message);
        $message = str_replace(':description', $this->info->description, $message);
        $message = str_replace(':message', '<a href="'.$url.'">Join Here</a>', $message);

        return $this->markdown('emails.mailcontent')
            ->subject($subject)
            ->with([
                'content' => $message,
            ])
            ->withSwiftMessage(function ($message) use ($email) {
                $message->getHeaders()
                    ->addTextHeader('user_email', $email);
            });
    }
}
