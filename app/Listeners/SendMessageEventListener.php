<?php

namespace App\Listeners;

use App\Events\SendMessageEvent;
use App\Models\Users\ParentUser;
use App\Models\Users\StudentUser;
use App\Traits\Common;
use App\Traits\LogActivity;
use App\Traits\SendMessageProcess;

class SendMessageEventListener
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
    public function handle(SendMessageEvent $event)
    {
        //
        foreach ($event->request->selected as $user_id) {
            foreach ($user_id as $parent_id) {
                foreach ($event->request->selectedUsers as $student_id) {
                    $student = StudentUser::where([['usergroup_id', 6], ['id', $student_id]])->first();
                }
                $user = ParentUser::where([['usergroup_id', 7], ['id', $parent_id]])->first();
                $send = $this->selectSendMessage($event->request, $event->school_id, $event->admin_email, $user, $event->admin, $student);
            }
        }
    }
}
