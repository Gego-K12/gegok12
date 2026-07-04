<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StaffAttendanceRequest;
use App\Http\Resources\AttendanceUser as AttendanceUserResource;
use App\Http\Resources\StaffAttendanceResource;
use App\Http\Resources\Teacherlist as TeacherlistResource;
use App\Models\AbsentReason;
use App\Models\Attendance;
use App\Models\User;
use App\Models\Users\TeacherUser;
use App\Traits\AcademicProcess;
use App\Traits\Common;
use App\Traits\LogActivity;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Log;

/**
 * Class StaffAttendanceController
 *
 * Handles staff attendance operations including
 * attendance creation, listing absentees, and
 * staff attendance reports for the admin module.
 */
class StaffAttendanceController extends Controller
{
    use AcademicProcess;
    use Common;
    use LogActivity;

    /**
     * Display a listing of the resource.
     *
     * Currently unused.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Get staff list and absent reasons.
     *
     * Returns active staff members for attendance
     * along with configured absent reasons.
     *
     * @return array
     */
    public function list()
    {
        $array = [];
        $school_id = Auth::user()->school_id;

        $academic_year = SiteHelper::getAcademicYear($school_id);

        $staff = User::whereIn('usergroup_id', [5, 8, 10, 11, 12, 13])
            ->where([
                ['school_id', Auth::user()->school_id],
                ['status', 'active'],
            ])
            ->get()
            ->sortBy('userprofile.firstname');

        $stafflist = TeacherlistResource::collection($staff);

        $absentReasonlist = AbsentReason::where('status', 1)->get();

        $array['stafflist'] = $stafflist;
        $array['absentReasonlist'] = $absentReasonlist;

        return $array;
    }

    /**
     * Show the staff attendance creation form.
     *
     * @return Response
     */
    public function create()
    {
        //
        return view('/admin/staff_attendance/create');
    }

    /**
     * Show the staff register page (calendar + daily present/absent list).
     *
     * @return Response
     */
    public function register()
    {
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);

        return view('/admin/staff_attendance/register', [
            'academicYearStart' => date('Y-m-d', strtotime($academic_year->start_date)),
            'today' => date('Y-m-d'),
        ]);
    }

    /**
     * Get the dates within a month that have staff attendance recorded, so
     * the register calendar can distinguish recorded from unrecorded days.
     *
     * @param  string  $month  Y-m
     * @return array
     */
    public function registerMonthSummary($month)
    {
        if (! preg_match('/^\d{4}-\d{2}$/', $month)) {
            abort(422, 'Invalid month');
        }

        $school_id = Auth::user()->school_id;
        $start = Carbon::createFromFormat('Y-m-d', $month.'-01')->startOfMonth();
        $end = $start->copy()->endOfMonth();

        $recordedDates = Attendance::where('school_id', $school_id)
            ->whereNull('standardLink_id')
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->selectRaw('DATE(date) as recorded_date')
            ->distinct()
            ->pluck('recorded_date');

        return ['recordedDates' => $recordedDates];
    }

    /**
     * Get the present/absent status of every staff member for a given date.
     *
     * @param  string  $date
     * @return array
     */
    public function registerByDate($date)
    {
        if (! preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            abort(422, 'Invalid date');
        }

        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);

        $staff = User::with(['userprofile', 'usergroup'])
            ->whereIn('usergroup_id', [5, 8, 10, 11, 12, 13])
            ->where([
                ['school_id', $school_id],
                ['status', 'active'],
            ])
            ->get()
            ->sortBy('userprofile.firstname');

        $attendanceByUser = Attendance::with('absentReason')
            ->where([
                ['school_id', $school_id],
                ['academic_year_id', $academic_year->id],
                ['date', $date],
                ['standardLink_id', null],
            ])
            ->get()
            ->groupBy('user_id');

        $sessionsRecorded = [
            'forenoon' => Attendance::where([['school_id', $school_id], ['date', $date], ['session', 'forenoon'], ['standardLink_id', null]])->exists(),
            'afternoon' => Attendance::where([['school_id', $school_id], ['date', $date], ['session', 'afternoon'], ['standardLink_id', null]])->exists(),
        ];

        $list = $staff->map(function ($member) use ($attendanceByUser) {
            $records = $attendanceByUser->get($member->id, collect());

            $sessions = [];
            foreach (['forenoon', 'afternoon'] as $session) {
                $record = $records->firstWhere('session', $session);

                $sessions[$session] = $record ? [
                    'status' => (bool) $record->status,
                    'reason' => $record->status ? null : optional($record->absentReason)->title,
                    'remarks' => $record->remarks,
                ] : null;
            }

            $recorded = array_filter($sessions);
            $overall = empty($recorded)
                ? 'not_recorded'
                : (collect($recorded)->every(fn ($s) => $s['status']) ? 'present' : 'absent');

            return [
                'user_id' => $member->id,
                'name' => $member->FullName,
                'designation' => optional($member->usergroup)->name,
                'sessions' => $sessions,
                'overall' => $overall,
            ];
        })->values();

        return [
            'date' => $date,
            'sessionsRecorded' => $sessionsRecorded,
            'staff' => $list,
            'summary' => [
                'present' => $list->where('overall', 'present')->count(),
                'absent' => $list->where('overall', 'absent')->count(),
                'not_recorded' => $list->where('overall', 'not_recorded')->count(),
            ],
        ];
    }

    /**
     * Store staff attendance records.
     *
     * Creates attendance entries for staff,
     * logs the activity, and returns success response.
     *
     * @return array
     */
    public function store(StaffAttendanceRequest $request)
    {
        //
        try {
            $school_id = Auth::user()->school_id;
            $academic_year = SiteHelper::getAcademicYear($school_id);
            $admin = Auth::id();

            $attendance = $this->createStaffAttendance(
                $school_id,
                $academic_year->id,
                $admin,
                $request
            );

            $message = trans('messages.add_success_msg', ['module' => 'Attendance']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $attendance,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_ADD_ATTENDANCE,
                $message
            );

            $res['success'] = $message;

            return $res;
        } catch (Exception $e) {
            // Log::info($e->getMessage());
        }
    }

    /**
     * Get today absent staff count.
     *
     * @return array
     */
    public function staff()
    {
        //
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);

        $absentees = Attendance::ByRole(5)->where([
            ['school_id', $school_id],
            ['academic_year_id', $academic_year->id],
            ['date', date('Y-m-d')],
            ['status', 0],
        ])->count();

        return ['studentAbsentees' => $absentees];
    }

    /**
     * Get today absent staff list.
     *
     * @return AnonymousResourceCollection
     */
    public function stafflist()
    {
        //
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);

        $absentees = Attendance::ByRole(5)->where([
            ['school_id', $school_id],
            ['academic_year_id', $academic_year->id],
            ['date', date('Y-m-d')],
            ['status', 0],
        ])->get();

        $attendance = StaffAttendanceResource::collection($absentees);

        return $attendance;
    }

    /**
     * Get monthly attendance summary for a staff member.
     *
     * Calculates total absent days grouped by month
     * within the academic year.
     *
     * @param  string  $name
     * @return array
     */
    public function getStudentAttendance($name)
    {
        //
        $staff = User::where('name', $name)->first();

        $array = [];
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
        $startDate = date('Y-m-d', strtotime($academic_year->start_date));
        $endDate = date('Y-m-d', strtotime($academic_year->end_date));

        $attendances = Attendance::with('user')->where([
            ['school_id', Auth::user()->school_id],
            ['academic_year_id', $academic_year->id],
            ['user_id', $staff->id],
            ['status', 0],
            ['date', '>=', $startDate],
            ['date', '<=', $endDate],
        ])
            ->orderBy('date', 'DESC')
            ->get()
            ->groupBy([
                function ($attendance) {
                    return Carbon::parse($attendance->date)->format('M Y');
                },
                'user_id',
            ]);

        $i = 0;

        foreach ($attendances as $key => $attendance) {
            foreach ($attendance as $user_id => $value) {
                if ($attendance[$user_id] != null) {
                    $array['staff'][$key] = count($value) * 0.5;
                } else {
                    $array['staff'][$key] = 0;
                }
            }
            $i++;
        }

        return $array;
    }

    /**
     * Show detailed attendance records for a staff member.
     *
     * @param  string  $name
     * @return AnonymousResourceCollection
     */
    public function showAttendance($name)
    {
        //
        $staff = TeacherUser::where('name', $name)->first();

        $attendances = AttendanceUserResource::collection(
            $staff->AttendanceUserAbsent
        );

        return $attendances;
    }
}
