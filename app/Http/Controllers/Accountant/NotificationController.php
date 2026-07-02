<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers\Accountant;

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
 * Notification controller for accountant area.
 *
 * Provides endpoints to list, view and mark notifications as read for the
 * authenticated accountant user.
 */
class NotificationController extends Controller
{

    /**
     * Fetches the notifications.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexList()
    {
        try
        {
            $array = [];

            $unreadNotifications = \DB::table('notifications')->where('notifiable_id',Auth::id())->whereNull('read_at')->get();
            
            $unreadNotifications = NotificationResource::collection($unreadNotifications);

            $readNotifications = \DB::table('notifications')->where('notifiable_id',Auth::id())->whereNotNull('read_at')->orderBy('read_at','ASC')->get();
            
            $readNotifications = NotificationResource::collection($readNotifications);

            $array['read_list']     = $readNotifications;
            $array['unread_list']   = $unreadNotifications;

            return $array;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        }
    }

    /**
     * Render the notification index view.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('/accountant/notification/index');
    }

    /**
     * Mark the notification as read.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try
        {
            if(Auth::user())
            {
                if($request->notification_id!='all')
                {
                	\DB::table('notifications')->where('id', $request->notification_id)->where('notifiable_id',Auth::id())->whereNull('read_at')->update(['read_at' => Carbon::now()]);

      				$res['success'] = "Notification Read Successfully";
        			return $res;
                }
                else
                {
                    \DB::table('notifications')->where('notifiable_id',Auth::id())->whereNull('read_at')->update(['read_at' => Carbon::now()]);

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
     * Fetches the notifications.
     *
     * @return \Illuminate\Http\Response
     */
    public function showList()
    {
        try
        {
        	$array=[];
            if(Auth::user())
            {
              	$array['count']=count(Auth::user()->unreadNotifications);
              	$notifications= Auth::user()->unreadNotifications->take(5);
              	$i=0;
             	foreach ($notifications as $notification)
                {
                    $val='';
                    if((count($notification->data)>0) && (isset($notification->data['data'])))
                    {
                        if(count($notification->data['data']) > 1)
                        {
                            $val = $notification->data['data']['data'];
                            $type = $notification->data['data']['type'];
                        }
                        else
                        {
                            $val = $notification->data['data'];
                            $type = null;
                        }
                    }
                    $array['list'][$i]['notification_id']=$notification['id'];                  
                    $array['list'][$i]['data']=$val;
                    $array['list'][$i]['date']=$notification->created_at->diffForHumans();
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