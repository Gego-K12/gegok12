<?php

namespace App\Console\Commands;

use App\Models\Task;
use Exception;
use Illuminate\Console\Command;
use Log;

class CheckTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gego:checktask';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Task';

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
        $now = date('Y-m-d H:i:s');
        try {
            $tasks = Task::get();

            foreach ($tasks as $task) {
                $task = Task::where('id', $task->id)->first();
                $task_date = date('Y-m-d H:i:s', strtotime($task->task_date));
                if ($task_date == $now) {
                    $task->task_flag = 1;
                } elseif ($task_date > $now) {
                    $task->task_flag = 2;
                } else {
                    $task->task_flag = 0;
                }
                $task->save();
            }
        } catch (Exception $e) {
            Log::info($e->getMesage());
        }
    }
}
