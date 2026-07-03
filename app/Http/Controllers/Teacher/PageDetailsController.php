<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Teacher;

use App\Events\Notification\SingleNotificationEvent;
use App\Http\Controllers\Controller;
use App\Models\ClassRoomPage;
use App\Models\ClassRoomPageDetail;
use App\Models\User;
use App\Traits\Common;
use App\Traits\LogActivity;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class PageDetailsController extends Controller
{
    use Common;
    use LogActivity;

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function follow(Request $request, $page_id)
    {
        //
        try {
            $page = ClassRoomPage::where('id', $page_id)->first();

            $page_detail = ClassRoomPageDetail::where([['user_id', Auth::id()], ['page_id', $page_id]])->first();

            if ($page_detail != null) {
                $page_detail->is_following = $request->is_following;
                $page_detail->status = 1;

                $page_detail->save();
            } else {
                $page_detail = new ClassRoomPageDetail;

                $page_detail->user_id = Auth::id();
                $page_detail->page_id = $page_id;
                $page_detail->is_following = $request->is_following;
                $page_detail->status = 1;

                $page_detail->save();
            }

            $user = User::where('id', $page->created_by)->first();

            if ($request->is_following == 1) {
                $message = trans('messages.follow_success_msg', ['page' => $page->page_name]);

                $details = trans('notification.page_follow_success_msg', ['user' => Auth::user()->FullName, 'page' => $page->page_name]);
            } else {
                $message = trans('messages.unfollow_success_msg', ['page' => $page->page_name]);

                $details = trans('notification.page_unfollow_success_msg', ['user' => Auth::user()->FullName, 'page' => $page->page_name]);
            }

            if ($user->id != Auth::id()) {
                $data = [];

                $data['user'] = $user;
                $data['details'] = $details;

                event(new SingleNotificationEvent($data));
            }

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $page,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_FOLLOW_PAGE,
                $message
            );

            $res['success'] = $message;

            return $res;
        } catch (Exception $e) {
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function like(Request $request, $page_id)
    {
        //
        try {
            $page = ClassRoomPage::where('id', $page_id)->first();
            $page_detail = ClassRoomPageDetail::where([['user_id', Auth::id()], ['page_id', $page_id]])->first();

            if ($page_detail != null) {
                $page_detail->like = $request->like;
                $page_detail->status = 1;

                $page_detail->save();
            } else {
                $page_detail = new ClassRoomPageDetail;

                $page_detail->user_id = Auth::id();
                $page_detail->page_id = $page_id;
                $page_detail->like = $request->like;
                $page_detail->status = 1;

                $page_detail->save();
            }

            $user = User::where('id', $page->created_by)->first();
            if ($request->like == 1) {
                $message = trans('messages.like_success_msg', ['page' => $page->page_name]);

                $details = trans('notification.page_like_success_msg', ['user' => Auth::user()->FullName, 'page' => $page->page_name]);
            } else {
                $message = trans('messages.remove_like_success_msg', ['page' => $page->page_name]);

                $details = trans('notification.page_remove_like_success_msg', ['user' => Auth::user()->FullName, 'page' => $page->page_name]);
            }

            if ($user->id != Auth::id()) {
                $data = [];

                $data['user'] = $user;
                $data['details'] = $details;

                event(new SingleNotificationEvent($data));
            }

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $page,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_LIKE_PAGE,
                $message
            );

            $res['success'] = $message;

            return $res;
        } catch (Exception $e) {
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function dislike(Request $request, $page_id)
    {
        //
        try {
            $page = ClassRoomPage::where('id', $page_id)->first();
            $page_detail = ClassRoomPageDetail::where([['user_id', Auth::id()], ['page_id', $page_id]])->first();

            if ($page_detail != null) {
                $page_detail->dislike = $request->dislike;
                $page_detail->status = 1;

                $page_detail->save();
            } else {
                $page_detail = new ClassRoomPageDetail;

                $page_detail->user_id = Auth::id();
                $page_detail->page_id = $page_id;
                $page_detail->dislike = $request->dislike;
                $page_detail->status = 1;

                $page_detail->save();
            }

            $user = User::where('id', $page->created_by)->first();

            if ($request->dislike == 1) {
                $message = trans('messages.unlike_success_msg', ['page' => $page->page_name]);

                $details = trans('notification.page_unlike_success_msg', ['user' => Auth::user()->FullName, 'page' => $page->page_name]);
            } else {
                $message = trans('messages.remove_unlike_success_msg', ['page' => $page->page_name]);

                $details = trans('notification.page_remove_unlike_success_msg', ['user' => Auth::user()->FullName, 'page' => $page->page_name]);
            }

            if ($user->id != Auth::id()) {
                $data = [];

                $data['user'] = $user;
                $data['details'] = $details;

                event(new SingleNotificationEvent($data));
            }

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $page,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_UNLIKE_PAGE,
                $message
            );

            $res['success'] = $message;

            return $res;
        } catch (Exception $e) {
        }
    }
}
