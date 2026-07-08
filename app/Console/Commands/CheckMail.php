<?php

namespace App\Console\Commands;

use App\Events\AbsentReminderMailEvent;
use App\Events\BirthdayReminderMailEvent;
use App\Events\ReminderMailEvent;
use App\Models\Events;
use App\Models\Reminder;
use App\Models\School;
use App\Traits\EventProcess;
use Exception;
use Illuminate\Console\Command;
use Log;

class CheckMail extends Command
{
    use EventProcess;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gego:checkmail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Mail';

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
            $now = now();
            $queuelist = Reminder::where([['queue_status', '=', 'queue'], ['via', '=', 'mail']])->where('executed_at', '<=', $now)->get();

            foreach ($queuelist as $reminder) {
                $school = School::IsActive($reminder->school_id)->first();
                if ($school) {
                    if (env('MAIL_STATUS') == 'on') {
                        $update['queue_status'] = 'deliver';
                        Reminder::where('id', $reminder->id)->update($update);

                        if ($reminder->entity_name == 'App\\Models\\Events') {
                            event(new ReminderMailEvent($reminder));
                            $events = Events::where('id', $reminder->entity_id)->first();
                            if ($events->repeats == 1) {
                                $this->sendToReminderEvent($events, $now, 'next');
                            }
                        } elseif (is_array($reminder->data) && ($reminder->data['type'] ?? '') === 'birthday') {
                            event(new BirthdayReminderMailEvent($reminder));
                        } elseif ($reminder->data == 'absent_message') {
                            event(new AbsentReminderMailEvent($reminder));
                        }
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
