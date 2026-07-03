<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use App\Events\Notification\SingleNotificationEvent;
use App\Http\Controllers\Controller;
use App\Models\ClassRoomPage;
use App\Models\Post;
use App\Models\PostCommentDetail;
use App\Models\User;
use App\Traits\Common;
use App\Traits\LogActivity;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;

/**
 * Class PostCommentDetailsController
 *
 * Handles like and dislike actions on post comments,
 * triggers notifications, and logs activities.
 */
class PostCommentDetailsController extends Controller
{
    use Common;
    //
    use LogActivity;

    /**
     * Like or remove like from a comment.
     *
     * Creates or updates the comment like record,
     * sends notification to the content owner,
     * and logs the activity.
     *
     * @param  int  $comment_id
     * @return array|null
     */
    public function like(Request $request, $comment_id)
    {
        //
        try {
            $post_comment_detail = PostCommentDetail::where([
                ['user_id', Auth::id()],
                ['post_comment_id', $comment_id],
            ])->first();

            if ($post_comment_detail != null) {
                $post_comment_detail->like = $request->like;
                $post_comment_detail->status = 1;
                $post_comment_detail->save();
            } else {
                $post_comment_detail = new PostCommentDetail;

                $post_comment_detail->user_id = Auth::id();
                $post_comment_detail->post_comment_id = $comment_id;
                $post_comment_detail->like = $request->like;
                $post_comment_detail->status = 1;
                $post_comment_detail->save();
            }

            if ($post_comment_detail->postComment->post->entity_name == 'App\Models\User') {
                $user = User::where(
                    'id',
                    $post_comment_detail->postComment->post->entity_id
                )->first();

                if ($request->like == 1) {
                    $details = trans('notification.page_comment_like_success_msg', [
                        'user' => Auth::user()->FullName,
                        'entity' => 'Post',
                    ]);
                } else {
                    $details = trans('notification.page_comment_remove_like_success_msg', [
                        'user' => Auth::user()->FullName,
                        'entity' => 'Post',
                    ]);
                }
            } elseif ($post_comment_detail->postComment->post->entity_name == 'App\Models\Page') {
                $page = ClassRoomPage::where(
                    'id',
                    $post_comment_detail->postComment->post->entity_id
                )->first();

                $user = User::where('id', $page->created_by)->first();

                if ($request->like == 1) {
                    $details = trans('notification.page_comment_like_success_msg', [
                        'user' => Auth::user()->FullName,
                        'entity' => 'Page',
                    ]);
                } else {
                    $details = trans('notification.page_comment_remove_like_success_msg', [
                        'user' => Auth::user()->FullName,
                        'entity' => 'Page',
                    ]);
                }
            }

            if ($request->like == 1) {
                $message = trans('messages.like_success_msg', ['page' => 'comment']);
            } else {
                $message = trans('messages.remove_like_success_msg', ['page' => 'comment']);
            }

            if ($user->id != Auth::id()) {
                $data = [];
                $data['user'] = $user;
                $data['details'] = $details;

                event(new SingleNotificationEvent($data));
            }

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $post_comment_detail,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_LIKE_COMMENT,
                $message
            );

            $res['success'] = $message;

            return $res;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Dislike or remove dislike from a comment.
     *
     * Creates or updates the comment dislike record,
     * sends notification to the content owner,
     * and logs the activity.
     *
     * @param  int  $comment_id
     * @return array|null
     */
    public function dislike(Request $request, $comment_id)
    {
        //
        try {
            $post_comment_detail = PostCommentDetail::where([
                ['user_id', Auth::id()],
                ['post_comment_id', $comment_id],
            ])->first();

            if ($post_comment_detail != null) {
                $post_comment_detail->unlike = $request->dislike;
                $post_comment_detail->status = 1;
                $post_comment_detail->save();
            } else {
                $post_comment_detail = new PostCommentDetail;

                $post_comment_detail->user_id = Auth::id();
                $post_comment_detail->post_comment_id = $comment_id;
                $post_comment_detail->unlike = $request->dislike;
                $post_comment_detail->status = 1;
                $post_comment_detail->save();
            }

            if ($post_comment_detail->postComment->post->entity_name == 'App\Models\User') {
                $user = User::where(
                    'id',
                    $post_comment_detail->postComment->post->entity_id
                )->first();

                if ($request->dislike == 1) {
                    $details = trans('notification.page_comment_dislike_success_msg', [
                        'user' => Auth::user()->FullName,
                        'entity' => 'Post',
                    ]);
                } else {
                    $details = trans('notification.page_comment_remove_dislike_success_msg', [
                        'user' => Auth::user()->FullName,
                        'entity' => 'Post',
                    ]);
                }
            } elseif ($post_comment_detail->postComment->post->entity_name == 'App\Models\Page') {
                $page = ClassRoomPage::where(
                    'id',
                    $post_comment_detail->postComment->post->entity_id
                )->first();

                $user = User::where('id', $page->created_by)->first();

                if ($request->dislike == 1) {
                    $details = trans('notification.page_comment_dislike_success_msg', [
                        'user' => Auth::user()->FullName,
                        'entity' => 'Page',
                    ]);
                } else {
                    $details = trans('notification.page_comment_remove_dislike_success_msg', [
                        'user' => Auth::user()->FullName,
                        'entity' => 'Page',
                    ]);
                }
            }

            if ($request->dislike == 1) {
                $message = trans('messages.unlike_success_msg', ['page' => 'comment']);
            } else {
                $message = trans('messages.remove_unlike_success_msg', ['page' => 'comment']);
            }

            if ($user->id != Auth::id()) {
                $data = [];
                $data['user'] = $user;
                $data['details'] = $details;

                event(new SingleNotificationEvent($data));
            }

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $post_comment_detail,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_UNLIKE_COMMENT,
                $message
            );

            $res['success'] = $message;

            return $res;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
