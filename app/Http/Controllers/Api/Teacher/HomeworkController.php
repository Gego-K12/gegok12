<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Api\Teacher;

use App\Events\Notification\ClassNotificationEvent;
use App\Events\StandardPushEvent;
use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\HomeworkRequest;
use App\Http\Resources\API\Teacher\Homework as HomeworkResource;
use App\Http\Resources\API\Teacher\StandardLink as StandardLinkResource;
use App\Http\Resources\API\Teacher\StudentHomework as StudentHomeworkResource;
use App\Models\Homework;
use App\Models\StandardLink;
use App\Models\StudentHomework;
use App\Models\Teacherlink;
use App\Traits\Common;
use App\Traits\LogActivity;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Log;

class HomeworkController extends Controller
{
    use Common;
    //
    use LogActivity;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function pendingList()
    {
        //
        $homework = Homework::where('school_id', Auth::user()->school_id)->where('date', '>=', date('Y-m-d'))->orderBy('date', 'DESC')->whereHas('standardLink', function ($query) {
            $query->where('class_teacher_id', Auth::id());
        })->get();

        $homeworklist = HomeworkResource::collection($homework);

        return $homeworklist;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function completedList()
    {
        //
        $homework = Homework::where('school_id', Auth::user()->school_id)->where('date', '<', date('Y-m-d'))->orderBy('date', 'DESC')->whereHas('standardLink', function ($query) {
            $query->where('class_teacher_id', Auth::id());
        })->get();

        $homeworklist = HomeworkResource::collection($homework);

        return $homeworklist;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
        $school_id = Auth::user()->school_id;

        $academic_year = SiteHelper::getAcademicYear($school_id);

        $standardLinks = StandardLink::where([
            ['school_id', $school_id],
            ['academic_year_id', $academic_year->id],
            ['class_teacher_id', Auth::id()],
        ])->pluck('id')->toArray();

        $teacherlinks = Teacherlink::where([
            ['school_id', $school_id],
            ['academic_year_id', $academic_year->id],
            ['teacher_id', Auth::id()],
        ])->pluck('standardLink_id')->toArray();

        $standards = array_merge($standardLinks, $teacherlinks);

        $standardLink = StandardLink::whereIn('id', $standards)->get();

        $standards = StandardLinkResource::collection($standardLink);

        return $standards;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(HomeworkRequest $request)
    {
        //
        try {
            $school_id = Auth::user()->school_id;
            $academic_year = SiteHelper::getAcademicYear($school_id);

            $work = new Homework;

            $work->school_id = $school_id;
            $work->academic_year_id = $academic_year->id;
            $work->standardLink_id = $request->standardLink_id;
            // $work->subject_id           =   $request->subject_id;
            // $work->teacher_id           =   Auth::id();
            $work->description = $request->description;
            $work->date = date('Y-m-d', strtotime($request->date));

            $file = $request->file('attachment');
            if ($file) {
                $folder = Auth::user()->school->slug.'/homework';
                $path = $this->uploadFile($folder, $file);
                $work->attachment = $path;
            }

            $work->save();

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
                $work,
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
        $studentHomeworks = StudentHomework::where('homework_id', $id)->paginate(10);

        $studentHomeworks = StudentHomeworkResource::collection($studentHomeworks);

        return $studentHomeworks;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
        $homework = Homework::where('id', $id)->first();

        $array = [];

        $array['standardLink_id'] = $homework->standardLink_id;
        $array['description'] = $homework->description;
        $array['date'] = date('d-m-Y', strtotime($homework->date));
        $array['attachment'] = $homework->attachment == null ? '' : $homework->AttachmentPath;
        $array['pending_count'] = $homework->PendingCount;

        return $array;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(HomeworkRequest $request, $id)
    {
        //
        try {
            $work = Homework::where('id', $id)->first();

            $work->standardLink_id = $request->standardLink_id;
            // $work->subject_id           =   $request->subject_id;
            // $work->teacher_id           =   Auth::id();
            $work->description = $request->description;
            $work->date = date('Y-m-d', strtotime($request->date));

            $file = $request->file('attachment');
            if ($file) {
                $folder = Auth::user()->school->slug.'/homework';
                $path = $this->uploadFile($folder, $file);
                $work->attachment = $path;
            } else {
                $work->attachment = $request->attachment;
            }

            $work->save();

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
                $work,
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
        try {
            $homework = Homework::where('id', $id)->first();
            if (\Gate::allows('homework', $homework)) {
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
            } else {
                abort(403);
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
