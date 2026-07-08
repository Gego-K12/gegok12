<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\Student\Task as TaskResource;
use App\Models\User;
use App\Services\DashboardReaderService;
use App\Traits\Dashboard;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    use Dashboard;

    public function __construct(protected DashboardReaderService $dashboardReader) {}

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        //
        $school_id = Auth::user()->school_id;
        $student_id = Auth::id();
        $student = User::where('id', $student_id)->first();
        $standardLink_id = $student->studentAcademicLatest->standardLink_id;

        $exam_date = null;
        if ($request->exam_date != null) {
            $exam_date = date('Y-m-d H:i:s', strtotime($request->exam_date));
        }

        $dashboard = $this->studentDashboard($school_id, $student, $standardLink_id, $request->subject, $request->exam, $request->mark, $exam_date);

        return view('/student/dashboard/dashboard', ['dashboard' => $dashboard]);
    }

    public function list(Request $request, $task_flag)
    {
        $tasks = $this->dashboardReader->taskWidgetList(Auth::user()->school_id, Auth::id(), $task_flag, $request->q);

        return TaskResource::collection($tasks);
    }

    public function listCount()
    {
        return $this->dashboardReader->taskWidgetCounts(Auth::user()->school_id, Auth::id());
    }
}
