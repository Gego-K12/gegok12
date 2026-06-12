<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin\Approval;

use App\Http\Resources\Approval\HomeworkTeacher as TeacherResource;
use App\Http\Resources\Homework as HomeworkResource;
use App\Events\Notification\SingleNotificationEvent;
use App\Events\Notification\ClassNotificationEvent;
use App\Http\Resources\Subject as SubjectResource;
use App\Http\Requests\HomeworkRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\HomeworkApproval;
use App\Events\StandardPushEvent;
use App\Events\SinglePushEvent;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use App\Models\StandardLink;
use App\Traits\LogActivity;
use App\Models\Teacherlink;
use App\Helpers\SiteHelper;
use App\Models\Homework;
use App\Traits\Common;
use App\Models\User;
use Exception;
use Log;

/**
 * Class HomeWorkController
 *
 * Controller for managing homework approvals and related actions
 * within the admin approval area.
 *
 * @package App\Http\Controllers\Admin\Approval
 */
class HomeWorkController extends Controller
{

    use LogActivity;
    use Common;

    public function showList(Request $request, $status)
    {
        //
        $school_id      =   Auth::user()->school_id;
        $academic_year  =   SiteHelper::getAcademicYear($school_id);
        $homeworks = Homework::where([
            ['school_id', $school_id], 
            ['academic_year_id', $academic_year->id]
        ])
        ->where('status', $status);
        
        if (count((array)\Request::getQueryString()) > 0) {
            if ($request->standardLink_id != '') {
                $homeworks = $homeworks->where('standardLink_id', $request->standardLink_id);
            }

            if ($request->search != '') {
                $homeworks = $homeworks->where('description', 'LIKE', '%' . $request->search . '%')->orWhereHas('subject', function ($q) use ($request) {
                    $q->where('name', 'LIKE', '%' . $request->search . '%');
                });
            }
        }
        $homeworks = $homeworks->orderBy('id', 'DESC')->paginate(10);
        $homeworks = HomeworkResource::collection($homeworks);

        return $homeworks;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = \Request::getQueryString();
        $admin = User::BySchool(Auth::user()->school_id)->ByRole(3)->first();

        if ($admin->id == Auth::id()) {
            $role = 'admin';
        } else {
            $role = 'teacher';
        }

        return view('/admin/homework/index', ['role' => $role, 'query' => $query]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $standard = \Request::get('standardLink_id') ? \Request::get('standardLink_id') : '';
        return view('/admin/homework/create', ['standard' => $standard]);
    }

    public function list()
    {
        //
        $school_id      =   Auth::user()->school_id;
        $academic_year  =   SiteHelper::getAcademicYear($school_id);
        $standardLink   =   SiteHelper::getStandardLinkList($school_id);

        $teacherlink    =   Teacherlink::where([['school_id', $school_id], ['academic_year_id', $academic_year->id]])->get();

        $subjectlist    =   SubjectResource::collection($teacherlink)->groupby('standardLink_id');

        $teacherlist    =   TeacherResource::collection($teacherlink)->groupby(['standardLink_id', 'subject_id']);

        $array = [];

        $array['standardlist']  =   $standardLink;
        $array['subjectlist']   =   $subjectlist;
        $array['teacherlist']   =   $teacherlist;

        return $array;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HomeworkRequest $request)
    {
        //
        try {
            $school_id = Auth::user()->school_id;
            $academic_year = SiteHelper::getAcademicYear($school_id);

            $homework = new Homework;

            $homework->school_id            =   $school_id;
            $homework->academic_year_id     =   $academic_year->id;
            $homework->standardLink_id      =   $request->standardLink_id;
            $standardLink = StandardLink::where('id', $request->standardLink_id)->first();
            $homework->subject_id           =   $request->subject_id;
            if ($request->teacher_id != null) {
                $homework->teacher_id       =   $request->teacher_id;
            } else {
                $homework->teacher_id       =   $standardLink->class_teacher_id;
            }
            $homework->description          =   $request->description;
            $homework->date                 =   date('Y-m-d', strtotime($request->date));
            $homework->submission_date      =   date('Y-m-d', strtotime($request->submission_date));

            $homework->status      =  $request->status;

            $file = $request->file('attachment');
            if ($file) {
                $folder = Auth::user()->school->slug . '/homework';
                $path = $this->uploadFile($folder, $file);
                $homework->attachment = $path;
            }

            $homework->save();

            $homeworkapproval = new HomeworkApproval;

            $admin = User::BySchool(Auth::user()->school_id)->ByRole(3)->first();

            if ($admin->id != Auth::id()) {
                $homeworkapproval->homework_id  = $homework->id;
                // $homeworkapproval->status       = 'pending';
                $homeworkapproval->status       = 'approved';

                $data = [];

                $data['user']       =   $admin;
                $data['details']    =   trans('notification.homework_add_success_msg');

                event(new SingleNotificationEvent($data));
            } else {
                $homeworkapproval->homework_id  = $homework->id;
                $homeworkapproval->comments     = 'Created By Admin';
                $homeworkapproval->approved_by  = Auth::id();
                $homeworkapproval->approved_at  = date('Y-m-d');
                $homeworkapproval->status       = 'approved';

                $data = [];

                $data['school_id']      =   Auth::user()->school_id;
                $data['standard_id']    =   $request->standardLink_id;
                $data['message']        =   'New Home Work Added';
                $data['type']           =   'homework';

                event(new StandardPushEvent($data));

                $array = [];

                $array['school_id']         = Auth::user()->school_id;
                $array['standardLink_id']   = $request->standardLink_id;
                $array['details']           = trans('notification.homework_add_success_msg');

                event(new ClassNotificationEvent($array));
            }
            $homeworkapproval->save();

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
            //dd($e->getMessage());
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $homework = Homework::where('id', $id)->first();

        return view('/admin/homework/show', ['homework' => $homework]);
    }

    public function view($id)
    {
        $homework = Homework::where('id', $id)->first();
        $viewers = $homework->viewers()->latest()->paginate(10);
        // dd($viewers);

        return view('/admin/homework/view', ['homework' => $homework, 'viewers' => $viewers]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $homework = Homework::where('id', $id)->first();

        return view('/admin/homework/edit', ['homework' => $homework]);
    }

    public function editList($id)
    {
        //
        $school_id      =   Auth::user()->school_id;
        $academic_year  =   SiteHelper::getAcademicYear($school_id);
        $standardLink   =   SiteHelper::getStandardLinkList($school_id);

        $teacherlink    =   Teacherlink::where([['school_id', $school_id], ['academic_year_id', $academic_year->id]])->get();

        $subjectlist    =   SubjectResource::collection($teacherlink)->groupby('standardLink_id');

        $teacherlist    =   TeacherResource::collection($teacherlink)->groupby(['standardLink_id', 'subject_id']);

        $homework = Homework::where('id', $id)->first();

        $array = [];

        $array['standardLink_id']   =   $homework->standardLink_id;
        $array['standardLink_name'] =   $homework->standardLink->StandardSection;
        $array['pending_count']     =   $homework->PendingCount;
        $array['subject_id']        =   $homework->subject_id;
        $array['teacher_id']        =   $homework->teacher_id;
        $array['description']       =   $homework->description;
        $array['date']              =   $homework->date == null ? null : date('Y-m-d', strtotime($homework->date));
        $array['show_date']         =   $homework->date == null ? null : date('d-m-Y', strtotime($homework->date));
        $array['attachment']        =   $homework->attachment == null ? '' : $homework->AttachmentPath;
        $array['standardlist']      =   $standardLink;
        $array['subjectlist']       =   $subjectlist;
        $array['teacherlist']       =   $teacherlist;
        $array['submission_date']   =   date('Y-m-d', strtotime($homework->submission_date));
        $array['viewers']           =   count($homework->viewers);

        $array['status']           =   $homework->status;

        return $array;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(HomeworkRequest $request, $id)
    {
        //
        try {
            $homework = Homework::where('id', $id)->first();

            $homework->standardLink_id      =   $request->standardLink_id;
            $standardLink = StandardLink::where('id', $request->standardLink_id)->first();
            $homework->subject_id           =   $request->subject_id;
            if ($request->teacher_id != null) {
                $homework->teacher_id       =   $request->teacher_id;
            } else {
                $homework->teacher_id       =   $standardLink->class_teacher_id;
            }
            $homework->description          =   $request->description;
            $homework->date                 =   date('Y-m-d', strtotime($request->date));
            $homework->submission_date      =   date('Y-m-d', strtotime($request->submission_date));
            $homework->status      =  $request->status;


            $file = $request->file('attachment');
            if ($file) {
                $folder = Auth::user()->school->slug . '/homework';
                $path = $this->uploadFile($folder, $file);
                $homework->attachment = $path;
            }


            $homework->save();

            $admin = User::BySchool(Auth::user()->school_id)->ByRole(3)->first();

            if ($admin->id != Auth::id()) {
                $data1 = [];

                $data1['user']       =   $admin;
                $data1['details']    =   trans('notification.homework_update_success_msg');

                event(new SingleNotificationEvent($data1));
            } else {
                $data = [];

                $data['school_id']      =   Auth::user()->school_id;
                $data['standard_id']    =   $request->standardLink_id;
                $data['message']        =   'Home Work Updated';
                $data['type']           =   'homework';

                event(new StandardPushEvent($data));

                $array = [];

                $array['school_id']         = Auth::user()->school_id;
                $array['standardLink_id']   = $request->standardLink_id;
                $array['details']           = trans('notification.homework_update_success_msg');

                event(new ClassNotificationEvent($array));
            }

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
            //dd($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $homework = Homework::where('id', $id)->first();
            if (Gate::allows('homework', $homework)) {
                $homework->delete();

                $admin = User::BySchool(Auth::user()->school_id)->ByRole(3)->first();

                if ($admin->id != Auth::id()) {
                    $data = [];

                    $data['user']       =   $admin;
                    $data['details']    =   trans('notification.homework_delete_success_msg');

                    event(new SingleNotificationEvent($data));

                    /*$array=[];

                    $array['school_id'] =  Auth::user()->school_id;
                    $array['user_id']   =  $admin->id;
                    $array['message']   = 'Assignment Deleted';
                    $array['type']      = 'assignment';
                            
                    event(new SinglePushEvent($array));*/
                }

                $message = trans('messages.delete_success_msg', ['module' => 'Homework']);

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
            //dd($e->getMessage());
        }
    }
}
