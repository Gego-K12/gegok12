<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers\Teacher;

use App\Events\Notification\SingleNotificationEvent;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Models\PostCommentDetail;
use Illuminate\Http\Request;
use App\Traits\LogActivity;
use App\Helpers\SiteHelper;
use App\Models\PostReply;
use App\Traits\Common;
use App\Models\Post;
use App\Models\User;
use Exception;

class PostCommentDetailsController extends Controller
{
    //
    use LogActivity;
    use Common;

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function like(Request $request,$comment_id)
    {
        //
        try
        {
            $post_comment = PostCommentDetail::where([['user_id',Auth::id()],['post_comment_id',$comment_id]])->first();

            if($post_comment != null)
            {
                $post_comment->like = $request->like;
                $post_comment->status = 1;

                $post_comment->save();
            }
            else
            {
                $post_comment = new PostCommentDetail;

                $post_comment->user_id    		= Auth::id();
                $post_comment->post_comment_id	= $comment_id;
                $post_comment->like       		= $request->like;
                $post_comment->status     		= 1;

                $post_comment->save();
            }

            if($post_comment_detail->postComment->post->entity_name == 'App\Models\User')
            {
                $user = User::where('id',$post_comment_detail->postComment->post->entity_id)->first();
                if($request->like == 1)
                {
                    $details = trans('notification.page_comment_like_success_msg',['user' => Auth::user()->FullName , 'entity' => 'Post']);
                }
                else
                {
                    $details = trans('notification.page_comment_remove_like_success_msg',['user' => Auth::user()->FullName , 'entity' => 'Post']);
                }
            }
            elseif($post_comment_detail->postComment->post->entity_name == 'App\Models\Page')
            {
                $page = ClassRoomPage::where('id',$post_comment_detail->postComment->post->entity_id)->first();
                $user = User::where('id',$page->created_by)->first();

                if($request->like == 1)
                {
                    $details = trans('notification.page_comment_like_success_msg',['user' => Auth::user()->FullName , 'entity' => 'Page']);
                }
                else
                {
                    $details = trans('notification.page_comment_remove_like_success_msg',['user' => Auth::user()->FullName , 'entity' => 'Page']);
                }
            }

            if($request->like == 1)
            {
                $message = trans('messages.like_success_msg',['page' => 'comment']);
            }
            else
            {
                $message = trans('messages.remove_like_success_msg',['page' => 'comment']);
            }

            if($user->id != Auth::id())
            {
                $data = [];

                $data['user']       =   $user;
                $data['details']    =   $details;

                event(new SingleNotificationEvent($data));
            }

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $post_comment,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_LIKE_COMMENT,
                $message
            ); 

            $res['success'] = $message;
            return $res;
        }
        catch(Exception $e)
        {
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function dislike(Request $request,$comment_id)
    {
        //
        try
        {
            $post_comment = PostCommentDetail::where([['user_id',Auth::id()],['post_comment_id',$comment_id]])->first();

            if($post_comment != null)
            {
                $post_comment->unlike  = $request->dislike;
                $post_comment->status  = 1;

                $post_comment->save();
            }
            else
            {
                $post_comment = new PostCommentDetail;

                $post_comment->user_id    		= Auth::id();
                $post_comment->post_comment_id	= $comment_id;
                $post_comment->unlike       	= $request->dislike;
                $post_comment->status     		= 1;

                $post_comment->save();
            }

            if($post_comment_detail->postComment->post->entity_name == 'App\Models\User')
            {
                $user = User::where('id',$post_comment_detail->postComment->post->entity_id)->first();
                if($request->dislike == 1)
                {
                    $details = trans('notification.page_comment_dislike_success_msg',['user' => Auth::user()->FullName , 'entity' => 'Post']);
                }
                else
                {
                    $details = trans('notification.page_comment_remove_dislike_success_msg',['user' => Auth::user()->FullName , 'entity' => 'Post']);
                }
            }
            elseif($post_comment_detail->postComment->post->entity_name == 'App\Models\Page')
            {
                $page = ClassRoomPage::where('id',$post_comment_detail->postComment->post->entity_id)->first();
                $user = User::where('id',$page->created_by)->first();

                if($request->dislike == 1)
                {
                    $details = trans('notification.page_comment_dislike_success_msg',['user' => Auth::user()->FullName , 'entity' => 'Page']);
                }
                else
                {
                    $details = trans('notification.page_comment_remove_dislike_success_msg',['user' => Auth::user()->FullName , 'entity' => 'Page']);
                }
            }

            if($request->dislike == 1)
            {
                $message = trans('messages.unlike_success_msg',['page' => 'comment']);
            }
            else
            {
                $message = trans('messages.remove_unlike_success_msg',['page' => 'comment']);
            }

            if($user->id != Auth::id())
            {
                $data = [];

                $data['user']       =   $user;
                $data['details']    =   $details;

                event(new SingleNotificationEvent($data));
            }

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $post_comment,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_UNLIKE_COMMENT,
                $message
            ); 

            $res['success'] = $message;
            return $res;
        }
        catch(Exception $e)
        {
        }
    }
}