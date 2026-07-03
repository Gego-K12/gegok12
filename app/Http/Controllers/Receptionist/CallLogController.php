<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Receptionist;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\LogRequest;
use App\Http\Resources\CallLog as CallLogResource;
use App\Models\CallLog;
use App\Traits\Common;
use App\Traits\LogActivity;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Log;

class CallLogController extends Controller
{
    use Common;
    use LogActivity;

    /**
     * Return a collection of call logs for current school and academic year.
     *
     * @return AnonymousResourceCollection
     */
    public function showlist(Request $request)
    {
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);

        $calllog = CallLog::where([
            ['school_id', Auth::user()->school_id],
            ['academic_year_id', $academic_year->id],
        ])->get();

        return CallLogResource::collection($calllog);
    }

    /**
     * Show the call log index view.
     *
     * @return View
     */
    public function index()
    {
        return view('/reception/calllog/index');
    }

    /**
     * Show the form for creating a new call log.
     *
     * @return View
     */
    public function create()
    {
        $date = date('Y-m-d');

        return view('/reception/calllog/create', ['date' => $date]);
    }

    /**
     * Store a newly created call log in storage.
     *
     * @return array|null Success response array or null on failure
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

            $duration = null;

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

            return ['success' => $message];
        } catch (Exception $e) {
            Log::info($e->getMessage());

            return null;
        }
    }

    /**
     * Display the specified call log resource.
     *
     * @param  int  $id
     * @return AnonymousResourceCollection
     */
    public function show($id)
    {
        $calllog = CallLog::where('id', $id)->get();

        return CallLogResource::collection($calllog);
    }

    /**
     * Show the form for editing the specified call log.
     *
     * @param  int  $id
     * @return View
     */
    public function edit($id)
    {
        $calllog = CallLog::where([
            ['id', $id],
            ['school_id', Auth::user()->school_id],
        ])->first();

        return view('/reception/calllog/edit', ['calllog' => $calllog]);
    }

    /**
     * Update the specified call log in storage.
     *
     * @param  int  $id
     * @return array|null
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

            return ['success' => $message];
        } catch (Exception $e) {
            Log::info($e->getMessage());

            return null;
        }
    }

    /**
     * Remove the specified call log from storage.
     *
     * @param  int  $id
     * @return array|null
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

            \DB::commit();

            return ['message' => $message];
        } catch (Exception $e) {
            \DB::rollBack();
            Log::info($e->getMessage());

            return null;
        }
    }
}
