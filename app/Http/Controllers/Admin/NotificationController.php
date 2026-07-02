<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use App\Http\Resources\Notification as NotificationResource;
use App\Notifications\NewMessageNotification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Notification;
use Exception;
use Log;

/**
 * Class NotificationController
 *
 * Handles notification listing, reading, and summary display
 * for authenticated admin users.
 *
 * @package App\Http\Controllers\Admin
 */
class NotificationController extends Controller
{
    //

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
        try
        {
            $array = [];

            $unreadNotifications = \DB::table('notifications')
                ->where('notifiable_id', Auth::id())
                ->whereNull('read_at')
                ->get();

            $unreadNotifications = NotificationResource::collection($unreadNotifications);

            $readNotifications = \DB::table('notifications')
                ->where('notifiable_id', Auth::id())
                ->whereNotNull('read_at')
                ->orderBy('read_at', 'ASC')
                ->get();

            $readNotifications = NotificationResource::collection($readNotifications);

            $array['read_list']   = $readNotifications;
            $array['unread_list'] = $unreadNotifications;

            return $array;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        }
    }

    /**
     * Display notification index page.
     *
     * @return \Illuminate\View\View
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
     * @param \Illuminate\Http\Request $request
     * @return array|null
     */
    public function store(Request $request)
    {
        try
        {
            if(Auth::user())
            {
                if($request->notification_id != 'all')
                {
                    \DB::table('notifications')
                        ->where('id', $request->notification_id)
                        ->where('notifiable_id', Auth::id())
                        ->whereNull('read_at')
                        ->update(['read_at' => Carbon::now()]);

                    $res['success'] = "Notification Read Successfully";
                    return $res;
                }
                else
                {
                    \DB::table('notifications')
                        ->where('notifiable_id', Auth::id())
                        ->whereNull('read_at')
                        ->update(['read_at' => Carbon::now()]);

                    $res['success'] = "All Notifications Read Successfully";
                    return $res;
                }
            }
        }
        catch(Exception $e)
        {
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
        try
        {
            $array = [];

            if(Auth::user())
            {
                $array['count'] = count(Auth::user()->unreadNotifications);
                $notifications  = Auth::user()->unreadNotifications->take(5);

                $i = 0;
                foreach ($notifications as $notification)
                {
                    $val  = '';
                    $type = null;

                    if ((count($notification->data) > 0) && (isset($notification->data['data'])))
                    {
                        if (count((array)$notification->data['data']) > 1)
                        {
                            $val  = $notification->data['data']['data'];
                            $type = $notification->data['data']['type'];
                        }
                        else
                        {
                            $val  = $notification->data['data'];
                            $type = null;
                        }
                    }

                    $array['list'][$i]['notification_id'] = $notification['id'];
                    $array['list'][$i]['data']            = $val;
                    $array['list'][$i]['type']            = $type;
                    $array['list'][$i]['date']            = $notification->created_at->diffForHumans();
                    $i++;
                }
            }

            return $array;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        }
    }
}
