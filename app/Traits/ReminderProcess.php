<?php
/**
 * Trait for processing ReminderProcess
 */
namespace App\Traits;

use App\Models\Reminder;
use Exception;
use Log;

/**
 *
 * @class trait
 * Trait for ReminderProcess Processes
 */
trait ReminderProcess
{
  
    /**
     * Create a reminder entry for later dispatch.
     *
     * @param int $school_id School identifier
     * @param string $from Sender address
     * @param string $to Recipient address
     * @param string $subject Reminder subject
     * @param string $message Reminder body
     * @param int $entity_id Related entity identifier
     * @param string $entity_name Related entity model name
     * @param string $via Delivery channel (notification, sms, mail)
     * @param mixed $data Additional payload to store with reminder
     * @param string $executed_at Scheduled execution datetime
     * @return void
     */
    public function createReminder($school_id,$from,$to,$subject,$message,$entity_id,$entity_name,$via,$data,$executed_at)
    {
        try
        {
            $reminder              = new Reminder;

            $reminder->school_id   = $school_id;
            $reminder->from        = $from;
            $reminder->to          = $to;
            $reminder->subject     = $subject;
            $reminder->message     = $message;
            $reminder->entity_id   = $entity_id;
            $reminder->entity_name = $entity_name;
            $reminder->via         = $via;
            //$reminder->queue_status= $queue_status;
            $reminder->executed_at = $executed_at;
            $reminder->data        = $data;
            //$reminder->template_id = $template_id;

            $reminder->save();
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        }    
	}
}
