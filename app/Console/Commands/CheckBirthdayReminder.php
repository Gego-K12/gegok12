<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Traits\EventProcess;
use App\Models\Userprofile;
use App\Models\User;
use Exception;

class CheckBirthdayReminder extends Command
{

    use EventProcess;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gego:checkbirthdayreminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Birthday Reminder';

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
            $teacherbirthdays = Userprofile::with('user')
                            ->WhereRaw("DATE_FORMAT(date_of_birth, '%m-%d') = DATE_FORMAT(now() + INTERVAL 2 DAY,'%m-%d')")
                            ->ByRole(5)
                            ->get();

            foreach($teacherbirthdays as $birthday)
            {   
                $admin = User::where('school_id',$birthday->school_id)->ByRole(3)->first();  
                $school_id = $birthday->school_id;
                $entity_id = $birthday->user->id;
                $user_name = $birthday->user->FullName;
                $user_email = $birthday->user->email;
                $date_of_birth = date('d-m-Y',strtotime($birthday->date_of_birth));
                $month = date('m-d',strtotime('-2 days',strtotime($birthday->date_of_birth)));
                $current_year=date('Y');
                $birth_date = $current_year.'-'.$month;
                $mail = $admin->email;
                $mobile_no = $admin->mobile_no; 

                $this->adminBirthdayReminder($school_id,$user_name,$user_email,$entity_id,$date_of_birth,$mobile_no,$mail,$birth_date);
            }

            $studentbirthdays = Userprofile::with('user')
                            ->WhereRaw("DATE_FORMAT(date_of_birth, '%m-%d') = DATE_FORMAT(now() + INTERVAL 2 DAY,'%m-%d')")
                            ->ByRole(6)
                            ->get();

            foreach($studentbirthdays as $birthday)
            {   
                $admin = User::where('school_id',$birthday->school_id)->ByRole(3)->first();  
                $school_id = $birthday->school_id;
                $entity_id = $birthday->user->id;
                $user_name = $birthday->user->FullName;
                $user_email = $birthday->user->email;
                $date_of_birth = date('d-m-Y',strtotime($birthday->date_of_birth));
                $month = date('m-d',strtotime('-2 days',strtotime($birthday->date_of_birth)));
                $current_year=date('Y');
                $birth_date = $current_year.'-'.$month;
                $mail = $admin->email;
                $mobile_no = $admin->mobile_no; 

                $this->adminBirthdayReminder($school_id,$user_name,$user_email,$entity_id,$date_of_birth,$mobile_no,$mail,$birth_date);
            }
        }
        catch(Exception $e)
        {
        }
    }
}
