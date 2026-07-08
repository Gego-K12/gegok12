<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Api;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\Attendance as AttendanceResource;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($student_id)
    {
        //
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
        $student = User::where('id', $student_id)->first();
        $attendances = Attendance::where([['school_id', Auth::user()->school_id], ['academic_year_id', $academic_year->id], ['user_id', $student_id]])->get();
        $attendance = AttendanceResource::collection($attendances->where('status', 0));

        $total = $attendances->count();
        $present = $attendances->where('status', 1)->count();
        if ($total > 0) {
            $percentage = number_format(($present / $total) * 100, 2);
        } else {
            $percentage = 0;
        }

        return response()->json([
            'success' => true,
            'message' => 'Attendance',
            'attendancePercentage' => $percentage.'%',
            'data' => $attendance,
        ], 200);
    }
}
