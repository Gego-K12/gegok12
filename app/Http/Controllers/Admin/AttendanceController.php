<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AttendanceAddRequest;
use App\Http\Resources\Attendance as AttendanceResource;
use App\Http\Resources\AttendanceStudentList as AttendanceStudentListResource;
use App\Http\Resources\StandardLink as StandardLinkResource;
use App\Models\AbsentReason;
use App\Models\Attendance;
use App\Models\StandardLink;
use App\Models\StudentAcademic;
use App\Traits\AcademicProcess;
use App\Traits\Common;
use App\Traits\LogActivity;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use League\Csv\Writer;
use Log;
use App\Models\User;
/**
 * Class AttendanceController
 *
 * Controller for managing student attendance: listing, creating,
 * exporting and retrieving student attendance data.
 */
class AttendanceController extends Controller
{
    use AcademicProcess;
    use Common;
    use LogActivity;

    /**
     * Show the student attendance list page.
     *
     * @return Response
     */
    public function list()
    {
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);

        $standardlistResource = SiteHelper::getStandardLinkList($school_id);
        // Convert Resource collection to array for JSON encoding in blade view
        $standardlist = $standardlistResource ? $standardlistResource->toArray(request()) : [];

        $studentAcademic = StudentAcademic::with('user')->where([['school_id', $school_id], ['academic_year_id', $academic_year->id]])->whereHas('user', function ($q) {
            $q->where([['status', 'active'], ['deleted_at', null]]);
        })->get();

        $studentlist = AttendanceStudentListResource::collection($studentAcademic)->groupBy('standardLink_id');
        $absentReasonlist = AbsentReason::where('status', 1)->get();

        return view('/admin/attendance/list', [
            'standardlist' => $standardlist,
            'studentlist' => $studentlist,
            'absentReasonlist' => $absentReasonlist,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);

        $standard = \Request::get('standardLink_id') ? \Request::get('standardLink_id') : '';

        $standardlistResource = SiteHelper::getStandardLinkList($school_id);
        // Convert Resource collection to array for JSON encoding in blade view
        $standardlist = $standardlistResource ? $standardlistResource->toArray(request()) : [];

        $studentAcademic = StudentAcademic::with('user')->where([['school_id', $school_id], ['academic_year_id', $academic_year->id]])->whereHas('user', function ($q) {
            $q->where([['status', 'active'], ['deleted_at', null]]);
        })->get();

        $studentlist = AttendanceStudentListResource::collection($studentAcademic)->groupBy('standardLink_id');
        $absentReasonlist = AbsentReason::where('status', 1)->get();

        return view('/admin/attendance/create', [
            'standard' => $standard,
            'standardlist' => $standardlist,
            'studentlist' => $studentlist,
            'absentReasonlist' => $absentReasonlist,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(AttendanceAddRequest $request)
    {
        //
        try {
            $school_id = Auth::user()->school_id;
            $academic_year = SiteHelper::getAcademicYear($school_id);
            $admin = Auth::id();

            $attendance = $this->createAttendance($school_id, $academic_year->id, $admin, $request);

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
            Log::info($e->getMessage());
        }
    }

    public function export($standardLink_id)
    {
        try {
            //
            $school_id = Auth::user()->school_id;
            $academic_year = SiteHelper::getAcademicYear($school_id);
            $standardLink = StandardLink::where('id', $standardLink_id)->first();
            $standard = $standardLink->StandardName;
            $section = $standardLink->section->name;
            $csv_name = 'SP Student Attendance Export_'.$standard.'_'.$section.'_'.date('_d-m-Y_H:i').'.csv';
            $attendances = Attendance::where([
                ['school_id', $school_id],
                ['academic_year_id', $academic_year->id],
                ['standardLink_id', $standardLink_id],
                ['status', 0],
            ])->orderBy('date', 'DESC')->get()->groupBy([function ($attendance) {
                return Carbon::parse($attendance->date)->format('d-m-Y');
            }, 'session']);
            $csv = Writer::createFromFileObject(new \SplTempFileObject);

            if (count($attendances) > 0) {
                $csv->insertOne(['Date', 'Forenoon_Absent_Count', 'Afternoon_Absent_Count']);

                $i = 0;
                foreach ($attendances as $key => $attendance) {
                    foreach ($attendance as $key1 => $student) {
                        if ($key1 == 'forenoon') {
                            if ($student != null) {
                                $forenoon_count[$i] = count($student);
                            } else {
                                $forenoon_count[$i] = 0;
                            }
                        } else {
                            if ($student != null) {
                                $afternoon_count[$i] = count($student);
                            } else {
                                $afternoon_count[$i] = 0;
                            }
                        }
                    }
                    $csv->insertOne([$key, $forenoon_count[$i], $afternoon_count[$i]]);
                    $i++;
                }
            } else {
                $csv->insertOne(['No Records Found']);
                $csv->output($csv_name);
            }
            $csv->output($csv_name);
            $message = trans('messages.export_success_msg', ['module' => 'Student Attendance']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                Auth::user(),
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_EXPORT_STUDENT_ATTENDANCE,
                $message
            );
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function student()
    {
        //
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);
        $standardLinklist = SiteHelper::getStandardLinkList($school_id);
        $absentees = Attendance::ByRole(6)->where([
            ['school_id', $school_id],
            ['academic_year_id', $academic_year->id],
            ['date', date('Y-m-d')],
            ['status', 0],
        ]);
        $count = $absentees->count();
        $standards = $absentees->distinct('standardLink_id')->pluck('standardLink_id')->toArray();
        $standardLink = StandardLink::where([['school_id', $school_id], ['academic_year_id', $academic_year->id]])->whereIn('id', $standards)->get();
        $standardLinklist = StandardLinkResource::collection($standardLink);

        return ['studentAbsentees' => $count, 'standardLinklist' => $standardLinklist];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function studentList(Request $request)
    {
        //
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);
        $absentees = Attendance::ByRole(6)->where([
            ['school_id', $school_id],
            ['academic_year_id', $academic_year->id],
            ['date', date('Y-m-d')],
            ['status', 0],
            ['session', $request->session],
        ]);
        if ($request->standardLink_id != '') {
            $absentees = $absentees->where('standardLink_id', $request->standardLink_id);
        }
        $absentees = $absentees->get();

        $attendance = AttendanceResource::collection($absentees);

        return $attendance;
    }
    public function register()
    {
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);

        $standardlistResource = SiteHelper::getStandardLinkList($school_id);
        $standardlist = $standardlistResource ? $standardlistResource->toArray(request()) : [];

        $standardLink_id = count($standardlist) > 0 ? $standardlist[0]['id'] : null;

        return view('/admin/attendance/register', [
            'academicYearStart' => date('Y-m-d', strtotime($academic_year->start_date)),
            'today' => date('Y-m-d'),
            'standardlink_id' => $standardLink_id,
            'standardlist' => $standardlist,
        ]);
    }

    public function registerMonthSummary($standardLink_id, $month)
    {
        if (! preg_match('/^\d{4}-\d{2}$/', $month)) {
            abort(422, 'Invalid month');
        }

        $school_id = Auth::user()->school_id;
        $start = Carbon::createFromFormat('Y-m-d', $month.'-01')->startOfMonth();
        $end = $start->copy()->endOfMonth();

        $recordedDates = Attendance::where([
                ['school_id', $school_id],
                ['standardLink_id', $standardLink_id],
            ])
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->selectRaw('DATE(date) as recorded_date')
            ->distinct()
            ->pluck('recorded_date');

        return ['recordedDates' => $recordedDates];
    }

    public function registerByDate($standardLink_id,$date)
    {
        if (! preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            abort(422, 'Invalid date');
        }

        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);

        $students = StudentAcademic::with(['user.userprofile'])
            ->where([
                ['school_id', $school_id],
                ['academic_year_id', $academic_year->id],
                ['standardLink_id', $standardLink_id],
            ])
            ->whereHas('user', function ($q) {
                $q->where([['status', 'active'], ['deleted_at', null]]);
            })
            ->get()
            ->sortBy('user.userprofile.firstname')
            ->values();

        $attendanceByUser = Attendance::with('absentReason')
            ->where([
                ['school_id', $school_id],
                ['academic_year_id', $academic_year->id],
                ['date', $date],
                ['standardLink_id',$standardLink_id],
            ])
            ->get()
            ->groupBy('user_id');

        $sessionsRecorded = [
            'forenoon' => Attendance::where([['school_id', $school_id], ['date', $date], ['session', 'forenoon'], ['standardLink_id', $standardLink_id]])->exists(),
            'afternoon' => Attendance::where([['school_id', $school_id], ['date', $date], ['session', 'afternoon'], ['standardLink_id', $standardLink_id]])->exists(),
        ];

        $list = $students->map(function ($student) use ($attendanceByUser) {
            $records = $attendanceByUser->get($student->user_id, collect());

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
                'user_id' => $student->user_id,
                'name' => $student->user->FullName,
                'roll_number' => $student->roll_number,
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
}
