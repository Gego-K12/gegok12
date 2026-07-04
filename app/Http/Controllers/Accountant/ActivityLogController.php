<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogReaderService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Class ActivityLogController
 *
 * Handles viewing of activity logs for accountant users.
 *
 * Responsibilities:
 * - Retrieve activity logs created by the authenticated user
 * - Display logs in a paginated view
 */
class ActivityLogController extends Controller
{
    public function __construct(protected ActivityLogReaderService $activityLogReader) {}

    /**
     * Display a paginated list of activity logs for the authenticated user.
     *
     * @return View
     */
    public function index()
    {
        return view('/accountant/activity_log/show', [
            'activitylog' => $this->activityLogReader->forUser(Auth::id()),
        ]);
    }
}
