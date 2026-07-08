<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Attendance as AttendanceResource;
use App\Http\Resources\ClassConferenceResource;
use App\Http\Resources\ClassEvent as ClassEventResource;
use App\Http\Resources\ClassTeacher as ClassTeacherResource;
use App\Http\Resources\ClassUpcomingExam as ClassUpcomingExamResource;
use App\Http\Resources\Classwall\ClassPost as ClassPostResource;
use App\Http\Resources\Fee as FeeResource;
use App\Http\Resources\FeePayment as FeePaymentResource;
use App\Http\Resources\Timetable as TimetableResource;
use App\Http\Resources\User as UserResource;
use App\Models\Attendance;
use App\Models\Events;
use App\Models\ExamSchedule;
use App\Models\Fee;
use App\Models\FeePayment;
use App\Models\Post;
use App\Models\StandardLink;
use App\Models\Teacherlink;
use App\Models\Timetable;
use App\Models\User;
use App\Models\Users\StudentUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

/**
 * Class StandardsLinkDetailsController
 *
 * Provides detailed information for a specific
 * standard–section (class) including students,
 * teachers, attendance, timetable, exams, fees,
 * events, class wall, and online conferences.
 */
class StandardsLinkDetailsController extends Controller
{
    /**
     * Show standard overview details.
     *
     * Displays class information and student count
     * after authorization check.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
        $school_id = Auth::user()->school_id;
        $standardLink = StandardLink::with('standard', 'section', 'teacher')->where([['id', $id], ['school_id', $school_id]])->first();
        if (Gate::allows('standardlink', $standardLink)) {
            $academic_year = SiteHelper::getAcademicYear($school_id);
            $user_count = SiteHelper::getClassStudentCount($school_id, $academic_year->id, $id);

            return view('/admin/school/standardlinks/show', ['standardLink' => $standardLink, 'user_count' => $user_count]);
        } else {
            abort(403);
        }
    }

    /**
     * Get timetable details for a standard.
     *
     * @param  int  $id
     * @return array
     */
    public function showTimetable($id)
    {
        //
        $standardLink = StandardLink::where('id', $id)->first();
        if (Gate::allows('standardlink', $standardLink)) {
            $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
            if (class_exists('Gegok12\Timetable\Models\Timetable')) {
                $timetable = \Gegok12\Timetable\Models\Timetable::where([
                    ['school_id', Auth::user()->school_id],
                    ['academic_year_id', $academic_year->id],
                    ['standardLink_id', $id],
                ])->get();
            } else {
                $timetable = Timetable::where([
                    ['school_id', Auth::user()->school_id],
                    ['academic_year_id', $academic_year->id],
                    ['standardLink_id', $id],
                ])->get();
            }

            $array['periodCount'] = count($timetable[0]['schedule']);
            if (class_exists('Gegok12\Timetable\Http\Resources\Timetable')) {
                $array['timetable'] = \Gegok12\Timetable\Http\Resources\Timetable::collection($timetable);
            } else {
                $array['timetable'] = TimetableResource::collection($timetable);
            }

            return $array;
        } else {
            abort(403);
        }
    }

    /**
     * Get teachers assigned to a standard.
     *
     * @param  int  $id
     * @return AnonymousResourceCollection
     */
    public function showTeachers($id)
    {
        //
        $standardLink = StandardLink::where('id', $id)->first();
        if (Gate::allows('standardlink', $standardLink)) {
            $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
            $teacherlink = Teacherlink::where([
                ['school_id', Auth::user()->school_id],
                ['academic_year_id', $academic_year->id],
                ['standardLink_id', $id],
            ])->get();
            $teachers = ClassTeacherResource::collection($teacherlink);

            return $teachers;
        } else {
            abort(403);
        }
    }

    /**
     * Get students enrolled in a standard.
     *
     * @param  int  $id
     * @return AnonymousResourceCollection
     */
    public function showStudents($id)
    {
        //
        $standardLink = StandardLink::where('id', $id)->first();

        if (Gate::allows('standardlink', $standardLink)) {
            $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
            $users = StudentUser::where([['school_id', Auth::user()->school_id], ['status', '!=', 'exit']])->whereHas('studentAcademic', function ($query) use ($academic_year) {
                $query->where('academic_year_id', $academic_year->id);
            })->ByRole(6)->ByStandard($id)->get()->sortBy('userprofile.firstname');
            $students = UserResource::collection($users);

            return $students;
        } else {
            abort(403);
        }
    }

    /**
     * Get aggregated student attendance summary.
     *
     * Groups absent sessions month-wise.
     *
     * @param  int  $id
     * @return array
     */
    public function getStudentAttendance($id)
    {
        //
        $standardLink = StandardLink::where('id', $id)->first();

        if (Gate::allows('standardlink', $standardLink)) {
            $array = [];
            $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
            $startDate = date('Y-m-d', strtotime($academic_year->start_date));
            $endDate = date('Y-m-d', strtotime($academic_year->end_date));

            $attendances = Attendance::with('user')->where([
                ['school_id', Auth::user()->school_id],
                ['academic_year_id', $academic_year->id],
                ['standardLink_id', $id],
                ['status', 0],
                ['date', '>=', $startDate],
                ['date', '<=', $endDate],
            ])->orderBy('date', 'DESC')->get()->groupBy([function ($attendance) {
                return Carbon::parse($attendance->date)->format('M Y');
            }, 'user_id']);
            $i = 0;

            foreach ($attendances as $key => $attendance) {
                $array['months'][$i] = $key;
                foreach ($attendance as $user_id => $sessions) {
                    $user = User::where('id', $user_id)->first();
                    $array['students'][$user->name]['FullName'] = $user->FullName;
                    if ($attendance[$user_id] != null) {
                        $array['students'][$user->name][$key] = count($sessions) * 0.5;
                    } else {
                        $array['students'][$user->name][$key] = 0;
                    }
                }
                $i++;
            }

            return $array;
        } else {
            abort(403);
        }
    }

    /**
     * Get daily attendance with chart data.
     *
     * @param  int  $id
     * @return array
     */
    public function getAttendance($id)
    {
        //
        $standardLink = StandardLink::where('id', $id)->first();

        if (Gate::allows('standardlink', $standardLink)) {
            $array = [];
            $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
            $array['select_month'] = Carbon::now()->format('m-Y');
            $months = [];
            $start = strtotime('last month', strtotime($academic_year->start_date));
            $now = strtotime($academic_year->end_date);
            $i = 0;
            // while(($start = strtotime('next month', $start)) <= $now)
            // {
            //     $array['months']->$i->id = date('m-Y', $start);
            //     $array['months']->$i->name = date('M Y', $start);
            //     $i++;
            // }

            // new
            while (($start = strtotime('next month', $start)) <= $now) {
                $months[] = [
                    'id' => date('m-Y', $start),
                    'name' => date('M Y', $start),
                ];
            }
            $array['months'] = $months;

            $startDate = Carbon::now()->firstOfMonth()->format('Y-m-d');
            $endDate = Carbon::now()->lastOfMonth()->format('Y-m-d');

            $attendances = Attendance::where([
                ['school_id', Auth::user()->school_id],
                ['academic_year_id', $academic_year->id],
                ['standardLink_id', $id],
                ['status', 0],
                ['date', '>=', $startDate],
                ['date', '<=', $endDate],
            ])->orderBy('date', 'DESC')->get();
            $array['attendances'] = AttendanceResource::collection($attendances)->groupBy([function ($attendance) {
                return Carbon::parse($attendance->date)->format('d M Y');
            }, 'session']);

            $attendancechart = Attendance::where([
                ['school_id', Auth::user()->school_id],
                ['academic_year_id', $academic_year->id],
                ['standardLink_id', $id],
                ['date', '>=', $startDate],
                ['date', '<=', $endDate],
            ])->orderBy('date', 'DESC')->get()->groupBy([function ($attendancechart) {
                return Carbon::parse($attendancechart->date)->format('d M Y');
            }, 'session', 'status']);

            $i = 0;
            foreach ($attendancechart as $date => $attendance) {
                $array['dates'][$i] = $date;
                foreach ($attendance as $session => $student) {
                    if ($session == 'forenoon') {
                        $forenoonpresent[$i] = isset($student[1]) ? count($student[1]) : 0;
                        $forenoonabsent[$i] = isset($student[0]) ? count($student[0]) : 0;
                    } else {
                        $afternoonpresent[$i] = isset($student[1]) ? count($student[1]) : 0;
                        $afternoonabsent[$i] = isset($student[0]) ? count($student[0]) : 0;
                    }
                }
                for ($j = 0; $j < count($attendancechart); $j++) {
                    $array['forenoon_present'][$j] = $forenoonpresent[$j];
                    $array['forenoon_absent'][$j] = $forenoonabsent[$j];
                    $array['afternoon_present'][$j] = $afternoonpresent[$j];
                    $array['afternoon_absent'][$j] = $afternoonabsent[$j];
                }
                $i++;
            }

            return $array;
        } else {
            abort(403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function showAttendance(Request $request, $id)
    {
        //
        $standardLink = StandardLink::where('id', $id)->first();

        if (Gate::allows('standardlink', $standardLink)) {
            $date = Carbon::createFromFormat('m-Y', $request->select_month);
            $array = [];
            $array['select_month'] = $request->select_month;
            $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
            $months = [];
            $start = strtotime('last month', strtotime($academic_year->start_date));
            $now = strtotime($academic_year->end_date);
            $i = 0;
            // while(($start = strtotime('next month', $start)) <= $now)
            // {
            //     $array['months']->$i->id = date('m-Y', $start);
            //     $array['months']->$i->name = date('M Y', $start);
            //     $i++;
            // }

            // new
            while (($start = strtotime('next month', $start)) <= $now) {
                $months[] = [
                    'id' => date('m-Y', $start),
                    'name' => date('M Y', $start),
                ];
            }

            $array['months'] = $months;

            $startDate = Carbon::parse($date)->firstOfMonth()->format('Y-m-d');
            $endDate = Carbon::parse($date)->lastOfMonth()->format('Y-m-d');

            $attendances = Attendance::where([
                ['school_id', Auth::user()->school_id],
                ['academic_year_id', $academic_year->id],
                ['standardLink_id', $id],
                ['status', 0],
                ['date', '>=', $startDate],
                ['date', '<=', $endDate],
            ])->orderBy('date', 'DESC')->get();
            $array['attendances'] = AttendanceResource::collection($attendances)->groupBy([function ($attendance) {
                return Carbon::parse($attendance->date)->format('d M Y');
            }, 'session']);

            $attendancechart = Attendance::where([
                ['school_id', Auth::user()->school_id],
                ['academic_year_id', $academic_year->id],
                ['standardLink_id', $id],
                ['date', '>=', $startDate],
                ['date', '<=', $endDate],
            ])->orderBy('date', 'DESC')->get()->groupBy([function ($attendancechart) {
                return Carbon::parse($attendancechart->date)->format('d M Y');
            }, 'session', 'status']);

            $i = 0;
            foreach ($attendancechart as $date => $attendance) {
                $array['dates'][$i] = $date;
                foreach ($attendance as $session => $student) {
                    if ($session == 'forenoon') {
                        $forenoonpresent[$i] = count($student[1]);
                        $forenoonabsent[$i] = count($student[0]);
                    } else {
                        $afternoonpresent[$i] = count($student[1]);
                        $afternoonabsent[$i] = count($student[0]);
                    }
                }
                for ($j = 0; $j < count($attendancechart); $j++) {
                    $array['forenoon_present'][$j] = $forenoonpresent[$j];
                    $array['forenoon_absent'][$j] = $forenoonabsent[$j];
                    $array['afternoon_present'][$j] = $afternoonpresent[$j];
                    $array['afternoon_absent'][$j] = $afternoonabsent[$j];
                }
                $i++;
            }

            return $array;
        } else {
            abort(403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function showEvents($id)
    {
        //
        $standardLink = StandardLink::where('id', $id)->first();

        if (Gate::allows('standardlink', $standardLink)) {
            $events = Events::where('standard_id', $standardLink->id);

            // new
            if (! config('gexam.enabled', false)) {
                $events = $events->where('category', '!=', 'exam');
            }

            $events = $events->orderBy('start_date', 'DESC')->get();
            $events = ClassEventResource::collection($events);

            return $events;
        } else {
            abort(403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function showUpcomingExams($id)
    {
        //
        $standardLink = StandardLink::where('id', $id)->first();

        if (Gate::allows('standardlink', $standardLink)) {
            $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);

            if (class_exists('Gegok12\Exam\Models\ExamSchedule')) {
                $upcomingExams = \Gegok12\Exam\Models\ExamSchedule::whereHas('exam', function ($query) use ($academic_year) {
                    $query->where('academic_year_id', $academic_year->id);
                })->where('standard_id', $standardLink->id)->where('start_time', '>=', date('Y-m-d H:i:s'))->orderBy('start_time', 'ASC')->get();
            } else {
                $upcomingExams = ExamSchedule::whereHas('exam', function ($query) use ($academic_year) {
                    $query->where('academic_year_id', $academic_year->id);
                })->where('standard_id', $standardLink->id)->where('start_time', '>=', date('Y-m-d H:i:s'))->orderBy('start_time', 'ASC')->get();

            }
            $upcomingExams = ClassUpcomingExamResource::collection($upcomingExams)->groupBy('exam_id');

            return $upcomingExams;
        } else {
            abort(403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function showPastExams($id)
    {
        //
        $standardLink = StandardLink::where('id', $id)->first();

        if (Gate::allows('standardlink', $standardLink)) {
            $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
            $start_date = date('Y-m-d H:i:s', strtotime($academic_year->start_date));

            if (class_exists('Gegok12\Exam\Models\ExamSchedule')) {
                $pastExams = \Gegok12\Exam\Models\ExamSchedule::whereHas('exam', function ($query) use ($academic_year) {
                    $query->where('academic_year_id', $academic_year->id);
                })->where('standard_id', $standardLink->id)->where([
                    ['start_time', '>=', $start_date],
                    ['start_time', '<=', date('Y-m-d H:i:s')],
                ])->orderBy('start_time', 'ASC')->get();
            } else {
                $pastExams = ExamSchedule::whereHas('exam', function ($query) use ($academic_year) {
                    $query->where('academic_year_id', $academic_year->id);
                })->where('standard_id', $standardLink->id)->where([
                    ['start_time', '>=', $start_date],
                    ['start_time', '<=', date('Y-m-d H:i:s')],
                ])->orderBy('start_time', 'ASC')->get();
            }
            $pastExams = ClassUpcomingExamResource::collection($pastExams)->groupBy('exam_id');

            return $pastExams;
        } else {
            abort(403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function showFees($id)
    {
        //
        $standardLink = StandardLink::where('id', $id)->first();

        if (Gate::allows('standardlink', $standardLink)) {
            $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);

            // new condition
            if (class_exists('Gegok12\Fee\Models\Fee')) {
                $fees = \Gegok12\Fee\Models\Fee::where([
                    ['school_id', Auth::user()->school_id],
                    ['academic_year_id', $academic_year->id],
                    ['standardLink_id', $id],
                ])->orWhere('standardLink_id', null)->get();
            } else {
                $fees = Fee::where([
                    ['school_id', Auth::user()->school_id],
                    ['academic_year_id', $academic_year->id],
                    ['standardLink_id', $id],
                ])->orWhere('standardLink_id', null)->get();
            }
            // end

            if (class_exists('Gegok12\Fee\Http\Resources\Fee')) {
                $array['feelist'] = \Gegok12\Fee\Http\Resources\Fee::collection($fees);
            } else {
                $array['feelist'] = FeeResource::collection($fees);
            }

            foreach ($fees as $fee) {
                // new condition
                if (class_exists('Gegok12\Fee\Models\FeePayment')) {
                    $feepayments = \Gegok12\Fee\Models\FeePayment::where('fee_id', $fee->id)->whereHas('user', function ($query) use ($id) {
                        $query->whereHas('studentAcademicLatest', function ($q) use ($id) {
                            $q->where('standardLink_id', $id);
                        });
                    });
                } else {
                    $feepayments = FeePayment::where('fee_id', $fee->id)->whereHas('user', function ($query) use ($id) {
                        $query->whereHas('studentAcademicLatest', function ($q) use ($id) {
                            $q->where('standardLink_id', $id);
                        });
                    });
                }
                // end

                $paid[$fee->id] = $feepayments->pluck('user_id')->toArray();

                $unpaid[$fee->id] = User::whereHas('studentAcademicLatest', function ($query) use ($id) {
                    $query->where('standardLink_id', $id);
                })->pluck('id')->toArray();

                $students[$fee->id] = User::whereIn('id', array_diff($unpaid[$fee->id], $paid[$fee->id]));
                $array['unpaidCount'][$fee->id] = $students[$fee->id]->count();
                $array['unpaidStudents'][$fee->id] = UserResource::collection($students[$fee->id]->get());

                // new
                if (class_exists('Gegok12\Fee\Http\Resources\FeePayment')) {
                    $feepayment = \Gegok12\Fee\Http\Resources\FeePayment::collection($feepayments->get());
                } else {
                    $feepayment = FeePaymentResource::collection($feepayments->get());
                }

                $array['paidCount'][$fee->id] = $feepayments->count();
                $array['paidStudents'][$fee->id] = $feepayment;
            }

            return $array;
        } else {
            abort(403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function showClassWall($id)
    {
        //
        $standardLink = StandardLink::where('id', $id)->first();

        if (Gate::allows('standardlink', $standardLink)) {
            $school_id = Auth::user()->school_id;
            $academic_year = SiteHelper::getAcademicYear($school_id);

            $posts = Post::where([['school_id', $school_id], ['academic_year_id', $academic_year->id], ['is_posted', 1], ['visible_for', $id]])->orderBy('post_created_at', 'DESC')->get();

            $posts = ClassPostResource::collection($posts);

            return $posts;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function showComments($post_id)
    {
        //
        $post = Post::where('id', $post_id)->first();

        $array = [];

        $array['comment_list']['comments_count'] = count($post->PostComments);
        $array['comment_list']['comments'] = $post->PostComments;

        return $array;
    }

    /**
     * Get online conference sessions for a class.
     *
     * @param  int  $id
     * @return AnonymousResourceCollection
     */
    public function showConference($id)
    {
        //
        $standardLink = StandardLink::where('id', $id)->first();

        if (Gate::allows('standardlink', $standardLink)) {
            $school_id = Auth::user()->school_id;
            $academic_year = SiteHelper::getAcademicYear($school_id);
            if (class_exists('Gegok12\Videoroom\Models\VideoConference')) {

                $conference = \Gegok12\Videoroom\Models\VideoConference::where([['school_id', $school_id], ['academic_year_id', $academic_year->id], ['standard', $standardLink->id]])->orderBy('created_at','DESC')->paginate(10);

                $conference = ClassConferenceResource::collection($conference);

                return $conference;
            }
        }
    }
}
