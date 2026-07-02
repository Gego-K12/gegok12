<?php

namespace App\Console\Commands;

use App\Events\BirthdayPushEvent;
use App\Events\SinglePushEvent;
use Illuminate\Console\Command;
use App\Models\Reminder;
use App\Models\School;
use App\Models\Task;
use Exception;
use Log;

class CheckNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gego:checknotification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Notification';

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
            $now       = date('Y-m-d H:i:s');
            $queuelist = Reminder::where([['queue_status','=','queue'],['via','=','notification']])->where('executed_at','=',$now)->get();
            
            foreach($queuelist as $queue)
            {  
                $school = School::IsActive($queue->school_id)->exists();
                if($school == TRUE)
                {
                    $update['queue_status']='deliver';
                    Reminder::where('id',$queue->id)->update($update);

                    if($queue->user->platform_token != null)
                    {
                        if($queue->entity_name == "App\\Models\\Task")
                        {
                            $task_update['snooze'] = 0;
                            Task::where('id',$queue->entity_id)->update($task_update);

                            $array=[];

                            $array['school_id']  =   $queue->school_id;
                            $array['user_id']    =   $queue->user->id;
                            $array['message']    =   $queue->data['message'];
                            $array['type']       =   'task';

                            event(new SinglePushEvent($array));
                        }
                        else
                        {
                            event(new BirthdayPushEvent($queue));
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