<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Teacher;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Classwall\Post as PostResource;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\PostDetail;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PostsController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function indexList(Request $request)
    {
        //
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);
        $posts = Post::where([['school_id', $school_id], ['academic_year_id', $academic_year->id], ['is_posted', 1]])->orderBy('post_created_at', 'DESC'); // ['visibility','all_class'],
        if (count((array) \Request::getQueryString()) > 0) {
            if ($request->entity_id != '') {
                $posts = $posts->where('entity_id', $request->entity_id);
            }
            if ($request->entity_name != '') {
                $posts = $posts->where('entity_name', $request->entity_name);
            }
        }
        $posts = $posts->get();
        $posts = PostResource::collection($posts);

        return $posts;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        $entity_id = Auth::id();
        $entity_name = 'App\Models\User';

        return view('/teacher/classwall/post/index', ['entity_id' => $entity_id, 'entity_name' => $entity_name]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function showList($id)
    {
        //
        $post = Post::where('id', $id)->first();

        if ($post->visibility == 'all_class') {
            $visibility = str_replace('_', ' ', ucwords($post->visibility));
        } elseif ($post->visibility == 'select_class') {
            $visibility = $post->StandardLink->StandardSection;
        }

        $array = [];

        $array['description'] = $post->description;
        $array['visibility'] = $visibility;
        $array['post_created_at'] = Carbon::parse($this->post_created_at)->diffForHumans(); // $post->post_created_at->diffForHumans();
        $array['created_by'] = $post->created_by;
        $array['is_posted'] = $post->is_posted;
        $array['attachments'] = $post->AttachmentPath;
        $post_detail = PostDetail::where([['user_id', Auth::id()], ['post_id', $id]])->first();
        $array['like'] = $post_detail->like;
        $array['unlike'] = $post_detail->unlike;
        $array['save'] = $post_detail->save;
        $array['unsave'] = $post_detail->unsave;
        $array['auth_id'] = Auth::id();
        $array['like_count'] = $post->postDetail == null ? null : $post->postDetail->ByLikeCount($post->id);
        $array['unlike_count'] = $post->postDetail == null ? null : $post->postDetail->ByUnlikeCount($post->id);
        $array['comment_list']['comments'] = $post->PostComments;
        $array['comment_list']['comments_count'] = count($post->PostComments);

        return $array;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
        $post = Post::where('id', $id)->first();

        return view('/teacher/classwall/post/show', ['post' => $post]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function editCommentList($comment_id)
    {
        //
        $post_reply = PostComment::where('id', $comment_id)->first();

        $array = [];

        $array['id'] = $post_reply->id;
        $array['comments'] = $post_reply->comments;
        $array['attachment_file'] = $post_reply->attachment_file == null ? '' : $post_reply->AttachmentPath;

        return $array;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
        try {
            $post = Post::where('id', $id)->first();
            if (Gate::allows('post', $post)) {
                if ($post->created_by == Auth::id()) {
                    $post->status = 'cancelled';
                    $post->save();

                    $post->delete();

                    $message = trans('messages.delete_success_msg', ['module' => 'Post']);

                    $ip = $this->getRequestIP();
                    $this->doActivityLog(
                        $post,
                        Auth::user(),
                        ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                        LOGNAME_DELETE_POST,
                        $message
                    );
                    $res['success'] = $message;

                    return $res;
                } else {
                    abort(403);
                }
            } else {
                abort(403);
            }
        } catch (Exception $e) {
        }
    }
}
