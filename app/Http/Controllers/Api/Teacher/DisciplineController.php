<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Api\Teacher;

use App\Events\Notification\SingleNotificationEvent;
use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\Teacher\PerformanceAddRequest;
use App\Http\Requests\API\Teacher\PerformanceUpdateRequest;
use App\Http\Resources\API\Teacher\DisciplineResource;
use App\Http\Resources\Studentlist as StudentlistResource;
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

        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
        $disciplines = Discipline::where([['school_id', Auth::user()->school_id], ['academic_year_id', $academic_year->id], ['reported_by', Auth::user()->id]]);

        $disciplines = $disciplines->get();
        $disciplines = DisciplineResource::collection($disciplines);

        return $disciplines;
    }

    public function type()
    {
        $array = [];

        $type = [
            [
                'id' => 'performance',
                'name' => 'Performance',
            ], [
                'id' => 'others',
                'name' => 'Others',
            ],
        ];

        $array['type'] = $type;

        return $array;

    }

    public function media_type()
    {
        $array = [];

        $media_type = [['id' => 'video', 'name' => 'Video'], ['id' => 'audio', 'name' => 'Audio'], ['id' => 'image', 'name' => 'Image']];
        $array['media_type'] = $media_type;

        return $array;

    }

    public function store(PerformanceAddRequest $request)
    {
        //
        try {
            $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
            // $student = User::where('name',$request->ref_name)->first();
            $admin = User::where('school_id', Auth::user()->school_id)->ByRole(3)->first();

            $discipline = new Discipline;

            $discipline->school_id = Auth::user()->school_id;
            $discipline->academic_year_id = $academic_year->id;
            $discipline->user_id = $request->student_id;
            $discipline->standardLink_id = $request->standardLink_id;
            $discipline->incident_date = date('Y-m-d H:i:s');
            $discipline->reported_by = Auth::user()->id;
            $discipline->incident_detail = $request->incident_detail;
            $discipline->type = $request->type;
            $discipline->media_type = $request->media_type;

            $file = $request->file('attachments');
            if ($file) {
                $folder = Auth::user()->school->slug.'/uploads/admin/discipline';
                $attachments = $this->uploadFile($folder, $file);
            }
            $discipline->attachments = $attachments;

            $discipline->save();

            $array = [];

            $array['user'] = $admin;
            $array['details'] = 'New Performance record Added';

            event(new SingleNotificationEvent($array));

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $discipline,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_ADD_DISCIPLINE,
                trans('messages.add_success_msg', ['module' => 'Performance'])
            );

            $res['success'] = trans('messages.add_success_msg', ['module' => 'Performance']);

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
        // $array['incident_date']     = date('d-m-Y H:i:s',strtotime($discipline->incident_date));
        // $array['teacher_id']        = $discipline->reported_by;
        $array['incident_detail'] = $discipline->incident_detail;
        $array['type'] = $discipline->type;
        $array['media_type'] = $discipline->media_type;
        // $array['response']          = $discipline->response;
        // $array['action_taken']      = $discipline->action_taken;
        $array['attachments'] = $discipline->attachments == null ? '' : $discipline->AttachmentPath;
        $array['notify_parents'] = $discipline->notify_parents;
        // $array['is_seen']           = $discipline->is_seen;
        // $array['standardLinklist']  = $standardLinklist;
        // $array['studentlist']       = $studentlist;
        // $array['teacherlist']       = $teacherlist;

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
    public function update(PerformanceUpdateRequest $request, $id)
    {
        //
        try {

            $discipline = Discipline::where('id', $id)->first();

            $discipline->user_id = $request->student_id;
            $discipline->incident_date = date('Y-m-d H:i:s');
            $discipline->standardLink_id = $request->standardLink_id;
            // $discipline->reported_by        =   $request->teacher_id;
            $discipline->incident_detail = $request->incident_detail;
            $discipline->type = $request->type;

            $file = $request->file('attachments');
            if ($file) {
                $folder = Auth::user()->school->slug.'/uploads/admin/discipline';
                $attachments = $this->uploadFile($folder, $file);
                $discipline->attachments = $attachments;
                $discipline->media_type = $request->media_type;
            }

            $discipline->save();

            $admin = User::where('school_id', Auth::user()->school_id)->ByRole(3)->first();

            $array = [];

            $array['user'] = $admin;
            $array['details'] = 'Performance record updated';

            event(new SingleNotificationEvent($array));

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

            $discipline->delete();

            $admin = User::where('school_id', Auth::user()->school_id)->ByRole(3)->first();

            $array = [];

            $array['user'] = $admin;
            $array['details'] = 'Performance record deleted';

            event(new SingleNotificationEvent($array));

            $message = trans('messages.delete_success_msg', ['module' => 'Discipline']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $discipline,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_DELETE_DISCIPLINE,
                $message
            );

            return response()->json([
                'success' => true,
                'message' => $message,
            ], 200);

            // return $message;
        } catch (Exception $e) {
        }
    }
}
