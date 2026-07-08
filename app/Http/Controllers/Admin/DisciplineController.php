<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use App\Events\Notification\SingleNotificationEvent;
use App\Events\SinglePushEvent;
use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\DisciplineRequest;
use App\Http\Resources\API\Teacher\Studentlist as StudentlistResource;
use App\Http\Resources\Teacherlist as TeacherlistResource;
use App\Models\Discipline;
use App\Models\StandardLink;
use App\Models\StudentAcademic;
use App\Models\User;
use App\Traits\Common;
use App\Traits\LogActivity;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Log;

/**
 * Class DisciplineController
 *
 * Controller for managing discipline records: listing, creating,
 * updating, notifying parents and deleting discipline entries.
 */
class DisciplineController extends Controller
{
    use Common;
    use LogActivity;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        //
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
        $standardLink = StandardLink::with('standard', 'section')->where('school_id', Auth::user()->school_id)->get();
        $disciplines = Discipline::where([['school_id', Auth::user()->school_id], ['academic_year_id', $academic_year->id]]);
        if ($request->type != '') {
            $disciplines = $disciplines->where('type', $request->type);
        }

        if ($request->class != '') {
            $disciplines = $disciplines->whereHas('user', function ($query) use ($request) {
                $query->whereHas('studentAcademicLatest', function ($query) use ($request) {
                    $query->where('standardLink_id', $request->class);
                });
            });
        }

        $disciplines = $disciplines->latest()->paginate(10);

        return view('/admin/discipline/index', ['disciplines' => $disciplines, 'standardLinks' => $standardLink]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function list()
    {
        //
        $array = [];

        $teachers = User::where([['school_id', Auth::user()->school_id], ['usergroup_id', 5]])->get();
        $teacherlist = TeacherlistResource::collection($teachers);

        $array['teacherlist'] = $teacherlist;

        return $array;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
        $ref_name = Request('ref_name') ? Request('ref_name') : '';

        return view('/admin/discipline/create', ['ref_name' => $ref_name]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(DisciplineRequest $request)
    {
        //
        try {
            $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
            $student = User::where('name', $request->ref_name)->first();

            $discipline = new Discipline;

            $discipline->school_id = Auth::user()->school_id;
            $discipline->academic_year_id = $academic_year->id;
            $discipline->user_id = $student->id;
            $discipline->incident_date = date('Y-m-d H:i:s', strtotime($request->incident_date));
            $discipline->reported_by = $request->teacher_id;
            $discipline->incident_detail = $request->incident_detail;
            $discipline->action_taken = $request->action_taken;
            $discipline->notify_parents = $request->notify_parents;
            $discipline->type = 'discipline';

            $file = $request->file('attachments');
            if ($file) {
                $folder = Auth::user()->school->slug.'/uploads/admin/discipline';
                $attachments = $this->uploadFile($folder, $file);
            }
            $discipline->attachments = $attachments;

            $discipline->save();

            if ($discipline->notify_parents == '1') {
                foreach ($student->parents as $parent) {
                    $array = [];

                    $array['school_id'] = $discipline->school_id;
                    $array['user_id'] = $parent->userParent->id;
                    $array['message'] = 'New Discipline Complaint Received';
                    $array['type'] = 'discipline';

                    event(new SinglePushEvent($array));
                }

                $data = [];

                $data['user'] = $student;
                $data['details'] = trans('notification.discipline_add_success_msg');

                event(new SingleNotificationEvent($data));
            }

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $discipline,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_ADD_DISCIPLINE,
                trans('messages.add_success_msg', ['module' => 'Discipline'])
            );

            $res['success'] = trans('messages.add_success_msg', ['module' => 'Discipline']);

            return $res;
        } catch (Exception $e) {
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
        $discipline = Discipline::where('id', $id)->first();

        return view('/admin/discipline/show', ['discipline' => $discipline]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function editlist($id)
    {
        //
        $array = [];

        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
        $standardLinklist = StandardLink::with('standard', 'section')->where('school_id', Auth::user()->school_id)->get();
        $studentAcademic = StudentAcademic::where([['school_id', Auth::user()->school_id], ['academic_year_id', $academic_year->id]])->get();
        $studentlist = StudentlistResource::collection($studentAcademic)->groupBy('standardLink_id');
        $teachers = User::where([['school_id', Auth::user()->school_id], ['usergroup_id', 5]])->get();
        $teacherlist = TeacherlistResource::collection($teachers);

        $discipline = Discipline::where('id', $id)->first();

        $student = StudentAcademic::where('user_id', $discipline->user_id)->first();

        $array['standardlink_id'] = $student->standardLink_id;
        $array['student_id'] = $discipline->user_id;
        $array['incident_date'] = date('d-m-Y H:i:s', strtotime($discipline->incident_date));
        $array['teacher_id'] = $discipline->reported_by;
        $array['incident_detail'] = $discipline->incident_detail;
        $array['response'] = $discipline->response;
        $array['action_taken'] = $discipline->action_taken;
        $array['attachments'] = $discipline->attachments == null ? '' : $discipline->AttachmentPath;
        $array['notify_parents'] = $discipline->notify_parents;
        $array['is_seen'] = $discipline->is_seen;
        $array['standardLinklist'] = $standardLinklist;
        $array['studentlist'] = $studentlist;
        $array['teacherlist'] = $teacherlist;

        return $array;
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
        $discipline = Discipline::where('id', $id)->first();

        return view('/admin/discipline/edit', ['discipline' => $discipline]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(DisciplineRequest $request, $id)
    {
        //
        try {
            $discipline = Discipline::where('id', $id)->first();

            $discipline->user_id = $request->student_id;
            $discipline->incident_date = date('Y-m-d H:i:s', strtotime($request->incident_date));
            $discipline->reported_by = $request->teacher_id;
            $discipline->incident_detail = $request->incident_detail;
            $discipline->action_taken = $request->action_taken;
            $discipline->notify_parents = $request->notify_parents;

            $file = $request->file('attachments');
            if ($file) {
                $folder = Auth::user()->school->slug.'/uploads/admin/discipline';
                $attachments = $this->uploadFile($folder, $file);
                $discipline->attachments = $attachments;
            }
            // $discipline->attachments = $attachments;

            $discipline->save();

            if ($discipline->notify_parents == 1) {
                $student = User::where('id', $request->student_id)->first();
                foreach ($student->parents as $parent) {
                    $array = [];

                    $array['school_id'] = $discipline->school_id;
                    $array['user_id'] = $parent->userParent->id;
                    $array['message'] = 'Discipline Complaint Updated';
                    $array['type'] = 'discipline';

                    event(new SinglePushEvent($array));
                }

                $data = [];

                $data['user'] = $student;
                $data['details'] = trans('notification.discipline_update_success_msg');

                event(new SingleNotificationEvent($data));
            }

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $discipline,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_EDIT_DISCIPLINE,
                trans('messages.update_success_msg', ['module' => 'Discipline'])
            );

            $res['success'] = trans('messages.update_success_msg', ['module' => 'Discipline']);

            return $res;
        } catch (Exception $e) {
        }
    }

    public function updateStatus(Request $request, $id)
    {
        //
        try {
            $discipline = Discipline::where('id', $id)->first();

            $discipline->notify_parents = $request->status;

            $discipline->save();

            if ($discipline->notify_parents == 1) {
                if ($discipline->type == 'discipline') {
                    $type = 'discipline';
                } else {
                    $type = 'performance';
                }

                $student = User::where('id', $discipline->user_id)->first();
                foreach ($student->parents as $parent) {
                    $array = [];

                    $array['school_id'] = $discipline->school_id;
                    $array['user_id'] = $parent->userParent->id;
                    $array['message'] = 'New '.$type.' Record Received';
                    $array['type'] = $type;

                    event(new SinglePushEvent($array));
                }

                $data = [];

                $data['user'] = $student;
                $data['details'] = 'New '.$type.' Record Received';

                event(new SingleNotificationEvent($data));
            }

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $discipline,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_EDIT_DISCIPLINE,
                trans('messages.update_success_msg', ['module' => 'Discipline'])
            );

            $message = trans('messages.update_success_msg', ['module' => 'Discipline']);

            return redirect('/admin/disciplines')->with('successmessage', $message);
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
            $discipline = Discipline::where('id', $id)->first();

            if ($discipline->notify_parents == 1) {
                $student = User::where('id', $discipline->user_id)->first();
                foreach ($student->parents as $parent) {
                    $array = [];

                    $array['school_id'] = $discipline->school_id;
                    $array['user_id'] = $parent->userParent->id;
                    $array['message'] = 'Discipline Complaint Deleted';
                    $array['type'] = 'discipline';

                    event(new SinglePushEvent($array));
                }

                $data = [];

                $data['user'] = $student;
                $data['details'] = trans('notification.discipline_delete_success_msg');

                event(new SingleNotificationEvent($data));
            }

            $discipline->delete();

            $message = trans('messages.delete_success_msg', ['module' => 'Discipline']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $discipline,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_DELETE_DISCIPLINE,
                $message
            );

            return redirect()->back()->with('successmessage',$message);
        } catch (Exception $e) {
        }
    }
}
