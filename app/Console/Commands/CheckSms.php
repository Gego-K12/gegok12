<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Traits\SmsProcess;
use App\Models\Reminder;
use App\Models\Events;
use App\Models\School;
use Exception;
use Log;

class CheckSms extends Command
{

    use SmsProcess;
   

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gego:checksms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Sms';

    /**
     * Create a new command instance.
     *
     * @return void
     */
  
    
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try
        {
            $now       = now();
            $queuelist = Reminder::where([['queue_status','=','queue'],['via','=','sms']])->where('executed_at','<=',$now)->get();

            foreach($queuelist as $queue)
            {  
                $school = School::IsActive($queue->school_id)->first();
                if($school)
                {
                    if(env('SMS_STATUS') == 'on')
                    {
                        $update['queue_status']='deliver';
                        Reminder::where('id',$queue->id)->update($update);

                        if($queue->entity_name=="App\\Models\\Events")
                        {  
                            $events=Events::where('id',$queue->entity_id)->first();
                            $this->sendSmsNotification($queue->to,$queue->data['date'],$queue->data['location']);
                        }
                        else if($queue->data == 'absent_message')
                        {
                            $this->sendAbsentRecord($queue->to,$queue->message,$school->name);
                        }
                        else if($queue->data['type'] == 'birthday')
                        {
                            $this->sendBirthday($queue->to,$queue->data['message'],$school->name);
                        }
                    }
                }
                else
                {
                    return FALSE;
                }
            }      
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        }
    }     
}