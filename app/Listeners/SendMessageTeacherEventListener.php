<?php

namespace App\Listeners;

use App\Events\SendMessageTeacherEvent;
use App\Models\User;
use App\Traits\Common;
use App\Traits\LogActivity;
use App\Traits\SendMessageProcess;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMessageTeacherEventListener implements ShouldQueue
{
    use Common;
    use LogActivity;
    use SendMessageProcess;

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
    public function handle(SendMessageTeacherEvent $event)
    {
        //
        foreach ($event->data->selected as $user_id) {
            $user = User::where('id', $user_id)->first();
            $send = $this->selectSendMessage($event->data, $event->school_id, $event->admin_email, $user, $event->admin, $user);
        }
    }
}
