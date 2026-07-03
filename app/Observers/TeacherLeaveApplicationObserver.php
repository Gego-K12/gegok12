<?php

namespace App\Observers;

use App\Models\TeacherLeaveApplication;
use Exception;
use Illuminate\Support\Facades\Cache;
use Log;

class TeacherLeaveApplicationObserver
{
    /**
     * Handle the teacherprofile "created" event.
     *
     * @return void
     */
    public function created(TeacherLeaveApplication $teacherleaveapplication)
    {
        try {
            Cache::forget('pending_leave_count_'.$teacherleaveapplication->school_id.'_'.$teacherleaveapplication->academic_year_id.'_'.$teacherleaveapplication->user_id);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Handle the teacherprofile "updated" event.
     *
     * @return void
     */
    public function updated(TeacherLeaveApplication $teacherleaveapplication)
    {
        try {
            Cache::forget('pending_leave_count_'.$teacherleaveapplication->school_id.'_'.$teacherleaveapplication->academic_year_id.'_'.$teacherleaveapplication->user_id);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Handle the teacherprofile "deleted" event.
     *
     * @return void
     */
    public function deleted(TeacherLeaveApplication $teacherleaveapplication)
    {
        //
        try {
            Cache::forget('pending_leave_count_'.$teacherleaveapplication->school_id.'_'.$teacherleaveapplication->academic_year_id.'_'.$teacherleaveapplication->user_id);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Handle the teacherprofile "restored" event.
     *
     * @return void
     */
    public function restored(TeacherLeaveApplication $teacherleaveapplication)
    {
        //
    }

    /**
     * Handle the teacherprofile "force deleted" event.
     *
     * @return void
     */
    public function forceDeleted(TeacherLeaveApplication $teacherleaveapplication)
    {
        //
    }
}
