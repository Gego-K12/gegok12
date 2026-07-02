<?php

namespace App\Console\Commands;

use App\Events\Notification\ClassNotificationEvent;
use App\Events\StandardPushEvent;
use Illuminate\Console\Command;
use App\Helpers\SiteHelper;
use App\Models\Assignment;
use Exception;
use Log;

class CheckAssignment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gego:checkassignment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Assignment';

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
            $pendingAssignments = Assignment::where('status','pending')->where('assigned_date','<=',date('Y-m-d'))->whereHas('assignmentApproval' , function($query) {
                    $query->where('status','approved');
                })->get();
              
            foreach($pendingAssignments as $assignment)
            {
                $update['status']='ongoing';
                Assignment::where('id',$assignment->id)->update($update);

                $data=[];

                $data['school_id']      =   $assignment->school_id;
                $data['standard_id']    =   $assignment->standardLink_id;
                $data['message']        =   'New Assignment Added';
                $data['type']           =   'assignment';

                event(new StandardPushEvent($data));

                $array = [];

                $array['school_id']         = $assignment->school_id;
                $array['standardLink_id']   = $assignment->standardLink_id;
                $array['details']           = trans('notification.teacher_assignment_add_msg');  

                event(new ClassNotificationEvent($array));         

                $academic_year = SiteHelper::getAcademicYear($assignment->school_id);
                $studentAcademics = SiteHelper::getClassStudents($assignment->school_id,$academic_year->id,$assignment->standardLink_id);
                foreach($studentAcademics as $studentAcademic)
                {
                    foreach ($studentAcademic->user->parents as $parent) 
                    {
                        $this->sendToAssignmentReminder($assignment->school_id,date('Y-m-d',strtotime($assignment->submission_date)),$assignment->subject->name,$assignment->title,$parent->userParent->id,$parent->userParent->email,$parent->userParent->mobile_no);
                    }  
                }
            }

            $completedAssignments = Assignment::where('status','ongoing')->where('submission_date','<=',date('Y-m-d'))->get();
              
            foreach($completedAssignments as $assignment)
            {
                $update['status']='completed';
                Assignment::where('id',$assignment->id)->update($update);
            }  
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        }
    }
}