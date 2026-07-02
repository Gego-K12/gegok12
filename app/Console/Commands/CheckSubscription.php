<?php

namespace App\Console\Commands;

use App\Events\AfterExpiredEvent;
use Illuminate\Console\Command;
use App\Models\Subscription;
use App\Models\Userprofile;
use Exception;
use Log;

class CheckSubscription extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gego:checksubscription';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Subscription';

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
        \DB::beginTransaction();
        try
        {
            $now = date('Y-m-d H:i:s');
            $queuelist = Subscription::where('status','=','approve')->where('end_date','<=',$now)->get();
              
            foreach($queuelist as $subscription)
            {
                if(env('MAIL_STATUS') == 'on')
                {
                    $update['status']='expired';
                    Subscription::where('id',$subscription->id)->update($update);

                    $update['status']='inactive';
                    Userprofile::where('user_id',$subscription->user_id)->update($update);
                     
                    \DB::commit();       
                    event(new AfterExpiredEvent($subscription));
                }
            }
                   
        }
        catch(Exception $e)
        {
            \DB::rollBack();
            Log::info($e->getMessage());
        }
    }
}