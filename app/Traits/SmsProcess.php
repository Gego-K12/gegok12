<?php
/**
 * Trait for processing common
 */
namespace App\Traits;

use App\Models\Smstemplate;
use App\Traits\MSG91;
use Exception;
use Log;

/**
 *
 * @class trait
 * Trait for Common Processes
 */
trait SmsProcess
{

    use MSG91;

    /**
     * Send SMS event notification using template placeholders.
     *
     * @param string $to Recipient mobile number(s)
     * @param string $start_date Event start date
     * @param string $location Event location text
     * @return void
     */
    public function sendSmsNotification($to,$start_date,$location)
    {
        try
        {
            $template = Smstemplate::where([['name','Event'],['status','1']])->first();
            $content  = $template->content;
      
            $content = str_replace(":date",$start_date,$content);
            $content = str_replace(":location",$location,$content);
            
            $sms = env('SMS_GATEWAY');
            if($sms)
            {
                $this->sendSMS($content, $to);
            }
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        }
    }

    /**
     * Send password reset SMS with reset URL.
     *
     * @param string $to Recipient mobile number(s)
     * @param string $url Reset URL to include in the message
     * @return void
     */
    public function sendUserResetPassword($to,$url)
    {
        try
        {
            $template = Smstemplate::where([['name','reset_password'],['status','1']])->first();
            $content  = $template->content;
        
            $content = str_replace(":url",$url,$content);
            $sms = env('SMS_GATEWAY');
          
            if($sms)
            {
                $this->sendSMS($content, $to);
            }
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        }
    }

    /**
     * Send absence notification SMS to parents.
     *
     * @param string $to Recipient mobile number(s)
     * @param string $message Absence message text
     * @param string $school_name School name to include in the template
     * @return void
     */
    public function sendAbsentRecord($to,$message,$school_name)
    { 
        try
        {
            $template = Smstemplate::where([['name','absent_message'],['status','1']])->first();
            $content  = $template->content;
        
            $content = str_replace(":message",$message,$content);
            $content = str_replace(":school_name",$school_name,$content);
            $sms = env('SMS_GATEWAY');
          
            if($sms)
            {
                $this->sendSMS($content, $to);
            }
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        }
    }

    /**
     * Send birthday wishes SMS.
     *
     * @param string $to Recipient mobile number(s)
     * @param string $message Message content
     * @param string $school_name School name to include in the template
     * @return void
     */
    public function sendBirthday($to,$message,$school_name)
    { 
        try
        {
            $template = Smstemplate::where([['name','birthday'],['status','1']])->first();
            $content  = $template->content;
        
            $content = str_replace(":message",$message,$content);
            $content = str_replace(":school_name",$school_name,$content);
            $sms = env('SMS_GATEWAY');
          
            if($sms)
            {
                $this->sendSMS($content, $to);
            }
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        }
    }

    /**
     * Send admission approval SMS including application number.
     *
     * @param array $data Data containing application_no, school_name, and mobile_no
     * @return void
     */
    public function sendAdmissionApproval($data)
    { 
        try
        {
            $template = Smstemplate::where([['name','admission_confirmation'],['status','1']])->first();
            $content  = $template->content;
        
            $content = str_replace(":application_no",$data['application_no'],$content);
            $content = str_replace(":school_name",$data['school_name'],$content);
            $sms = env('SMS_GATEWAY');
          
            if($sms)
            {
                $this->sendSMS($content, $data['mobile_no']);
            }
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        }
    }
}
