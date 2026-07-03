<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Teacher;

use App\Events\Notification\SingleNotificationEvent;
use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\LogRequest;
use App\Http\Resources\CallLog as CallLogResource;
use App\Models\CallLog;
use App\Traits\Common;
use App\Traits\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CallLogController extends Controller
{
    use Common;
    use LogActivity;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function showlist(Request $request)
    {
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);

        $calllog = CallLog::where([['school_id', Auth::user()->school_id], ['academic_year_id', $academic_year->id]])->get();

        $callloglist = CallLogResource::collection($calllog);

        return $callloglist;
    }

    public function index()
    {
        // $calllog=CallLog::where('id',$id)->first();
        return view('/teacher/calllog/index');
    }

    public function create()
    {
        return view('/teacher/calllog/create');
    }

    public function store(LogRequest $request)
    {
        try {
            $school_id = Auth::user()->school_id;

            $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);

            $calllog = new CallLog;

            $calllog->school_id = $school_id;
            $calllog->academic_year_id = $academic_year->id;
            $calllog->name = $request->name;
            $calllog->calling_purpose = $request->calling_purpose;
            $calllog->call_type = $request->call_type;
            $calllog->incoming_number = $request->incoming_number;
            $calllog->outgoing_number = $request->outgoing_number;
            $calllog->call_date = $request->call_date;
            $calllog->start_time = $request->start_time;
            $calllog->end_time = $request->end_time;

            $calllog->description = $request->description;
            $calllog->entry_by = Auth::user()->name;

            $calllog->save();

            $message = trans('messages.add_success_msg', ['module' => 'Call Log']);

            $data = [];

            $data['user'] = SiteHelper::getAdmin($school_id);
            $data['details'] = trans('notification.add_success_msg', ['user' => Auth::user()->FullName, 'module' => 'Call Log']);

            event(new SingleNotificationEvent($data));

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $calllog,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_ADD_CALL_LOG,
                $message
            );

            $res['success'] = trans('messages.add_success_msg', ['module' => 'Call Log']);

            return $res;
        } catch (Exception $e) {
        }
    }

    public function show($id)
    {
        $calllog = CallLog::where('id', $id)->get();

        $calllog = CallLogResource::collection($calllog);

        return $calllog;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $calllog = CallLog::where([['id', $id], ['school_id', Auth::user()->school_id]])->first();

        return view('/teacher/calllog/edit', ['calllog' => $calllog]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        try {
            $school_id = Auth::user()->school_id;

            $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
            $calllog = CallLog::find($id);

            $calllog->school_id = $school_id;
            $calllog->academic_year_id = $academic_year->id;
            $calllog->name = $request->name;
            $calllog->calling_purpose = $request->calling_purpose;
            $calllog->call_type = $request->call_type;
            $calllog->incoming_number = $request->incoming_number;
            $calllog->outgoing_number = $request->outgoing_number;
            $calllog->call_date = $request->call_date;
            $calllog->start_time = $request->start_time;
            $calllog->end_time = $request->end_time;

            /* $hours = $end_time->diffInHours($start_time);
             $minutes = $end_time->diffInMinutes($start_time);
             $seconds = $end_time->diffInSeconds($start_time);

            $calllog->duration=$hours . ':' . $minutes. ':' .$seconds;*/

            $calllog->description = $request->description;
            $calllog->entry_by = Auth::user()->name;

            $calllog->save();

            $message = trans('messages.update_success_msg', ['module' => 'Call Log']);

            $data = [];

            $data['user'] = SiteHelper::getAdmin($school_id);
            $data['details'] = trans('notification.update_success_msg', ['user' => Auth::user()->FullName, 'module' => 'Call Log']);

            event(new SingleNotificationEvent($data));

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $calllog,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_EDIT_CALL_LOG,
                $message
            );

            $res['success'] = $message;

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
        try {
            $calllog = CallLog::where('id', $id)->first();

            $calllog->delete();

            $message = trans('messages.delete_success_msg', ['module' => 'Call Log']);

            $data = [];

            $data['user'] = SiteHelper::getAdmin(Auth::user()->school_id);
            $data['details'] = trans('notification.delete_success_msg', ['user' => Auth::user()->FullName, 'module' => 'Call Log']);

            event(new SingleNotificationEvent($data));

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $calllog,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_DELETE_CALL_LOG,
                $message
            );

            $res['message'] = $message;

            return $res;
        } catch (Exception $e) {
        }
    }
}
