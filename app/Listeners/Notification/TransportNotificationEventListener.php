<?php

namespace App\Listeners\Notification;

use App\Events\Notification\TransportNotificationEvent;
use App\Models\RouteStudent;
use App\Models\User;
use App\Notifications\NewMessageNotification;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;
use Notification;

class TransportNotificationEventListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct() {}

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(TransportNotificationEvent $event)
    {
        try {

            $users = RouteStudent::where('route_id', $event->data['route_id'])->pluck('user_id')->toArray();
            $students = User::whereIn('id', $users)->with('parents')->get();
            // $students = StudentParentLink::whereIn('student_id', $users)->get();

            // $parentId = $students->parents[0]['parent_id'];

            foreach ($students as $student) {
                $parentId1 = $student->parents[0]['parent_id'];
                $parentId2 = $student->parents[1]['parent_id'];
                $parents = User::whereIn('id', [$parentId1, $parentId2])->get();
                foreach ($parents as $parent) {
                    if ($event->data['trip_name'] != 'others') {
                        Notification::send($parent, new NewMessageNotification($event->data['details']));
                    }
                }
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
