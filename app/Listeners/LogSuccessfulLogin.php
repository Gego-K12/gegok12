<?php

namespace App\Listeners;

use App\Mail\LoggedInMail;
use App\Traits\Common;
use App\Traits\LogActivity;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class LogSuccessfulLogin implements ShouldQueue
{
    use Common;
    use LogActivity;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(Login $event)// Login
    {
        //
        $ip = $this->getRequestIP();
        $this->doActivityLog(
            $event->user,
            $event->user,
            ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
            LOGNAME_LOGIN,
            'Logged In'
        );

        if (env('MAIL_STATUS') == 'on') {
            if ($event->user->email != null) {
                Mail::to($event->user->email)->queue(new LoggedInMail($event->user));
            }
        }
    }
}
