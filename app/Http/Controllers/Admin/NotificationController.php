<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\NotificationReaderService;
use App\Services\NotificationWriterService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Log;

/**
 * Class NotificationController
 *
 * Handles notification listing, reading, and summary display
 * for authenticated admin users.
 */
class NotificationController extends Controller
{
    public function __construct(
        protected NotificationReaderService $notificationReader,
        protected NotificationWriterService $notificationWriter
    ) {}

    /**
     * Fetch all read and unread notifications for the logged-in user.
     *
     * Returns notifications grouped as read and unread using
     * API resource transformation.
     *
     * @return array|null
     */
    public function indexList()
    {
        try {
            return $this->notificationReader->groupedByReadStatus(Auth::id());
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Display notification index page.
     *
     * @return View
     */
    public function index()
    {
        //
        return view('/admin/notification/index');
    }

    /**
     * Mark notification(s) as read.
     *
     * Marks a single notification or all notifications
     * as read based on request input.
     *
     * @return array|null
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
     * Fetch latest unread notifications summary.
     *
     * Returns unread notification count and the latest
     * five unread notifications with formatted data.
     *
     * @return array|null
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
