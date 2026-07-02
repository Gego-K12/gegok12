<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use App\Http\Requests\LeaveTypeUpdateRequest;
use App\Http\Requests\LeaveTypeAddRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\SiteHelper;
use App\Traits\LogActivity;
use App\Models\LeaveType;
use League\Csv\Writer;
use App\Traits\Common;
use Carbon\Carbon;
use Exception;

/**
 * Class LeaveTypesController
 *
 * Manages leave type configuration for the admin panel.
 *
 * Responsibilities:
 * - List active leave types
 * - Create new leave types
 * - Edit existing leave types
 * - Update leave type limits
 * - Delete leave types
 * - Log all leave-type related activities
 *
 * @package App\Http\Controllers\Admin
 */
class LeaveTypesController extends Controller
{
    use LogActivity;
    use Common;

    /**
     * Display a list of active leave types for the current academic year.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        //
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);

        $leavetypes = LeaveType::where([
            ['school_id', $school_id],
            ['academic_year_id', $academic_year->id],
            ['status', 1]
        ])->get();

        return view('admin/leavetypes/index', [
            'leavetypes' => $leavetypes
        ]);
    }

    /**
     * Show the form for creating a new leave type.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        //
        return view('admin/leavetypes/create');
    }

    /**
     * Store a newly created leave type.
     *
     * @param  \App\Http\Requests\LeaveTypeAddRequest  $request
     * @return \Illuminate\Http\RedirectResponse|null
     */
    public function store(LeaveTypeAddRequest $request)
    {
        //
        try
        {
            $school_id = Auth::user()->school_id;
            $academic_year = SiteHelper::getAcademicYear($school_id);

            $leavetype = new LeaveType;

            $leavetype->school_id        = $school_id;
            $leavetype->academic_year_id = $academic_year->id;
            $leavetype->name             = $request->name;
            $leavetype->max_no_of_days   = $request->max_no_of_days;
            $leavetype->status           = 1;

            $leavetype->save();

            $message = trans('messages.add_success_msg', ['module' => 'LeaveType']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $leavetype,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_ADD_LEAVETYPE,
                $message
            );

            return redirect('/admin/leavetypes')->with('successmessage', $message);
        }
        catch (Exception $e)
        {
        }
    }

    /**
     * Show the form for editing the specified leave type.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        //
        $leavetype = LeaveType::where('id', $id)->first();

        return view('admin/leavetypes/edit', [
            'leavetype' => $leavetype
        ]);
    }

    /**
     * Update the specified leave type.
     *
     * @param  \App\Http\Requests\LeaveTypeUpdateRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse|null
     */
    public function update(LeaveTypeUpdateRequest $request, $id)
    {
        //
        try
        {
            $leavetype = LeaveType::where('id', $id)->first();

            $leavetype->name           = $request->name;
            $leavetype->max_no_of_days = $request->max_no_of_days;

            $leavetype->save();

            $message = trans('messages.update_success_msg', ['module' => 'LeaveType']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $leavetype,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_ADD_LEAVETYPE,
                $message
            );

            return redirect('/admin/leavetypes')->with('successmessage', $message);
        }
        catch (Exception $e)
        {
        }
    }

    /**
     * Remove the specified leave type.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse|null
     */
    public function destroy($id)
    {
        //
        try
        {
            $leavetype = LeaveType::where('id', $id)->first();
            $leavetype->delete();

            $message = trans('messages.delete_success_msg', ['module' => 'LeaveType']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $leavetype,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_DELETE_LEAVETYPE,
                $message
            );

            return redirect()->back()->with('successmessage', $message);
        }
        catch (Exception $e)
        {
        }
    }
}
