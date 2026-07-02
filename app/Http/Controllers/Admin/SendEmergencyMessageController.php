<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use App\Events\EmergencyNotificationEvent;
use App\Http\Requests\EmergencyNotificationRequest;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Helpers\SiteHelper;
use App\Traits\LogActivity;
use App\Models\SendMail;
use App\Traits\Common;
use App\Models\User;
use Exception;
use Log;

/**
 * Class SendEmergencyMessageController
 *
 * Handles sending emergency notifications to users
 * within the authenticated school context.
 *
 * @package App\Http\Controllers\Admin
 */
class SendEmergencyMessageController extends Controller
{
    /**
     * Display the emergency message creation view.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.message.create');
    }

    /**
     * Store and dispatch an emergency notification.
     *
     * Validates request data, triggers emergency
     * notification event, and returns success response.
     *
     * @param EmergencyNotificationRequest $request
     * @return array
     */
    public function store(EmergencyNotificationRequest $request)
    {
        //
        try
        {
            $data=[];
            $data['message_type'] =$request->message_type;
            $data['message']=$request->message;
            $data['standard_id']=$request->standardLink_id;
            $datas=(object)$data;
            
            event (new EmergencyNotificationEvent (
                $datas,
                Auth::user()->school_id,
                Auth::user()->email,
                Auth::user()
            ));
                  
            $res['message'] = trans('messages.message_success_msg');
            return $res;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        }
    }
}
