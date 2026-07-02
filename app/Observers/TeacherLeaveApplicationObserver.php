<?php

namespace App\Observers;

use Illuminate\Support\Facades\Cache;
use App\Models\TeacherLeaveApplication;
use App\Models\User;
use Exception;
use Log;

class TeacherLeaveApplicationObserver
{
    /**
     * Handle the teacherprofile "created" event.
     *
     * @param  \App\Models\TeacherLeaveApplication  $teacherleaveapplication
     * @return void
     */
    public function created(TeacherLeaveApplication $teacherleaveapplication)
    {
        try
        {
            Cache::forget('pending_leave_count_'.$teacherleaveapplication->school_id.'_'.$teacherleaveapplication->academic_year_id.'_'.$teacherleaveapplication->user_id);
        }
        catch(Exception $e)
        {
        	Log::info($e->getMessage());
        }
    }

    /**
     * Handle the teacherprofile "updated" event.
     *
     * @param  \App\Models\TeacherLeaveApplication  $teacherleaveapplication
     * @return void
     */
    public function updated(TeacherLeaveApplication $teacherleaveapplication)
    {
        try
        {
            Cache::forget('pending_leave_count_'.$teacherleaveapplication->school_id.'_'.$teacherleaveapplication->academic_year_id.'_'.$teacherleaveapplication->user_id);
        }
        catch(Exception $e)
        {
        	Log::info($e->getMessage());
        }
    }

    /**
     * Handle the teacherprofile "deleted" event.
     *
     * @param  \App\Models\TeacherLeaveApplication  $teacherleaveapplication
     * @return void
     */
    public function deleted(TeacherLeaveApplication $teacherleaveapplication)
    {
        //
        try
        {
            Cache::forget('pending_leave_count_'.$teacherleaveapplication->school_id.'_'.$teacherleaveapplication->academic_year_id.'_'.$teacherleaveapplication->user_id);
        }
        catch(Exception $e)
        {
        	Log::info($e->getMessage());
        }
    }

    /**
     * Handle the teacherprofile "restored" event.
     *
     * @param  \App\Models\TeacherLeaveApplication  $teacherleaveapplication
     * @return void
     */
    public function restored(TeacherLeaveApplication $teacherleaveapplication)
    {
        //
    }

    /**
     * Handle the teacherprofile "force deleted" event.
     *
     * @param  \App\Models\TeacherLeaveApplication  $teacherleaveapplication
     * @return void
     */
    public function forceDeleted(TeacherLeaveApplication $teacherleaveapplication)
    {
        //
    }
}