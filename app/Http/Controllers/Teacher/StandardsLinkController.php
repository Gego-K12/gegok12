<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Teacher;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\ClassEvent as ClassEventResource;
use App\Http\Resources\ClassTeacher as ClassTeacherResource;
use App\Http\Resources\ClassUpcomingExam as ClassUpcomingExamResource;
use App\Http\Resources\Fee as FeeResource;
use App\Http\Resources\FeePayment as FeePaymentResource;
use App\Http\Resources\Notice as NoticeResource;
use App\Http\Resources\Timetable as TimetableResource;
use App\Http\Resources\User as UserResource;
use App\Models\Events;
use App\Models\ExamSchedule;
use App\Models\Fee;
use App\Models\FeePayment;
use App\Models\NoticeBoard;
use App\Models\StandardLink;
use App\Models\Teacherlink;
use App\Models\Timetable;
use App\Models\User;
use App\Models\Users\StudentUser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class StandardsLinkController extends Controller
{
    //

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function list()
    {
        //
        $standardLinks = SiteHelper::getStandardLinkList(Auth::user()->school_id);

        return $standardLinks;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);
        $standardLinks = StandardLink::with('teacherlink', 'standard', 'section', 'teacher')->where([['school_id', $school_id], ['academic_year_id', $academic_year->id]])->get();

        return view('/teacher/standardlinks/index', ['standardLinks' => $standardLinks]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);
        $standardLink = StandardLink::with('standard', 'section', 'teacher')->where([['id', $id], ['school_id', $school_id], ['academic_year_id', $academic_year->id]])->first();
        if (Gate::allows('standardlink', $standardLink)) {
            $academic_year = SiteHelper::getAcademicYear($school_id);
            $user_count = SiteHelper::getClassStudentCount($school_id, $academic_year->id, $id);
            $teacherlink = $standardLink->teacherlink->pluck('teacher_id')->toArray();

            return view('/teacher/standardlinks/show', ['standardLink' => $standardLink, 'user_count' => $user_count, 'teacherlink' => $teacherlink]);
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
    public function showNotice(Request $request)
    {
        //
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
        $notice = NoticeBoard::where([['school_id', Auth::user()->school_id], ['academic_year_id', $academic_year->id]])->where('expire_date', '>=', date('Y-m-d'))->where('status', 1);
        if (count((array) \Request::getQueryString()) > 0) {
            if ($request->showExpired == 'true') {
                $notice = $notice->orWhere('status', 0)->orWhere('expire_date', '<=', date('Y-m-d'));
            }

            if ($request->standardLink_id != '') {
                $notice = $notice->where('standardLink_id', $request->standardLink_id);
            }
        }
        $notice = $notice->get();
        $noticelist = NoticeResource::collection($notice);

        return $noticelist;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function showTimetable($id)
    {
        //
        $standardLink = StandardLink::where('id', $id)->first();
        if (Gate::allows('standardlink', $standardLink)) {
            $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);

            $timetable = Timetable::where([
                ['school_id', Auth::user()->school_id],
                ['academic_year_id', $academic_year->id],
                ['standardLink_id', $id],
            ])->get();

            $array['periodCount'] = count($timetable[0]['schedule']);

            $array['timetable'] = TimetableResource::collection($timetable);

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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function showStudents($id)
    {
        //
        $standardLink = StandardLink::where('id', $id)->first();

        if (Gate::allows('standardlink', $standardLink)) {
            $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
            $users = StudentUser::where('school_id', Auth::user()->school_id)->whereHas('studentAcademic', function ($query) use ($academic_year) {
                $query->where('academic_year_id', $academic_year->id);
            })->ByRole(6)->ByStandard($id)->get();
            $students = UserResource::collection($users);

            return $students;
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

            if (class_exists('Gegok12\Fee\Models\Fee')) {// new
                $fees = \Gegok12\Fee\Models\Fee::where([
                        ['school_id', Auth::user()->school_id],
                        ['academic_year_id', $academic_year->id],
                        ['standardLink_id', $id],
                    ])->orWhere('standardLink_id', null)->get();
            } else { // new
                $fees = Fee::where([
                    ['school_id', Auth::user()->school_id],
                    ['academic_year_id', $academic_year->id],
                    ['standardLink_id', $id],
                ])->orWhere('standardLink_id', null)->get();
            }

            if (class_exists('Gegok12\Fee\Http\Resources\Fee')) {// new
                $array['feelist'] = \Gegok12\Fee\Http\Resources\Fee::collection($fees);
            } else {
                $array['feelist'] = FeeResource::collection($fees);
            }

            foreach ($fees as $fee) {
                if (class_exists('Gegok12\Fee\Models\FeePayment')) {// new
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

                $paid[$fee->id] = $feepayments->pluck('user_id')->toArray();

                $unpaid[$fee->id] = User::whereHas('studentAcademicLatest', function ($query) use ($id) {
                    $query->where('standardLink_id', $id);
                })->pluck('id')->toArray();

                $students[$fee->id] = User::whereIn('id', array_diff($unpaid[$fee->id], $paid[$fee->id]));
                $array['unpaidCount'][$fee->id] = $students[$fee->id]->count();
                $array['unpaidStudents'][$fee->id] = UserResource::collection($students[$fee->id]->get());
                if (class_exists('Gegok12\Fee\Http\Resources\FeePayment')) {// new
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
}
