<?php

namespace App\Listeners;

use App\Events\CalendarEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\CalendarMail;
use App\Models\Users\TeacherUser;
use App\Models\User;
use App\Models\Events;

class CalendarEventListener implements ShouldQueue
{
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
     * @param  CalendarEvent  $event
     * @return void
     */
    public function handle(CalendarEvent $events)
    {
        $users=TeacherUser::where('school_id',$events->events->school_id)->ByRole(5)->get();
        
        foreach ($users as $user) 
        {
            Mail::to($user->email)->queue(new CalendarMail($events->events));
        }
    }
}