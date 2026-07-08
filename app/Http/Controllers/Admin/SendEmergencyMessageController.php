<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use App\Events\EmergencyNotificationEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmergencyNotificationRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Log;

/**
 * Class SendEmergencyMessageController
 *
 * Handles sending emergency notifications to users
 * within the authenticated school context.
 */
class SendEmergencyMessageController extends Controller
{
    /**
     * Display the emergency message creation view.
     *
     * @return Response
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
     * @return array
     */
    public function store(EmergencyNotificationRequest $request)
    {
        //
        try {
            $data = [];
            $data['message_type'] = $request->message_type;
            $data['message'] = $request->message;
            $data['standard_id'] = $request->standardLink_id;
            $datas = (object) $data;

            event(new EmergencyNotificationEvent(
                $datas,
                Auth::user()->school_id,
                Auth::user()->email,
                Auth::user()
            ));

            $res['message'] = trans('messages.message_success_msg');

            return $res;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
