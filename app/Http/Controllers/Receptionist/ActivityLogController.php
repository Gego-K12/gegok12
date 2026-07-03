<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $activitylog = ActivityLog::where('causer_id', Auth::id())->orderby('id', 'desc')->paginate(10);

        return view('/reception/activity_log/show', ['activitylog' => $activitylog]);
    }
}
