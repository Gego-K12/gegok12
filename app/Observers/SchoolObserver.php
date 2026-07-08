<?php

namespace App\Observers;

use App\Models\AcademicYear;
use App\Models\School;
use App\Models\SchoolDetail;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Str;
use Log;

class SchoolObserver
{
    /**
     * Handle the school "created" event.
     *
     * @return void
     */
    public function created(School $school)
    {
        //
        try {
            $slug = Str::slug($school->name, '-');
            $school = School::where('id', $school->id)->first();
            $school->slug = $slug;
            $school->save();

            $keys = ['about_us', 'admission_open', 'admission_close_message', 'admission_close_on', 'affiliation_no', 'affiliated_by', 'board', 'date_of_establishment', 'landline_no', 'moto', 'school_logo', 'website'];
            foreach ($keys as $key) {
                $detail = SchoolDetail::create([
                    'school_id' => $school->id,
                    'meta_key' => $key,
                    'meta_value' => '-',
                ]);
            }

            if (AcademicYear::where('school_id', $school->id)->exists()) {
                return;
            }

            $currentYear = Carbon::now()->year;
            $prevYear = $currentYear - 1;

            // Previous Academic Year
            AcademicYear::create([
                'school_id' => $school->id,
                'name' => $prevYear.'-'.$currentYear,
                'description' => 'Previous Academic Year',
                'start_date' => $prevYear.'-06-01',
                'end_date' => $currentYear.'-04-30',
                'status' => 0, // OLD
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Current Academic Year
            AcademicYear::create([
                'school_id' => $school->id,
                'name' => $currentYear.'-'.($currentYear + 1),
                'description' => 'Current Academic Year',
                'start_date' => $currentYear.'-06-01',
                'end_date' => ($currentYear + 1).'-04-30',
                'status' => 1, // CURRENT
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Handle the school "updated" event.
     *
     * @return void
     */
    public function updated(School $school)
    {
        //
    }

    /**
     * Handle the school "deleted" event.
     *
     * @return void
     */
    public function deleted(School $school)
    {
        //
    }

    /**
     * Handle the school "restored" event.
     *
     * @return void
     */
    public function restored(School $school)
    {
        //
    }

    /**
     * Handle the school "force deleted" event.
     *
     * @return void
     */
    public function forceDeleted(School $school)
    {
        //
    }
}
