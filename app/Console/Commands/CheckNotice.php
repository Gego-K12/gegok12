<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\NoticeBoard;
use Exception;
use Log;

class CheckNotice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gego:checknotice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Notice';

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
        //
        try
        {
            $notices = NoticeBoard::where('expire_date','<=',date('Y-m-d'))->where('status',1)->get();
            foreach($notices as $notice)
            {
                $update['status'] = 0;

                NoticeBoard::where('id',$notice->id)->update($update);
            }
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        }
    }
}