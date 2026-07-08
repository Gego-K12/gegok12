<?php

namespace App\Console\Commands;

use App\Events\SinglePushEvent;
use App\Models\SendMail;
use Illuminate\Console\Command;
use Log;

class CheckSendMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gego:checksendmail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Send Mail';

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
        try {
            $now = now();
            $sendmails = SendMail::where('is_executed', 0)->where('executed_at', '<=', $now)->get();

            foreach ($sendmails as $sendmail) {
                $is_executed['is_executed'] = 1;

                SendMail::where('id', $sendmail->id)->update($is_executed);

                $data = [];

                $data['school_id'] = $sendmail->school_id;
                $data['message'] = 'New Message Received';
                $data['type'] = 'private message';

                event(new SinglePushEvent($data));

                $fired_at['status'] = 'delivered';
                $fired_at['fired_at'] = date('Y-m-d H:i:s');

                SendMail::where('id', $sendmail->id)->update($fired_at);
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
