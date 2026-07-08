<?php

namespace App\Console\Commands\Test;

use App\Mail\TestMail;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CheckTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gego:checktest {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Test ';

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
            $to = $this->argument('email');
            Mail::to($to)->queue(new TestMail);
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
