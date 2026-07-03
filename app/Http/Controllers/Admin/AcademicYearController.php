<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AcademicYearAddRequest;
use App\Http\Requests\AcademicYearUpdateRequest;
use App\Http\Resources\AcademicYear as AcademicYearResource;
use App\Models\AcademicYear;
use App\Traits\Common;
use App\Traits\LogActivity;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Log;

/**
 * Class AcademicYearController
 *
 * Controller for managing academic years: listing, creating,
 * updating and deleting academic year records for the school.
 */
class AcademicYearController extends Controller
{
    use Common;
    use LogActivity;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function list()
    {
        //
        $academicYears = AcademicYear::where('school_id', Auth::user()->school_id)->orderBy('name', 'ASC')->get();
        $academic_year = AcademicYear::where([['status', 1], ['school_id', Auth::user()->school_id]])->first();

        $array = [];

        $array['academic_years'] = AcademicYearResource::collection($academicYears);
        $array['academic_year_id'] = $academic_year->id;

        return $array;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        $academicYears = AcademicYear::where('school_id', Auth::user()->school_id)->get();

        return view('/admin/school/academics/index', ['school' => $school, 'academicYears' => $academicYears]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function createList()
    {
        //
        $array = [];

        $array['statuslist'] = SiteHelper::getAcademicYearStatusList();

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
        return view('/admin/school/academics/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(AcademicYearAddRequest $request)
    {
        //
        try {
            $curr_year = date('Y', strtotime($request->start_date));
            $next_year = date('Y', strtotime($request->end_date));
            $academic = new AcademicYear;

            $academic->school_id = Auth::user()->school_id;
            // $academic->name         = $request->name;
            $academic->name = $curr_year.'-'.$next_year;
            $academic->description = $request->description;
            $academic->start_date = date('Y-m-d', strtotime($request->start_date));
            $academic->end_date = date('Y-m-d', strtotime($request->end_date));
            if ($request->status == 'current') {
                $academic->status = 1;
            } elseif ($request->status == 'new') {
                $academic->status = 2;
            } elseif ($request->status == 'old') {
                $academic->status = 0;
            }

            $academic->save();

            $message = trans('messages.add_success_msg', ['module' => 'Academic Year']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $academic,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_ADD_ACADEMIC_YEAR,
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
    public function editList($id)
    {
        //
        $academicYear = AcademicYear::where([['id', $id], ['school_id', Auth::user()->school_id]])->first();

        $array = [];

        $array['description'] = $academicYear->description;
        $array['start_date'] = date('Y-m-d', strtotime($academicYear->start_date));
        $array['end_date'] = date('Y-m-d', strtotime($academicYear->end_date));
        if ($academicYear->status == 1) {
            $array['status'] = 'current';
        } elseif ($academicYear->status == 2) {
            $array['status'] = 'new';
        } elseif ($academicYear->status == 0) {
            $array['status'] = 'old';
        }
        $array['statuslist'] = SiteHelper::getAcademicYearStatusList();

        return $array;
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
        $academic_year = AcademicYear::where([['id', $id], ['school_id', Auth::user()->school_id]])->first();

        if (Gate::allows('academic', $academic_year)) {
            return view('/admin/school/academics/show', ['academic_year' => $academic_year]);
        } else {
            abort(403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
        $academicYear = AcademicYear::where([['id', $id], ['school_id', Auth::user()->school_id]])->first();

        if (Gate::allows('academic', $academicYear)) {
            return view('/admin/school/academics/edit', ['academicYear' => $academicYear]);
        } else {
            abort(403);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(AcademicYearUpdateRequest $request, $id)
    {
        //
        try {
            $academic = AcademicYear::where('id', $id)->first();

            // $academic->name         = $request->name;
            $academic->description = $request->description;
            $academic->start_date = date('Y-m-d', strtotime($request->start_date));
            $academic->end_date = date('Y-m-d', strtotime($request->end_date));
            if ($request->status == 'current') {
                $academic->status = 1;
            } elseif ($request->status == 'new') {
                $academic->status = 2;
            } elseif ($request->status == 'old') {
                $academic->status = 0;
            }

            $academic->save();

            $message = trans('messages.update_success_msg', ['module' => 'Academic Year']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $academic,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_EDIT_ACADEMIC_YEAR,
                $message
            );

            $res['success'] = $message;

            return $res;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function updateStatus(Request $request)
    {
        //
        try {
            $prev_current_academic_year = AcademicYear::where([['status', 1], ['school_id', Auth::user()->school_id]])->first();

            $prev_current_academic_year->status = 0;

            $prev_current_academic_year->save();

            $new_current_academic_year = AcademicYear::where('id', $request->academic_year_id)->first();

            $new_current_academic_year->status = 1;

            $new_current_academic_year->save();

            Cache::flush(); // reset cache to change all details of current academic year

            $message = trans('messages.update_status_success_msg', ['module' => 'Academic Year']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $new_current_academic_year,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_UPDATE_STATUS_ACADEMIC_YEAR,
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
            $academic = AcademicYear::where('id', $id)->first();
            if (Gate::allows('academic', $academic)) {
                $academic->delete();

                $message = trans('messages.delete_success_msg', ['module' => 'Academic Year']);

                $ip = $this->getRequestIP();
                $this->doActivityLog(
                    $academic,
                    Auth::user(),
                    ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                    LOGNAME_DELETE_ACADEMIC_YEAR,
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
