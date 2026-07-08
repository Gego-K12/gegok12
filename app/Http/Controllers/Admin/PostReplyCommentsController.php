<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use App\Events\Notification\SingleNotificationEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Classwall\PostCommentEditRequest;
use App\Http\Resources\Classwall\PostReplyComment as PostReplyCommentResource;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\User;
use App\Traits\Common;
use App\Traits\LogActivity;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

/**
 * Class PostReplyCommentsController
 *
 * Manages reply comments for post comments including
 * listing replies, adding, editing, deleting reply comments,
 * sending notifications, and logging activities.
 */
class PostReplyCommentsController extends Controller
{
    use Common;
    //
    use LogActivity;

    /**
     * List all reply comments for a post comment.
     *
     * @param  int  $post_comment_id
     * @return AnonymousResourceCollection
     */
    public function list(Request $request, $post_comment_id)
    {
        //
        $reply_comments = PostComment::where([
            ['entity_id', $post_comment_id],
            ['entity_name', 'App\Models\PostComment'],
        ])->get();

        $reply_comments = PostReplyCommentResource::collection($reply_comments);

        return $reply_comments;
    }

    /**
     * Add a reply to a post comment.
     *
     * Handles attachment upload, sends notifications
     * to post owner and original comment owner,
     * and logs activity.
     *
     * @param  int  $post_comment_id
     * @return array|null
     */
    public function addComment(Request $request, $post_comment_id)
    {
        //
        try {
            $post_comment = PostComment::with('post')
                ->where('id', $post_comment_id)
                ->first();

            $post_reply = new PostComment;

            $post_reply->user_id = Auth::id();
            $post_reply->entity_id = $request->entity_id;
            $post_reply->entity_name = 'App\Models\PostComment';
            $post_reply->comments = $request->reply_comments;

            $file = $request->reply_attachment;
            if ($file != null) {
                $path = $this->uploadFile(
                    Auth::user()->school->slug.
                    '/posts/'.$post_comment_id.
                    '/comments'.$post_comment_id.'/reply',
                    $file
                );
            } else {
                $path = null;
            }

            $post_reply->attachment_file = $path;
            $post_reply->status = 1;
            $post_reply->save();

            $message = trans('messages.add_success_msg', [
                'module' => 'Post Reply Comment',
            ]);

            if ($post_comment->post->entity_name == 'App\Models\User') {
                $user = User::where('id', $post_comment->post->entity_id)->first();
                $details = trans('notification.post_comment_add_success_msg', [
                    'user' => Auth::user()->FullName,
                    'entity' => 'Post',
                ]);
            } elseif ($post_comment->post->entity_name == 'App\Models\Page') {
                $user = User::where('id', $post_comment->post->created_by)->first();
                $details = trans('notification.post_comment_add_success_msg', [
                    'user' => Auth::user()->FullName,
                    'entity' => 'Page',
                ]);
            }

            if ($user->id != Auth::id()) {
                $data = [];
                $data['user'] = $user;
                $data['details'] = $details;

                event(new SingleNotificationEvent($data));
            }

            $comment_user = User::where('id', $post_comment->user_id)->first();
            if ($comment_user->id != Auth::id()) {
                $array = [];
                $array['user'] = $comment_user;
                $array['details'] = trans(
                    'notification.post_reply_comment_add_success_msg',
                    ['user' => Auth::user()->FullName]
                );

                event(new SingleNotificationEvent($array));
            }

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $post_reply,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_ADD_REPLY_COMMENT,
                $message
            );

            $res['success'] = $message;

            return $res;
        } catch (Exception $e) {
        }
    }

    /**
     * Edit a reply comment.
     *
     * Updates reply comment content and attachment,
     * sends notifications, and logs activity.
     *
     * @param  int  $post_comment_id
     * @return array|null
     */
    public function editComment(PostCommentEditRequest $request, $post_comment_id)
    {
        //
        try {
            $post_reply = PostComment::where('id', $post_comment_id)->first();
            $post_comment = PostComment::where('id', $post_reply->entity_id)->first();

            $post_reply->comments = $request->reply_comments;
            $file = $request->reply_attachment;

            if ($file != null) {
                $path = $this->uploadFile(
                    Auth::user()->school->slug.
                    '/posts/'.$post_comment->post_id.
                    '/comments'.$post_comment_id.'/reply',
                    $file
                );
            } else {
                $path = null;
            }

            $post_reply->attachment_file = $path;
            $post_reply->status = 1;
            $post_reply->save();

            $message = trans('messages.update_success_msg', [
                'module' => 'Post Comment',
            ]);

            if ($post_reply->post->entity_name == 'App\Models\User') {
                $user = User::where('id', $post_reply->post->entity_id)->first();
                $details = trans('notification.post_comment_update_success_msg', [
                    'user' => Auth::user()->FullName,
                    'entity' => 'Post',
                ]);
            } elseif ($post_reply->post->entity_name == 'App\Models\Page') {
                $user = User::where('id', $post_reply->post->created_by)->first();
                $details = trans('notification.post_comment_update_success_msg', [
                    'user' => Auth::user()->FullName,
                    'entity' => 'Page',
                ]);
            }

            if ($user->id != Auth::id()) {
                $data = [];
                $data['user'] = $user;
                $data['details'] = $details;

                event(new SingleNotificationEvent($data));
            }

            $comment_user = User::where('id', $post_comment->user_id)->first();
            if ($comment_user->id != Auth::id()) {
                $array = [];
                $array['user'] = $comment_user;
                $array['details'] = trans(
                    'notification.post_reply_comment_update_success_msg',
                    ['user' => Auth::user()->FullName]
                );

                event(new SingleNotificationEvent($array));
            }

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $post_reply,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_EDIT_REPLY_COMMENT,
                $message
            );

            $res['success'] = $message;

            return $res;
        } catch (Exception $e) {
        }
    }

    /**
     * Delete a reply comment.
     *
     * Allows deletion only by the reply owner,
     * sends notifications, and logs activity.
     *
     * @param  int  $post_comment_id
     * @return array|null
     */
    public function destroy($post_comment_id)
    {
        //
        try {
            $post_reply = PostComment::where('id', $post_comment_id)->first();

            if ($post_reply->user_id == Auth::id()) {
                $post_comment = PostComment::where('id', $post_reply->entity_id)->first();

                $post_reply->status = 0;
                $post_reply->save();

                $message = trans('messages.delete_success_msg', [
                    'module' => 'Post Comment',
                ]);

                if ($post_reply->post->entity_name == 'App\Models\User') {
                    $user = User::where('id', $post_reply->post->entity_id)->first();
                    $details = trans('notification.post_comment_delete_success_msg', [
                        'user' => Auth::user()->FullName,
                        'entity' => 'Post',
                    ]);
                } elseif ($post_reply->post->entity_name == 'App\Models\Page') {
                    $user = User::where('id', $post_reply->post->created_by)->first();
                    $details = trans('notification.post_comment_delete_success_msg', [
                        'user' => Auth::user()->FullName,
                        'entity' => 'Page',
                    ]);
                }

                $post_reply->delete();

                if ($user->id != Auth::id()) {
                    $data = [];
                    $data['user'] = $user;
                    $data['details'] = $details;

                    event(new SingleNotificationEvent($data));
                }

                $comment_user = User::where('id', $post_comment->user_id)->first();
                if ($comment_user->id != Auth::id()) {
                    $array = [];
                    $array['user'] = $comment_user;
                    $array['details'] = trans(
                        'notification.post_reply_comment_delete_success_msg',
                        ['user' => Auth::user()->FullName]
                    );

                    event(new SingleNotificationEvent($array));
                }

                $ip = $this->getRequestIP();
                $this->doActivityLog(
                    $post_reply,
                    Auth::user(),
                    ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                    LOGNAME_DELETE_REPLY_COMMENT,
                    $message
                );

                $res['success'] = $message;

                return $res;
            } else {
                abort(403);
            }
        } catch (Exception $e) {
        }
    }
}
