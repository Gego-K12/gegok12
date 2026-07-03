<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\LogRequest;
use App\Http\Resources\CallLog as CallLogResource;
use App\Models\CallLog;
use App\Models\User;
use App\Traits\Common;
use App\Traits\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

/**
 * Class CallLogController
 *
 * Controller for managing call logs: listing, creating, editing,
 * viewing and deleting phone call records for the school.
 */
class CallLogController extends Controller
{
    use Common;
    use LogActivity;
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    /**
     * Return call log records for the authenticated user's school and current academic year.
     *
     * @return AnonymousResourceCollection|array
     */
    public function showlist(Request $request)
    {
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);

        $calllog = CallLog::where([['school_id', Auth::user()->school_id], ['academic_year_id', $academic_year->id]])->get();

        $callloglist = CallLogResource::collection($calllog);

        return $callloglist;
    }

    /**
     * Show the call log index view.
     *
     * @return Response
     */
    public function index()
    {
        return view('/admin/calllog/index');
    }

    /**
     * Show the create call log form.
     *
     * @return Response
     */
    public function create()
    {
        $date = date('Y-m-d');

        return view('/admin/calllog/create', ['date' => $date]);
    }

    /**
     * Store a newly created call log record.
     *
     * @return array
     */
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

            /* $end_time=$request->end_time;
             $start_time=$request->start_time;

              $hours = $end_time->diffInHours($start_time);
              $minutes = $end_time->diffInMinutes($start_time);
              $seconds = $end_time->diffInSeconds($start_time);

             $calllog->duration=$hours . ':' . $minutes. ':' .$seconds;*/
            if ($request->start_time != '' && $request->end_time != '') {
                $end_time = \DateTime::createFromFormat('H:i', $request->end_time);
                $start_time = \DateTime::createFromFormat('H:i', $request->start_time);

                $diff_in_minutes = $end_time->diff($start_time);
                $duration = $diff_in_minutes->format('%h:%i');
            }

            $calllog->duration = $duration;
            $calllog->description = $request->description;
            $calllog->entry_by = Auth::user()->name;

            $calllog->save();

            $message = trans('messages.add_success_msg', ['module' => 'Call Log']);

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

    /**
     * Return a single call log record as a resource collection.
     *
     * @param  int  $id
     * @return AnonymousResourceCollection
     */
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

        /*if(Gate::allows('book',$book))
        {*/
        return view('/admin/calllog/edit', ['calllog' => $calllog]);
        /*  }
          else
          {
              abort(403);
          }*/
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {

        $school_id = Auth::user()->school_id;

        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
        try {
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

            $message = trans('messages.update_success_msg', ['module' => 'call Log']);

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
        \DB::beginTransaction();
        try {
            $calllog = CallLog::where('id', $id)->first();

            $calllog->delete();

            $message = trans('messages.delete_success_msg', ['module' => 'Call Log']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $calllog,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_DELETE_CALL_LOG,
                $message
            );

            $res['message'] = $message;

            \DB::commit();

            return $res;
        } catch (Exception $e) {
            \DB::rollBack();
        }
    }
}
