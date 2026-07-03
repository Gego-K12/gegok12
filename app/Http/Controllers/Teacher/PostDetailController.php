<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Teacher;

use App\Events\Notification\SingleNotificationEvent;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostDetail;
use App\Models\User;
use App\Traits\Common;
use App\Traits\LogActivity;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class PostDetailController extends Controller
{
    use Common;
    //
    use LogActivity;

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function like(Request $request, $post_id)
    {
        //
        try {
            $post = Post::where('id', $post_id)->first();
            $post_reply = PostDetail::where([['user_id', Auth::id()], ['post_id', $post_id]])->first();

            if ($post_reply != null) {
                $post_reply->like = $request->like;
                $post_reply->status = 1;

                $post_reply->save();
            } else {
                $post_reply = new PostDetail;

                $post_reply->user_id = Auth::id();
                $post_reply->post_id = $post_id;
                $post_reply->like = $request->like;
                $post_reply->status = 1;

                $post_reply->save();
            }

            if ($post->entity_name == 'App\Models\User') {
                $user = User::where('id', $post->entity_id)->first();
                if ($request->like == 1) {
                    $details = trans('notification.page_comment_like_success_msg', ['user' => Auth::user()->FullName, 'entity' => 'Post']);
                } else {
                    $details = trans('notification.page_comment_remove_like_success_msg', ['user' => Auth::user()->FullName, 'entity' => 'Post']);
                }
            } elseif ($post->entity_name == 'App\Models\Page') {
                $page = ClassRoomPage::where('id', $post->entity_id)->first();
                $user = User::where('id', $page->created_by)->first();

                if ($request->like == 1) {
                    $details = trans('notification.page_comment_like_success_msg', ['user' => Auth::user()->FullName, 'entity' => 'Page']);
                } else {
                    $details = trans('notification.page_comment_remove_like_success_msg', ['user' => Auth::user()->FullName, 'entity' => 'Page']);
                }
            }

            if ($request->like == 1) {
                $message = trans('messages.like_success_msg', ['page' => 'post']);
            } else {
                $message = trans('messages.remove_like_success_msg', ['page' => 'post']);
            }

            if ($user->id != Auth::id()) {
                $data = [];

                $data['user'] = $user;
                $data['details'] = $details;

                event(new SingleNotificationEvent($data));
            }

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $post,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_LIKE_POST,
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
    public function dislike(Request $request, $post_id)
    {
        //
        try {
            $post = Post::where('id', $post_id)->first();
            $post_reply = PostDetail::where([['user_id', Auth::id()], ['post_id', $post_id]])->first();

            if ($post_reply != null) {
                $post_reply->unlike = $request->dislike;
                $post_reply->status = 1;

                $post_reply->save();
            } else {
                $post_reply = new PostDetail;

                $post_reply->user_id = Auth::id();
                $post_reply->post_id = $post_id;
                $post_reply->unlike = $request->dislike;
                $post_reply->status = 1;

                $post_reply->save();
            }

            if ($post->entity_name == 'App\Models\User') {
                $user = User::where('id', $post->entity_id)->first();
                if ($request->dislike == 1) {
                    $details = trans('notification.page_comment_dislike_success_msg', ['user' => Auth::user()->FullName, 'entity' => 'Post']);
                } else {
                    $details = trans('notification.page_comment_remove_dislike_success_msg', ['user' => Auth::user()->FullName, 'entity' => 'Post']);
                }
            } elseif ($post->entity_name == 'App\Models\Page') {
                $page = ClassRoomPage::where('id', $post->entity_id)->first();
                $user = User::where('id', $page->created_by)->first();

                if ($request->dislike == 1) {
                    $details = trans('notification.page_comment_dislike_success_msg', ['user' => Auth::user()->FullName, 'entity' => 'Page']);
                } else {
                    $details = trans('notification.page_comment_remove_dislike_success_msg', ['user' => Auth::user()->FullName, 'entity' => 'Page']);
                }
            }

            if ($request->dislike == 1) {
                $message = trans('messages.unlike_success_msg', ['page' => 'post']);
            } else {
                $message = trans('messages.remove_unlike_success_msg', ['page' => 'post']);
            }

            if ($user->id != Auth::id()) {
                $data = [];

                $data['user'] = $user;
                $data['details'] = $details;

                event(new SingleNotificationEvent($data));
            }

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $post,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_UNLIKE_POST,
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
    public function save(Request $request, $post_id)
    {
        //
        try {
            $post = Post::where('id', $post_id)->first();
            $post_reply = PostDetail::where([['user_id', Auth::id()], ['post_id', $post_id]])->first();

            if ($post_reply != null) {
                $post_reply->save = $request->save;
                $post_reply->status = 1;

                $post_reply->save();
            } else {
                $post_reply = new PostDetail;

                $post_reply->user_id = Auth::id();
                $post_reply->post_id = $post_id;
                $post_reply->save = $request->save;
                $post_reply->status = 1;

                $post_reply->save();
            }

            $message = trans('messages.save_success_msg');

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $post,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_SAVE_POST,
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
    public function unsave(Request $request, $post_id)
    {
        //
        try {
            $post = Post::where('id', $post_id)->first();
            $post_reply = PostDetail::where([['user_id', Auth::id()], ['post_id', $post_id]])->first();

            if ($post_reply != null) {
                $post_reply->save = $request->save;
                $post_reply->status = 1;

                $post_reply->save();
            } else {
                $post_reply = new PostDetail;

                $post_reply->user_id = Auth::id();
                $post_reply->post_id = $post_id;
                $post_reply->save = $request->save;
                $post_reply->status = 1;

                $post_reply->save();
            }

            $message = trans('messages.unsave_success_msg');

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $post,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_UNSAVE_POST,
                $message
            );

            $res['success'] = $message;

            return $res;
        } catch (Exception $e) {
        }
    }
}
