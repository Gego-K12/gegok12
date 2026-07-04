<?php

namespace Database\Seeders;

use App\Helpers\SiteHelper;
use App\Models\AbsentReason;
use App\Models\Attendance;
use App\Models\School;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

/**
 * Backfills staff attendance for recent weekdays so the Staff Attendance
 * pages (list/report/add) have something to show without submitting the
 * form by hand for every day.
 *
 * Safe to re-run: skips any school/date/session batch that already has
 * attendance rows, matching the uniqueness the app itself enforces
 * (see StaffAttendanceRequest::check_session).
 */
class TestAttendanceDataSeeder extends Seeder
{
    /**
     * Usergroup ids treated as "staff" by the admin Staff Attendance page.
     *
     * @var array<int>
     */
    private $staffUsergroupIds = [5, 8, 10, 11, 12, 13];

    /**
     * How many past weekdays (per school) to backfill attendance for.
     *
     * @var int
     */
    private $weekdaysToSeed = 14;

    /**
     * Chance (0-100) that a given staff member is marked absent for a session.
     *
     * @var int
     */
    private $absentChance = 10;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (env('APP_ENV') != 'local' && env('APP_ENV') != 'development') {
            return;
        }

        $absentReasons = AbsentReason::where('status', 1)->get();

        if ($absentReasons->isEmpty()) {
            $this->command->warn('No active absent reasons found, skipping TestAttendanceDataSeeder.');

            return;
        }

        $schools = School::where('status', 1)->get();

        foreach ($schools as $school) {
            $academicYear = SiteHelper::getAcademicYear($school->id);

            if (! $academicYear) {
                continue;
            }

            $recordedBy = User::where([
                ['school_id', $school->id],
                ['usergroup_id', 3],
                ['status', 'active'],
            ])->first();

            $staff = User::whereIn('usergroup_id', $this->staffUsergroupIds)
                ->where([
                    ['school_id', $school->id],
                    ['status', 'active'],
                ])
                ->get();

            if (! $recordedBy || $staff->isEmpty()) {
                continue;
            }

            $academicYearStart = Carbon::parse($academicYear->start_date)->startOfDay();

            foreach ($this->recentWeekdays($academicYearStart) as $date) {
                foreach (['forenoon', 'afternoon'] as $session) {
                    $this->seedSession($school, $academicYear, $recordedBy, $staff, $absentReasons, $date, $session);
                }
            }
        }
    }

    /**
     * Create attendance rows for one school/date/session batch, unless one
     * already exists.
     *
     * @param  School  $school
     * @param  \App\Models\AcademicYear  $academicYear
     * @param  User  $recordedBy
     * @param  \Illuminate\Support\Collection  $staff
     * @param  \Illuminate\Support\Collection  $absentReasons
     * @param  Carbon  $date
     * @param  string  $session
     * @return void
     */
    private function seedSession($school, $academicYear, $recordedBy, $staff, $absentReasons, $date, $session)
    {
        $alreadySeeded = Attendance::where([
            ['school_id', $school->id],
            ['academic_year_id', $academicYear->id],
            ['date', $date->toDateString()],
            ['session', $session],
            ['standardLink_id', null],
        ])->exists();

        if ($alreadySeeded) {
            return;
        }

        $rows = [];
        $now = $date->copy()->toDateTimeString();

        foreach ($staff as $member) {
            $isAbsent = rand(1, 100) <= $this->absentChance;

            $rows[] = [
                'school_id' => $school->id,
                'academic_year_id' => $academicYear->id,
                'standardLink_id' => null,
                'user_id' => $member->id,
                'date' => $date->toDateString(),
                'session' => $session,
                'status' => $isAbsent ? 0 : 1,
                'reason_id' => $isAbsent ? $absentReasons->random()->id : null,
                'remarks' => $isAbsent ? 'Seeded test data' : null,
                'recorded_by' => $recordedBy->id,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        Attendance::insert($rows);
    }

    /**
     * Build the list of weekday dates to seed, most recent first, going back
     * from today but never before the academic year's start date.
     *
     * @param  Carbon  $academicYearStart
     * @return array<Carbon>
     */
    private function recentWeekdays($academicYearStart)
    {
        $dates = [];
        $cursor = Carbon::today();

        while (count($dates) < $this->weekdaysToSeed && $cursor->gte($academicYearStart)) {
            if ($cursor->isWeekday()) {
                $dates[] = $cursor->copy();
            }

            $cursor->subDay();
        }

        return $dates;
    }
}
