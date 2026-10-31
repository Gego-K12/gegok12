<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Login;
use App\Traits\LogActivity;
use App\Mail\LoggedInMail;
use App\Traits\Common;

class LogSuccessfulLogin implements ShouldQueue
{
    use LogActivity;
    use Common;
    
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
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)//Login
    {
        //
        $ip= $this->getRequestIP();
        $this->doActivityLog(
            $event->user,
            $event->user,
            ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown client'],
            LOGNAME_LOGIN,
            'Logged In'
        );
        
        if(env('MAIL_STATUS') == 'on'
            && (!config('academy_backend.enabled') || config('academy_backend.login_email_notifications')))
        {
            if($event->user->email != null)
            {
                Mail::to($event->user->email)->queue(new LoggedInMail($event->user));
            }
        }
    }
}
