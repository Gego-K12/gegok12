<?php

namespace App\Listeners;

// use App\Events\PushEvent;
use App\Events\TransportNotificationPushEvent;
use App\Models\RouteStudent;
use App\Models\StudentParentLink;
// use App\Traits\SendPushNotification;
use App\Models\User;
// use App\Models\CoordinatorIncharge;
use App\Notifications\SendDeviceNotification;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Log;

class TransportNotificationPushListener implements ShouldQueue
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
     * @return void
     */
    public function handle(TransportNotificationPushEvent $event)
    {
        try {

            // $users = CoordinatorIncharge::where([['route_id', $event->data['route_id']],['user_id', $event->data['driver_id']]])->get();
            // $users = CoordinatorIncharge::where([['route_id', $event->data['route_id']], ['created_at', '>=', Carbon::today()]])->pluck('user_id')->toArray();

            // $users = RouteStudent::where('route_id', $event->data['route_id'])->pluck('user_id')->toArray();
            // $students = User::whereIn('id', $users)->with('parents')->get();

            // $students = StudentParentLink::whereIn('student_id', $users)->get();

            // $parentId = $students->parents[0]['parent_id'];

            $users = RouteStudent::where([['school_id', $event->data['school_id']], ['route_id', $event->data['route_id']]])->with('students')->get();
            foreach ($users as $user) {
                $parents = $user->students->parents;

                foreach ($parents as $parent) {
                    if ($event->data['trip_name'] != 'others') {
                        if (isset($parent->userParent->platform_token)) {

                            // $this->sendNotification($event->data,$parent->userParent->platform_token);
                            $parent->userParent->notify(new SendDeviceNotification($event->data, $parent->userParent->platform_token));
                        }
                    }

                    /*if($event->data['type'] == 'LiveLocation')
                    {
                        if(isset($parent->userParent->platform_token))
                        {
                            $this->sendLiveLocationNotification($event->data,$parent->userParent->platform_token);
                        }
                    }*/
                }
            }

            // Mail::to($event->queue->to)->queue(new ReminderMail($event->queue));
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }

    }
}
