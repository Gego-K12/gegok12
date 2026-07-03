<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Controller;
use App\Traits\Dashboard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class DashboardController
 *
 * Handles API requests related to the Teacher Dashboard.
 * Fetches and returns dashboard data for the authenticated teacher.
 */
class DashboardController extends Controller
{
    use Dashboard;

    /**
     * Display the teacher dashboard data.
     *
     * Retrieves dashboard details based on the authenticated teacher
     * and their associated school.
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $teacher_id = Auth::id();
        $school_id = Auth::user()->school_id;

        $dashboard = $this->teacherDashboard($school_id, $teacher_id);

        return response()->json([
            'success' => true,
            'message' => 'Teacher Dashboard Data',
            'data' => $dashboard['subject'],
        ], 200);
    }
}
