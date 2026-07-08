<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use App\Events\Notification\ClassNotificationEvent;
use App\Events\StandardPushEvent;
use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\HomeworkRequest;
use App\Http\Resources\API\Teacher as TeacherResource;
use App\Http\Resources\Homework as HomeworkResource;
use App\Http\Resources\Subject as SubjectResource;
use App\Models\Homework;
use App\Models\StandardLink;
use App\Models\Teacherlink;
use App\Traits\Common;
use App\Traits\LogActivity;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Log;

/**
 * Class HomeWorkController
 *
 * Handles homework management for the admin panel.
 *
 * Responsibilities:
 * - Listing homework (API & views)
 * - Creating homework
 * - Updating homework
 * - Deleting homework
 * - Sending notifications
 * - Logging homework-related activities
 */
class HomeWorkController extends Controller
{
    use Common;
    use LogActivity;

    /**
     * Get a paginated list of homework with optional filters.
     *
     * Supports:
     * - Past homework
     * - Standard-based filtering
     *
     * @return AnonymousResourceCollection
     */
    public function showList(Request $request)
    {
        //
        $homework = Homework::where('school_id', Auth::user()->school_id)
            ->where('date', '>=', date('Y-m-d'))
            ->orderBy('date', 'DESC');

        if (count((array) \Request::getQueryString()) > 0) {
            if ($request->showPast == 'true') {
                $homework = $homework->orWhere('date', '<', date('Y-m-d'));
            }

            if ($request->standardLink_id != '') {
                $homework = $homework->where('standardLink_id', $request->standardLink_id);
            }
        }

        $homework = $homework->orderBy('id', 'DESC')->paginate(10);
        $homeworklist = HomeworkResource::collection($homework);

        return $homeworklist;
    }

    /**
     * Display the homework listing page.
     *
     * @return View
     */
    public function index()
    {
        $query = \Request::getQueryString();

        return view('/admin/homework/index', ['query' => $query]);
    }

    /**
     * Show the homework creation page.
     *
     * @return View
     */
    public function create()
    {
        $standard = \Request::get('standardLink_id') ? \Request::get('standardLink_id') : '';

        return view('/admin/homework/create', ['standard' => $standard]);
    }

    /**
     * Get data required for homework creation.
     *
     * Includes:
     * - Standards
     * - Subjects
     * - Teachers
     *
     * @return array<string, mixed>
     */
    public function list()
    {
        //
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);
        $standardLink = SiteHelper::getStandardLinkList(Auth::user()->school_id);

        $teacherlink = Teacherlink::where([
            ['school_id', $school_id],
            ['academic_year_id', $academic_year->id],
        ])->get();

        $subjectlist = SubjectResource::collection($teacherlink)->groupby('standardLink_id');
        $teacherlist = TeacherResource::collection($teacherlink)->groupby(['standardLink_id', 'subject_id']);

        $array = [];
        $array['standardlist'] = $standardLink;
        $array['subjectlist'] = $subjectlist;
        $array['teacherlist'] = $teacherlist;

        return $array;
    }

    /**
     * Store a newly created homework.
     *
     * Handles:
     * - File upload
     * - Notifications
     * - Activity logging
     *
     * @return array<string, string>|null
     */
    public function store(HomeworkRequest $request)
    {
        //
        try {
            $school_id = Auth::user()->school_id;
            $academic_year = SiteHelper::getAcademicYear($school_id);

            $homework = new Homework;

            $homework->school_id = $school_id;
            $homework->academic_year_id = $academic_year->id;
            $homework->standardLink_id = $request->standardLink_id;

            $standardLink = StandardLink::where('id', $request->standardLink_id)->first();

            $homework->subject_id = $request->subject_id;
            $homework->teacher_id = $request->teacher_id ?? $standardLink->class_teacher_id;
            $homework->description = $request->description;
            $homework->date = date('Y-m-d', strtotime($request->date));

            $file = $request->file('attachment');
            if ($file) {
                $folder = Auth::user()->school->slug.'/homework';
                $path = $this->uploadFile($folder, $file);
                $homework->attachment = $path;
            }

            $homework->save();

            $data = [];
            $data['school_id'] = Auth::user()->school_id;
            $data['standard_id'] = $request->standardLink_id;
            $data['message'] = 'New Home Work Added';
            $data['type'] = 'homework';

            event(new StandardPushEvent($data));

            $array = [];
            $array['school_id'] = Auth::user()->school_id;
            $array['standardLink_id'] = $request->standardLink_id;
            $array['details'] = trans('notification.homework_add_success_msg');

            event(new ClassNotificationEvent($array));

            $message = trans('messages.add_success_msg', ['module' => 'Homeworks']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $homework,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_ADD_HOMEWORK,
                $message
            );

            $res['success'] = $message;

            return $res;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Display the specified homework.
     *
     * @param  int  $id
     * @return View
     */
    public function show($id)
    {
        $homework = Homework::where('id', $id)->first();

        return view('/admin/homework/show', ['homework' => $homework]);
    }

    /**
     * Show the homework edit page.
     *
     * @param  int  $id
     * @return View
     */
    public function edit($id)
    {
        $homework = Homework::where('id', $id)->first();

        return view('/admin/homework/edit', ['homework' => $homework]);
    }

    /**
     * Get data required for editing homework.
     *
     * @param  int  $id
     * @return array<string, mixed>
     */
    public function editList($id)
    {
        //
        $standardLink = SiteHelper::getStandardLinkList(Auth::user()->school_id);
        $teacherlink = Teacherlink::get();

        $subjectlist = SubjectResource::collection($teacherlink)->groupby('standardLink_id');
        $teacherlist = TeacherResource::collection($teacherlink)->groupby(['standardLink_id', 'subject_id']);

        $homework = Homework::where('id', $id)->first();

        $array = [];
        $array['standardLink_id'] = $homework->standardLink_id;
        $array['standardLink_name'] = $homework->standardLink->StandardSection;
        $array['pending_count'] = $homework->PendingCount;
        $array['subject_id'] = $homework->subject_id;
        $array['teacher_id'] = $homework->teacher_id;
        $array['description'] = $homework->description;
        $array['date'] = date('Y-m-d', strtotime($homework->date));
        $array['show_date'] = date('d-m-Y', strtotime($homework->date));
        $array['attachment'] = $homework->attachment == null ? '' : $homework->AttachmentPath;
        $array['standardlist'] = $standardLink;
        $array['subjectlist'] = $subjectlist;
        $array['teacherlist'] = $teacherlist;
        $array['viewers'] = count($homework->viewers);

        return $array;
    }

    /**
     * Update the specified homework.
     *
     * @param  int  $id
     * @return array<string, string>|null
     */
    public function update(HomeworkRequest $request, $id)
    {
        //
        try {
            $homework = Homework::where('id', $id)->first();

            $homework->standardLink_id = $request->standardLink_id;
            $homework->subject_id = $request->subject_id;
            $homework->teacher_id = $request->teacher_id;
            $homework->description = $request->description;
            $homework->date = date('Y-m-d', strtotime($request->date));

            $file = $request->file('attachment');
            if ($file) {
                $folder = Auth::user()->school->slug.'/homework';
                $path = $this->uploadFile($folder, $file);
                $homework->attachment = $path;
            } else {
                $homework->attachment = $request->attachment;
            }

            $homework->save();

            $data = [];
            $data['school_id'] = Auth::user()->school_id;
            $data['standard_id'] = $request->standardLink_id;
            $data['message'] = 'Home Work Updated';
            $data['type'] = 'homework';

            event(new StandardPushEvent($data));

            $array = [];
            $array['school_id'] = Auth::user()->school_id;
            $array['standardLink_id'] = $request->standardLink_id;
            $array['details'] = trans('notification.homework_update_success_msg');

            event(new ClassNotificationEvent($array));

            $message = trans('messages.update_success_msg', ['module' => 'Homeworks']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $homework,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_EDIT_HOMEWORK,
                $message
            );

            $res['success'] = $message;

            return $res;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Remove the specified homework.
     *
     * Authorization is handled via Gate.
     *
     * @param  int  $id
     * @return array<string, string>|null
     */
    public function destroy($id)
    {
        try {
            $homework = Homework::where('id', $id)->first();

            if (Gate::allows('homework', $homework)) {
                $array = [];
                $array['school_id'] = Auth::user()->school_id;
                $array['standardLink_id'] = $homework->standardLink_id;
                $array['details'] = trans('notification.homework_delete_success_msg');

                $homework->delete();

                $message = trans('messages.delete_success_msg', ['module' => 'Homework']);

                event(new ClassNotificationEvent($array));

                $ip = $this->getRequestIP();
                $this->doActivityLog(
                    $homework,
                    Auth::user(),
                    ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                    LOGNAME_DELETE_HOMEWORK,
                    $message
                );

                $res['success'] = $message;

                return $res;
            }

            abort(403);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
