<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use App\Http\Resources\StandardLink as StandardLinkResource;
use App\Http\Resources\Teacher as TeacherResource;
use App\Http\Requests\StandardDetailUpdateRequest;
use App\Http\Requests\StandardDetailRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Users\TeacherUser;
use App\Traits\AcademicProcess;
use Illuminate\Http\Request;
use App\Models\ExamSchedule;
use App\Models\AcademicYear;
use App\Models\StandardLink;
use App\Traits\LogActivity;
use App\Models\Teacherlink;
use App\Models\TempTimetable;
use App\Helpers\SiteHelper;
use App\Models\Attendance;
use App\Models\FeePayment;
use App\Models\Timetable;
use App\Models\Standard;
use App\Models\Subject;
use App\Models\Section;
use App\Models\Events;
use App\Traits\Common;
use App\Models\User;
use App\Models\Fee;
use Carbon\Carbon;
use Exception;
use PDF;
use Log;
use DB;

/**
 * Class StandardsLinkController
 *
 * Manages standard–section links including
 * class configuration, subject assignment,
 * teacher mapping, student ID cards, and
 * lifecycle operations for academic standards.
 *
 * @package App\Http\Controllers\Admin
 */
class StandardsLinkController extends Controller
{
    use AcademicProcess;
    use LogActivity;
    use Common;

    /**
     * Display a listing of standard links.
     *
     * Lists all standards with sections for the
     * current academic year ordered by standard
     * and section.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);

        $standards = Standard::where('school_id', $school_id)
            ->orderBy('order')
            ->pluck('id')
            ->toArray();

        if (count($standards) > 0)
        {
            $standard = implode(' ,', $standards);

            $standardLinks = StandardLink::where([
                ['school_id', $school_id],
                ['academic_year_id', $academic_year->id]
            ])
            ->orderByRaw('FIELD(standard_id,' . $standard . ')')
            ->orderBy('section_id')
            ->get();
        }

        $teacher_count = User::where('school_id', $school_id)
            ->where('usergroup_id', 5)
            ->count();

        return view('/admin/school/standardlinks/index', [
            'standardLinks' => $standardLinks,
            'teacher_count' => $teacher_count
        ]);
    }

    /**
     * Fetch required data for standard link creation.
     *
     * Provides academic year, standards, sections,
     * subjects, teachers, and existing standard links.
     *
     * @return array
     */
    public function list()
    {
        $array = [];

        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);

        $subjectlist = Subject::where([
            ['school_id', Auth::user()->school_id],
            ['academic_year_id', $academic_year->id],
            ['type', '!=', 'exam']
        ])->get()->groupBy(['standard_id', 'section_id']);

        $standardlist = DB::table('standards')
            ->where('school_id', Auth::user()->school_id)
            ->orderBy('order')
            ->get();

        $sectionlist = Section::where('school_id', Auth::user()->school_id)
            ->orderBy('name', 'ASC')
            ->get();

        $standardLinks = SiteHelper::getStandardLinkList(Auth::user()->school_id);

        $teacher = TeacherUser::with('userprofile')->where([
            ['school_id', Auth::user()->school_id],
            ['usergroup_id', 5],
            ['status', 'active']
        ])->get()->sortBy('userprofile.firstname');

        $teacherlist = TeacherResource::collection($teacher);

        $array['academic_year_id'] = $academic_year->id;
        $array['standardlink']     = $standardLinks;
        $array['standardlist']     = $standardlist;
        $array['sectionlist']      = $sectionlist;
        $array['subjectlist']      = $subjectlist;
        $array['teacherlist']      = $teacherlist;

        return $array;
    }

    /**
     * Fetch standard details by standard ID.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStandard(Request $request)
    {
        $standard = Standard::where('id', request('standard_id'))->first();
        return response()->json($standard);
    }

    /**
     * Show standard link creation view.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('/admin/school/standardlinks/create');
    }

    /**
     * Store a newly created standard link.
     *
     * Assigns subjects, sections, teachers,
     * and logs the activity.
     *
     * @param StandardDetailRequest $request
     * @return array
     */
    public function store(StandardDetailRequest $request)
    {
        try
        {
            $school_id = Auth::user()->school_id;
            $academic_year = SiteHelper::getAcademicYear($school_id);

            $standard = $this->createStandardLink(
                $school_id,
                $academic_year->id,
                $request
            );

            $message = trans('messages.add_success_msg', ['module' => 'Standard Details']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $standard,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_ADD_STANDARD_DETAIL,
                $message
            );

            return ['success' => $message];
        }
        catch (Exception $e)
        {
            Log::info($e->getMessage());
            dd($e->getMessage());
        }
    }

    /**
     * Fetch standard link details for edit API.
     *
     * @param int $id
     * @return array
     */
    public function editList($id)
    {
        $array = [];

        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
        $standardLink = StandardLink::with('teacherlink')->where('id', $id)->first();

        $subjectlist = Subject::where([
            ['school_id', Auth::user()->school_id],
            ['academic_year_id', $academic_year->id],
            ['standard_id', $standardLink->standard_id],
            ['section_id', $standardLink->section_id],
            ['type', '!=', 'exam']
        ])->get();

        $teacher = TeacherUser::with('userprofile')->where([
            ['school_id', Auth::user()->school_id],
            ['usergroup_id', 5],
            ['status', 'active']
        ])->get()->sortBy('userprofile.firstname');

        $teacherlist = TeacherResource::collection($teacher);
        $teacherLink = $standardLink->getTeacherLinkDetails();

        $array['subjectlist']     = $subjectlist;
        $array['teacherlist']     = $teacherlist;
        $array['standard']        = $standardLink->standard_id;
        $array['standard_id']     = $standardLink->StandardName;
        $array['section_id']      = $standardLink->section->name;
        $array['stream']          = $standardLink->stream;
        $array['class_teacher_id']= $standardLink->class_teacher_id;
        $array['no_of_students']  = $standardLink->no_of_students;
        $array['inputs']          = $teacherLink['inputs'];

        return $array;
    }

    /**
     * Show standard link edit view.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $standardLink = StandardLink::where('id', $id)->first();
        return view('/admin/school/standardlinks/edit', ['standardLink' => $standardLink]);
    }

    /**
     * Update standard link details.
     *
     * @param StandardDetailUpdateRequest $request
     * @param int $id
     * @return array
     */
    public function update(StandardDetailUpdateRequest $request, $id)
    {
        try
        {
            $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
            $school_id = Auth::user()->school_id;

            $standardLink = StandardLink::where('id', $id)->first();

            $this->editStandardLink(
                $school_id,
                $academic_year->id,
                $id,
                $request
            );

            $message = trans('messages.update_success_msg', ['module' => 'Standard Details']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $standardLink,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_EDIT_STANDARD_DETAIL,
                $message
            );

            return ['success' => $message];
        }
        catch (Exception $e)
        {
            Log::info($e->getMessage());
        }
    }

    /**
     * Update standard link status.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, $id)
    {
        try
        {
            $standard = StandardLink::where('id', $id)->first();
            $standard->status = $request->status;
            $standard->save();

            $message = trans('messages.update_status_success_msg', ['module' => 'Standard Details']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $standard,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_UPDATE_STATUS_STANDARD_DETAIL,
                $message
            );

            return redirect('/admin/standardlinks')->with('successmessage', $message);
        }
        catch (Exception $e)
        {
            Log::info($e->getMessage());
        }
    }

    /**
     * Generate student ID card view for a class.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function idcard($id)
    {
        $academic = SiteHelper::getAcademicYear(Auth::user()->school_id);
        $standardLink = StandardLink::where('id', $id)->first();
        $students = SiteHelper::getClassStudents(
            Auth::user()->school_id,
            $academic->id,
            $standardLink->id
        );

        return view('admin.id-card.id-card-new', compact('standardLink', 'students', 'academic'));
    }
    /**
 * Generate and stream student ID cards as a PDF.
 *
 * This method retrieves the current academic year for the authenticated user's school,
 * fetches the selected class/standard link, loads all students of that class,
 * and generates an ID card PDF using a Blade view.
 *
 * @param int $id
 *        The ID of the StandardLink (class/section) for which ID cards are generated.
 *
 * @return \Symfony\Component\HttpFoundation\StreamedResponse
 *         Streams the generated PDF file directly to the browser.
 *
 * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
 *         If the given StandardLink ID does not exist.
 */
public function printidcard($id)
{
    $academic = SiteHelper::getAcademicYear(Auth::user()->school_id);

    $standardLink = StandardLink::where('id', $id)->first();

    $students = SiteHelper::getClassStudents(
        Auth::user()->school_id,
        $academic_year->id,
        $standardLink->id
    );

    $pdf = PDF::loadView(
        'admin/id-card/idcard-print',
        compact('exam', 'students', 'academic')
    );

    return $pdf->stream('result.pdf', ['Attachment' => 0]);
}


    /**
     * Delete a standard link and its dependencies.
     *
     * @param int $id
     * @return mixed
     */
    public function destroy($id)
    {
        try
        {
            $standard = StandardLink::where('id', $id)->first();
            $teacherlinks = Teacherlink::where('standardLink_id', $id);

            if (class_exists('Gegok12\Timetable\Models\TempTimetable'))
            {
                $temptimetable = Gegok12\Timetable\Models\TempTimetable::where('standardLink_id', $id);
            }

            if (Gate::allows('standardlink', $standard))
            {
                if (class_exists('Gegok12\Timetable\Models\TempTimetable'))
                {
                    $temptimetable->delete();
                }

                $teacherlinks->delete();
                $standard->delete();

                $message = trans('messages.delete_success_msg', ['module' => 'Standard Details']);

                $ip = $this->getRequestIP();
                $this->doActivityLog(
                    $standard,
                    Auth::user(),
                    ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                    LOGNAME_DELETE_STANDARD_DETAIL,
                    $message
                );

                return $message;
            }
            else
            {
                abort(403);
            }
        }
        catch (Exception $e)
        {
            Log::info($e->getMessage());
        }
    }
}
