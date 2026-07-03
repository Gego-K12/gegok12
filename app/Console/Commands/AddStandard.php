<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Traits\AcademicProcess;
use Illuminate\Console\Command;

class AddStandard extends Command
{
    use AcademicProcess;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gego:addstandard {--default}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add Standard';

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
        $mobile = $this->ask('Enter Mobile Number Without Country Code');

        $admin = User::where('mobile_no', $mobile)->first();

        if ($this->option('default')) {
            $request->standards = 'higher_secondary';
            $this->addStandard($admin->school_id, $request);
        } else {
            $type = $this->choice('Select Highest Standard', ['nursery', 'primary', 'secondary', 'higher_secondary']);
            $request->standards = $type;
            $this->addStandard($admin->school_id, $request);
        }

        $this->info(trans('messages.standard_setup_success_msg'));
    }
}
