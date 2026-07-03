<?php

namespace App\Listeners;

// use App\Events\PushEvent;
use App\Events\StudentAttendancePushEvent;
use App\Models\RouteStudent;
// use App\Traits\SendPushNotification;
// use App\Models\CoordinatorIncharge;
use App\Models\User;
use App\Notifications\SendDeviceNotification;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;

class StudentAttendancePushListener implements ShouldQueue
{
    // use SendPushNotification;

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
     * @param  TransportNotificationPushEvent  $event
     * @return void
     */
    public function handle(StudentAttendancePushEvent $event)
    {
        try {
            $users = RouteStudent::where([['school_id', $event->data['school_id']], ['route_id', $event->data['route_id']], ['user_id', $event->data['user_id']]])->with('students')->get();
            foreach ($users as $user) {
                $parents = $user->students->parents;

                foreach ($parents as $parent) {
                    if (isset($parent->userParent->platform_token)) {
                        // $this->sendNotification($event->data,$parent->userParent->platform_token);
                        $user = User::find($parent->userParent->id);
                        $user->notify(new SendDeviceNotification($event->data, $user->platform_token));
                    }
                }
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }

    }
}
