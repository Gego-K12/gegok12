<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\NotificationReaderService;
use App\Services\NotificationWriterService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Log;

class NotificationController extends Controller
{
    public function __construct(
        protected NotificationReaderService $notificationReader,
        protected NotificationWriterService $notificationWriter
    ) {}

    /**
     * Fetches the notifications.
     *
     * @return Response
     */
    public function indexList()
    {
        try {
            return $this->notificationReader->groupedByReadStatus(Auth::id());
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    public function index()
    {
        //
        return view('/student/notification/index');
    }

    /**
     * Mark the notification as read.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        try {
            if (Auth::user()) {
                $message = $this->notificationWriter->markRead(Auth::id(), $request->notification_id);

                return ['success' => $message];
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Fetches the notifications.
     *
     * @return Response
     */
    public function showList()
    {
        try {
            return $this->notificationReader->bellDropdownSummary(Auth::user());
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
