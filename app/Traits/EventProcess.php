<?php

namespace App\Traits;

use App\Events\AdminBirthdayReminderEvent;
use App\Events\PrayerReminderEvent;
use App\Events\UserReminderEvent;
use App\Events\ReminderEvent;
use App\Models\Events;
use App\Models\User;
use App\Models\RoomLink;
use Exception;
use Log;

trait EventProcess
{
    /**
     * Dispatch reminder events for an event across notification, SMS, and mail channels.
     *
     * @param \App\Models\Events $events Event instance containing schedule info
     * @param string $executed_at Date string used to schedule the reminder (Y-m-d)
     * @param string $check Indicator for recurrence handling (e.g., 'next')
     * @return void
     */
    public function sendToReminderEvent($events,$executed_at,$check)
    {
        try
        { 
            $options = ['notification','sms','mail'];

            foreach($options as $option)
            {
                $school_id    =  $events->school_id;
                $from         =  env('MAIL_FROM_ADDRESS');
                $subject      =  "Event Remainder Mail";
                $message      =  "Event Notification Mail";
                $entity_id    =  $events->id;
                $entity_name  =  "App\\Models\\Events";
                $via          =  $option;
                $data         =  array('date'=>date('Y-m-d',strtotime($events->start_date)),'location'=>$events->location);

                if($check=='next')
                {
                    if($events->repeats == '1')
                    {
                        $freq = $events->freq;
                        if($events->freq_term == 'week')
                        {
                            $executed_at  =  date('Y-m-d', strtotime('+'.$freq. 'week', strtotime($executed_at)));          
                        }
                        elseif($events->freq_term == 'month')
                        {
                            $executed_at  =  date('Y-m-d', strtotime('+'.$freq. 'month', strtotime($executed_at)));
                        }
                        elseif($events->freq_term == 'year')
                        {
                            $executed_at  =  date('Y-m-d', strtotime('+'.$freq. 'year', strtotime($executed_at)));
                        }
                        elseif($events->freq_term == 'day')
                        {
                            $executed_at  =  date('Y-m-d', strtotime('+'.$freq. 'days', strtotime($executed_at)));  
                        }
                    }
                    else
                    {
                        $executed_at = $executed_at;
                    }
                }
                if($executed_at < $events->end_date )
                {
                    event(new ReminderEvent($school_id,$from,$subject,$message,$entity_id,$entity_name,$via,$data,$executed_at));      
                } 
            }
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        } 
    }
  
    /**
     * Send birthday or work anniversary reminders to a user via multiple channels.
     *
     * @param int $school_id School identifier
     * @param int $entity_id User identifier
     * @param string $date_of_birth Birth date for the user
     * @param array $data Payload passed to the reminder event
     * @param string|null $mobile_no Mobile number for SMS
     * @param string|null $mail Email address for mail delivery
     * @param string $type Reminder type ('birthday' or 'work_anniversary')
     * @return void
     */
    public function sendToBirthdayReminder($school_id,$entity_id,$date_of_birth,$data,$mobile_no,$mail,$type)
    {
        try
        {
            $options = ['notification','sms','mail'];
            foreach($options as $option)
            {
                $from         =  env('MAIL_FROM_ADDRESS');
          
                if($type == 'birthday')
                { 
                    $subject      =  "Birthday Wishes";
                    $message      =  "Birthday Notification Mail";
                }
                elseif($type == 'work_anniversary')
                {
                    $subject      =  "Work Anniversary Wishes";
                    $message      =  "Work Anniversary Notification Mail";
                }
                $executed_at  =  date('Y-m-d', strtotime($date_of_birth));
                $entity_name  =  "App\\Models\\User";
                $via          =  $option;
          
                /*$array = array('school_id' => $school_id , 'from' => $from , 'mobile_no' => $mobile_no , 'mail' => $mail , 'subject' =>$subject , 'message' =>$message , 'entity_id' =>$entity_id , 'entity_name' =>$entity_name , 'via' =>$via , 'data' =>$data , 'executed_at' => $executed_at );*/
                event(new UserReminderEvent($school_id,$from,$mobile_no,$mail,$subject,$message,$entity_id,$entity_name,$via,$data,$executed_at)); 
            }
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        }  
    }

    /**
     * Notify admins about a user's birthday via all channels.
     *
     * @param int $school_id School identifier
     * @param string $user_name Name of the user
     * @param string $user_email Email of the user
     * @param int $entity_id User identifier
     * @param string $date_of_birth Birth date of the user
     * @param string|null $mobile_no Mobile number for SMS
     * @param string|null $mail Email address for mail delivery
     * @param string $birth_date Date used to schedule the notification
     * @return void
     */
    public function adminBirthdayReminder($school_id,$user_name,$user_email,$entity_id,$date_of_birth,$mobile_no,$mail,$birth_date)
    {
        try
        {
            $options = ['notification','sms','mail'];
            foreach($options as $option)
            {
                $from         =  env('MAIL_FROM_ADDRESS');
                $subject      =  "Birthday Remainder Mail";
                $message      =  "Birthday Notification Mail for <br>
                Name : ".$user_name."<br>
                Email : ".$user_email."<br>
                Birth date : ".$date_of_birth;
                $executed_at  =  date('Y-m-d', strtotime($birth_date));
                $entity_name  =  "App\\Models\\User";
                $via          =  $option;
                $data         =  array('subject'=> $subject, 'message'=>$message, 'type'=>'birthday');
          
                event(new UserReminderEvent($school_id,$from,$mobile_no,$mail,$subject,$message,$entity_id,$entity_name,$via,$data,$executed_at));
            }  
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        } 
    }

    /**
     * Send absentee notifications to parents across available channels.
     *
     * @param int $school_id School identifier
     * @param string $date Date of absence
     * @param int $entity_id Student identifier
     * @param string|null $mobile_no Mobile number for SMS
     * @param string|null $email Email address for mail delivery
     * @param string $student_name Name of the absent student
     * @return void
     */
    public function sendToAttendanceReminder($school_id,$date,$entity_id,$mobile_no,$email,$student_name)
    {
        try
        {
            $options = ['notification','sms','mail'];
            foreach($options as $option)
            {
                $from         =  env('MAIL_FROM_ADDRESS');
                $entity_name  =  "App\\Models\\User";
                $via          =   $option;
                $subject      =  "Absent Message";
                $message      =  'Dear Parent, Kindly make a note that your child '.$student_name.' is absent for school today.';
                $executed_at  =   $date;
                //$mail         =   NULL;
                $data         =   'absent_message';
                event(new UserReminderEvent($school_id,$from,$mobile_no,$email,$subject,$message,$entity_id,$entity_name,$via,$data,$executed_at));
            }
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        }  
    }

    /*  public function sendToAssignmentReminder($school_id,$date,$subject_name,$title,$entity_id,$mail,$mobile_no)
    {
        try
        {
            $from         =  env('MAIL_FROM_ADDRESS');
            $entity_name  =  "App\\Models\\User";
            $via          =  'notification';
            $subject      =  "Assignment Reminder";
            $message      =  'This reminder is about the assignment to be submitted on '.$date.' of subject : '.$subject_name.' , title : '.$title.'.';
            $executed_at  =   date('Y-m-d',strtotime('-2 days',strtotime($date)));
            $data         =   'assignment_reminder';
            event(new UserReminderEvent($school_id,$from,$mobile_no,$mail,$subject,$message,$entity_id,$entity_name,$via,$data,$executed_at));
        }
        catch(Exception $e)
        { 
            Log::info($e->getMessage());
        }  
    }*/

    /**
     * Dispatch task reminder to a user over notification, SMS, and email.
     *
     * @param int $school_id School identifier
     * @param string $date Date to execute reminder
     * @param string $title Task title
     * @param int $entity_id Task identifier
     * @param string|null $mail Email address for mail delivery
     * @param string|null $mobile_no Mobile number for SMS
     * @return void
     */
    public function sendToTaskReminder($school_id,$date,$title,$entity_id,$mail,$mobile_no)
    {
        try
        {
            $options = ['notification','sms','mail'];
            foreach($options as $option)
            {
                $from         = env('MAIL_FROM_ADDRESS');
                $entity_name  = "App\\Models\\Task";
                $via          = $option;
                $subject      = "Task Reminder";
                $message      = 'This reminder is about the task "'.$title.'" assigned to you.';
                $data         = array('subject' => $subject, 'message' => $message, 'type' => 'task');
                event(new UserReminderEvent($school_id,$from,$mobile_no,$mail,$subject,$message,$entity_id,$entity_name,$via,$data,$date));
            } 
        }
        catch(Exception $e)
        { 
            Log::info($e->getMessage());
        }  
    }


    /**
     * Send assignment reminder to a user two days before due date.
     *
     * @param int $school_id School identifier
     * @param string $date Assignment due date
     * @param string $subject_name Name of the subject
     * @param string $title Assignment title
     * @param int $entity_id User identifier
     * @param string|null $mail Email address for mail delivery
     * @param string|null $mobile_no Mobile number for SMS
     * @return void
     */
    public function sendToAssignmentReminder($school_id,$date,$subject_name,$title,$entity_id,$mail,$mobile_no)
    {
        try
        {
            $from         =  env('MAIL_FROM_ADDRESS');
            $entity_name  =  "App\\Models\\User";
            $via          =  'notification';
            $subject      =  "Assignment Reminder";
            $message      =  'This reminder is about the assignment to be submitted on '.$date.' of subject : '.$subject_name.' , title : '.$title.'.';
            $executed_at  =   date('Y-m-d',strtotime('-2 days',strtotime($date)));
            $data         =   'assignment_reminder';
            event(new UserReminderEvent($school_id,$from,$mobile_no,$mail,$subject,$message,$entity_id,$entity_name,$via,$data,$executed_at));
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        }  
    }

    /**
     * Send snooze reminder for a task through notifications.
     *
     * @param int $school_id School identifier
     * @param string $date Rescheduled reminder date
     * @param string $title Task title
     * @param int $entity_id Task identifier
     * @param string|null $mail Email address for mail delivery
     * @param string|null $mobile_no Mobile number for SMS
     * @return void
     */
    public function sendToSnoozeTask($school_id,$date,$title,$entity_id,$mail,$mobile_no)
    {
        try
        {
            $from         = env('MAIL_FROM_ADDRESS');
            $entity_name  = "App\\Models\\Task";
            $via          = 'notification';
            $subject      = "Task Reminder";
            $message      = trans('notification.task_snooze_msg');
            $data         = array('subject' => $subject, 'message' => $message, 'type' => 'task');
            event(new UserReminderEvent($school_id,$from,$mobile_no,$mail,$subject,$message,$entity_id,$entity_name,$via,$data,$date));
        }
        catch(Exception $e)
        { 
            Log::info($e->getMessage());
        }  
    }

    /**
     * Send web notification for snoozed tasks.
     *
     * @param int $school_id School identifier
     * @param string $date Rescheduled reminder date
     * @param string $title Task title
     * @param int $entity_id Task identifier
     * @param string|null $mail Email address for mail delivery
     * @param string|null $mobile_no Mobile number for SMS
     * @return void
     */
    public function sendToSnoozeTaskWeb($school_id,$date,$title,$entity_id,$mail,$mobile_no)
    {
        try
        {
            $from         = env('MAIL_FROM_ADDRESS');
            $entity_name  = "App\\Models\\Task";
            $via          = 'web_notification';
            $subject      = "Task Reminder";
            $message      = trans('notification.task_snooze_msg');
            $data         = array('subject' => $subject, 'message' => $message, 'type' => 'task');
            event(new UserReminderEvent($school_id,$from,$mobile_no,$mail,$subject,$message,$entity_id,$entity_name,$via,$data,$date)); 
        }
        catch(Exception $e)
        { 
            Log::info($e->getMessage());
        }  
    }

    /**
     * Notify users when added to a chat room via SMS and mail.
     *
     * @param int $school_id School identifier
     * @param int $entity_id Room link identifier
     * @param string|null $mobile_no Mobile number for SMS
     * @param string|null $email Email address for mail delivery
     * @param string $date Notification execution date
     * @return void
     */
    public function userNotifyGroup($school_id,$entity_id,$mobile_no,$email,$date)
    {
        try
        {
            $from         =  env('MAIL_FROM_ADDRESS');
            $subject      =  "Chat Room Notification Mail";
            $message      =  "You have been added to this chat room.Check with your app to know more<br>";
            $entity_name  =  "App\\Models\\RoomLink";
            $via          =  'sms';
            $data         =  array('message'=>$message);
            $executed_at  =  date('Y-m-d', strtotime($date));
       
            event(new UserReminderEvent($school_id,$from,$mobile_no,$email,$subject,$message,$entity_id,$entity_name,$via,$data,$executed_at));
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        } 
    }
} 
