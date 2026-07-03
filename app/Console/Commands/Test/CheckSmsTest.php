<?php

namespace App\Console\Commands\Test;

use App\Traits\MSG91;
use Exception;
use Illuminate\Console\Command;

class CheckSmsTest extends Command
{
    use MSG91;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gego:checksmstest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check sms Test ';

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
            $mobileno = $this->ask('Enter mobile number)');
            $mobileno = $mobileno;

            if (env('SMS_STATUS') == 'on') {

                $msg = $this->sendSMS('hii', $mobileno);
                $this->info(is_scalar($msg) ? $msg : json_encode($msg));
            }

        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
