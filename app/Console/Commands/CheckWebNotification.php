<?php

namespace App\Console\Commands;

use App\Events\Notification\SingleNotificationEvent;
use App\Models\Reminder;
use App\Models\School;
use App\Models\Task;
use Exception;
use Illuminate\Console\Command;
use Log;

class CheckWebNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gego:checkwebnotification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Web Notification';

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
        try {
            $now = date('Y-m-d H:i:s');
            $queuelist = Reminder::where([['queue_status', '=', 'queue'], ['via', '=', 'web_notification']])->where('executed_at', '<=', $now)->get();

            foreach ($queuelist as $queue) {
                $school = School::IsActive($queue->school_id)->exists();
                if ($school == true) {
                    $update['queue_status'] = 'deliver';
                    Reminder::where('id', $queue->id)->update($update);

                    if ($queue->entity_name == 'App\\Models\\Task') {
                        $task_update['snooze'] = 0;
                        Task::where('id', $queue->entity_id)->update($task_update);

                        $arr['data'] = $queue->data['message'];
                        $arr['type'] = $queue->data['type'];

                        $array = [];

                        $array['user'] = $queue->userSms;
                        $array['details'] = $arr;

                        event(new SingleNotificationEvent($array));
                    }
                } else {
                    return false;
                }
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
