<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Auth;
use Illuminate\Console\Command;
use App\Traits\AutoPostProcess;
use App\Traits\EventProcess;

use App\Models\Userprofile;
use App\Helpers\SiteHelper;
use App\Traits\Common;
use App\Models\Events;
use App\Models\User;
use Carbon\Carbon;
use Exception;

class CheckAnniversary extends Command
{
    use EventProcess;
    use AutoPostProcess;
    use Common;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gego:checkanniversary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Anniversary';

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

         

            $teacheranniversary=User::whereHas('userprofile', function($query) 
                { 

                    $query->WhereRaw("DATE_FORMAT(joining_date, '%m-%d') = DATE_FORMAT(DATE_ADD(now(), INTERVAL 1 DAY),'%m-%d')")->where('status','active');
                })->where(function($query) {
                    $query->where('usergroup_id',5)
                                ->orWhere('usergroup_id',8)
                                ->orWhere('usergroup_id',11);
                })->get();


            if(count($teacheranniversary)>0)
            {
                foreach($teacheranniversary as $teacher)
                {
                    $joining_date = date('Y-m-d',strtotime($teacher->userprofile->joining_date));
                    $month = date('m-d',strtotime($teacher->userprofile->joining_date));
                    $current_year=date('Y');
                    $anniversary_date = $current_year.'-'.$month;

                      $date=date('Y-m-d H:i:s');

                $anniversary= Carbon::parse($joining_date)->diffInYears(Carbon::parse($anniversary_date));

                 if($anniversary=='1')
                    {
                        $description = '1st Work Anniversary';
                    }
                    elseif($anniversary=='2')
                    {
                        $description = '2nd Work Anniversary';
                    }
                    elseif($anniversary=='3')
                    {
                        $description = '3rd Work Anniversary';
                    }
                    else
                    {
                        $description = $anniversary.'th Work Anniversary';
                    }


                    $academic_year_id=$teacher->teacherprofile[0]['academic_year_id']; 

                    // $image = $this->getFilePath('uploads/images/work_anniversary.jpg');
                    $image = 'uploads/images/work_anniversary.jpg';


                    $event = $this->CreateWorkAnniversary($teacher,$image, $description,$anniversary_date,$date);
                    
                }                        
            
            }

          
        }
        catch(Exception $e)
        {
        }
    }
}
