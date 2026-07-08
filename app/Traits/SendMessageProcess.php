<?php

/**
 * Trait for processing SendMessageProcess
 */

namespace App\Traits;

use App\Events\Notification\SingleNotificationEvent;
use App\Events\SinglePushEvent;
use App\Helpers\SiteHelper;
use App\Models\SendMail;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Log;

/**
 * @class trait
 * Trait for SendMessageProcess Processes
 */
trait SendMessageProcess
{
    /**
     * Send or schedule a message to a parent with push notification support.
     *
     * @param  object  $data  Request payload containing subject, message, and optional execution time
     * @param  int  $school_id  School identifier
     * @param  string  $admin_email  Sender email address
     * @param  User  $parent  Parent user receiving the message
     * @param  User  $admin  Admin user performing the action
     * @param  User  $student  Student associated with the parent
     * @return void
     */
    public function selectSendMessage($data, $school_id, $admin_email, $parent, $admin, $student)
    {
        try {
            $academic_year = SiteHelper::getAcademicYear($school_id);

            $send = new SendMail;

            $send->school_id = $school_id;
            $send->academic_year_id = $academic_year->id;
            $send->user_id = $parent->id;
            $send->from = $admin_email;
            $send->to = $parent->mobile_no;
            $send->subject = $data->subject;
            $send->message = $data->message;
            $send->student_id = $student->id;

            if ($data->executed_at == '') {
                $send->executed_at = Carbon::now();
                $send->fired_at = Carbon::now();
                $send->is_executed = 1;
                $send->status = 'delivered';

                $array = [];

                $array['school_id'] = $school_id;
                $array['user_id'] = $parent->id;
                $array['message'] = 'New Message Received';
                $array['type'] = 'private message';

                event(new SinglePushEvent($array));

                $data = [];

                $data['user'] = $student;
                $data['details'] = trans('notification.message_sent_success_msg');

                event(new SingleNotificationEvent($data));
            } else {
                $send->executed_at = date('Y-m-d H:i:s', strtotime($data->executed_at));
                $send->status = 'queue';
            }

            $send->save();

            $message = ('Message Sent Successfully');

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $send,
                $admin,
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_SEND_MESSAGE,
                $message
            );
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
