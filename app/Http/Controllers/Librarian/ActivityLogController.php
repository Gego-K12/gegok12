<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogReaderService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    public function __construct(protected ActivityLogReaderService $activityLogReader) {}

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('/library/activity_log/show', [
            'activitylog' => $this->activityLogReader->forUser(Auth::id()),
        ]);
    }
}
