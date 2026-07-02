<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use App\Events\Notification\SingleNotificationEvent;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Traits\LogActivity;
use App\Helpers\SiteHelper;
use App\Models\PostDetail;
use App\Traits\Common;
use App\Models\Post;
use App\Models\User;
use Exception;
use Log;

/**
 * Class PostDetailController
 *
 * Handles user interactions with posts such as
 * like, dislike, save, and unsave actions.
 * Also triggers notifications and activity logs.
 *
 * @package App\Http\Controllers\Admin
 */
class PostDetailController extends Controller
{
    //
    use LogActivity;
    use Common;

    /**
     * Like or remove like from a post.
     *
     * Creates or updates post like details,
     * sends notification to post owner,
     * and logs the activity.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $post_id
     * @return array|null
     */
    public function like(Request $request, $post_id)
    {
        //
        try
        {
            $post = Post::where('id', $post_id)->first();
            $post_reply = PostDetail::where([
                ['user_id', Auth::id()],
                ['post_id', $post_id]
            ])->first();

            if ($post_reply != null)
            {
                $post_reply->like   = $request->like;
                $post_reply->status = 1;
                $post_reply->save();
            }
            else
            {
                $post_reply = new PostDetail;

                $post_reply->user_id = Auth::id();
                $post_reply->post_id = $post_id;
                $post_reply->like    = $request->like;
                $post_reply->status  = 1;
                $post_reply->save();
            }

            if ($post->entity_name == 'App\Models\User')
            {
                $user = User::where('id', $post->entity_id)->first();

                if ($request->like == 1)
                {
                    $details = trans('notification.page_comment_like_success_msg', [
                        'user'   => Auth::user()->FullName,
                        'entity' => 'Post'
                    ]);
                }
                else
                {
                    $details = trans('notification.page_comment_remove_like_success_msg', [
                        'user'   => Auth::user()->FullName,
                        'entity' => 'Post'
                    ]);
                }
            }
            elseif ($post->entity_name == 'App\Models\Page')
            {
                $page = ClassRoomPage::where('id', $post->entity_id)->first();
                $user = User::where('id', $page->created_by)->first();

                if ($request->like == 1)
                {
                    $details = trans('notification.page_comment_like_success_msg', [
                        'user'   => Auth::user()->FullName,
                        'entity' => 'Page'
                    ]);
                }
                else
                {
                    $details = trans('notification.page_comment_remove_like_success_msg', [
                        'user'   => Auth::user()->FullName,
                        'entity' => 'Page'
                    ]);
                }
            }

            if ($request->like == 1)
            {
                $message = trans('messages.like_success_msg', ['page' => 'post']);
            }
            else
            {
                $message = trans('messages.remove_like_success_msg', ['page' => 'post']);
            }

            if ($user->id != Auth::id())
            {
                $data = [];
                $data['user']    = $user;
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
        }
        catch (Exception $e)
        {
            Log::info($e->getMessage());
        }
    }

    /**
     * Dislike or remove dislike from a post.
     *
     * Creates or updates post dislike details,
     * sends notification to post owner,
     * and logs the activity.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $post_id
     * @return array|null
     */
    public function dislike(Request $request, $post_id)
    {
        //
        try
        {
            $post = Post::where('id', $post_id)->first();
            $post_reply = PostDetail::where([
                ['user_id', Auth::id()],
                ['post_id', $post_id]
            ])->first();

            if ($post_reply != null)
            {
                $post_reply->unlike = $request->dislike;
                $post_reply->status = 1;
                $post_reply->save();
            }
            else
            {
                $post_reply = new PostDetail;

                $post_reply->user_id = Auth::id();
                $post_reply->post_id = $post_id;
                $post_reply->unlike  = $request->dislike;
                $post_reply->status  = 1;
                $post_reply->save();
            }

            if ($post->entity_name == 'App\Models\User')
            {
                $user = User::where('id', $post->entity_id)->first();

                if ($request->dislike == 1)
                {
                    $details = trans('notification.page_comment_dislike_success_msg', [
                        'user'   => Auth::user()->FullName,
                        'entity' => 'Post'
                    ]);
                }
                else
                {
                    $details = trans('notification.page_comment_remove_dislike_success_msg', [
                        'user'   => Auth::user()->FullName,
                        'entity' => 'Post'
                    ]);
                }
            }
            elseif ($post->entity_name == 'App\Models\Page')
            {
                $page = ClassRoomPage::where('id', $post->entity_id)->first();
                $user = User::where('id', $page->created_by)->first();

                if ($request->dislike == 1)
                {
                    $details = trans('notification.page_comment_dislike_success_msg', [
                        'user'   => Auth::user()->FullName,
                        'entity' => 'Page'
                    ]);
                }
                else
                {
                    $details = trans('notification.page_comment_remove_dislike_success_msg', [
                        'user'   => Auth::user()->FullName,
                        'entity' => 'Page'
                    ]);
                }
            }

            if ($request->dislike == 1)
            {
                $message = trans('messages.unlike_success_msg', ['page' => 'post']);
            }
            else
            {
                $message = trans('messages.remove_unlike_success_msg', ['page' => 'post']);
            }

            if ($user->id != Auth::id())
            {
                $data = [];
                $data['user']    = $user;
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
        }
        catch (Exception $e)
        {
            Log::info($e->getMessage());
        }
    }

    /**
     * Save a post for later.
     *
     * Marks the post as saved for the user
     * and logs the activity.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $post_id
     * @return array|null
     */
    public function save(Request $request, $post_id)
    {
        //
        try
        {
            $post = Post::where('id', $post_id)->first();
            $post_reply = PostDetail::where([
                ['user_id', Auth::id()],
                ['post_id', $post_id]
            ])->first();

            if ($post_reply != null)
            {
                $post_reply->save   = $request->save;
                $post_reply->status = 1;
                $post_reply->save();
            }
            else
            {
                $post_reply = new PostDetail;

                $post_reply->user_id = Auth::id();
                $post_reply->post_id = $post_id;
                $post_reply->save    = $request->save;
                $post_reply->status  = 1;
                $post_reply->save();
            }

            $message = trans('messages.save_post_success_msg');

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
        }
        catch (Exception $e)
        {
            Log::info($e->getMessage());
        }
    }

    /**
     * Unsave a previously saved post.
     *
     * Removes the saved status for the post
     * and logs the activity.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $post_id
     * @return array|null
     */
    public function unsave(Request $request, $post_id)
    {
        //
        try
        {
            $post = Post::where('id', $post_id)->first();
            $post_reply = PostDetail::where([
                ['user_id', Auth::id()],
                ['post_id', $post_id]
            ])->first();

            if ($post_reply != null)
            {
                $post_reply->save   = $request->save;
                $post_reply->status = 1;
                $post_reply->save();
            }
            else
            {
                $post_reply = new PostDetail;

                $post_reply->user_id = Auth::id();
                $post_reply->post_id = $post_id;
                $post_reply->save    = $request->save;
                $post_reply->status  = 1;
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
        }
        catch (Exception $e)
        {
            Log::info($e->getMessage());
        }
    }
}
