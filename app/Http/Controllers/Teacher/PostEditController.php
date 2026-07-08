<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Teacher;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Classwall\PostRequest;
use App\Models\Post;
use App\Traits\Common;
use App\Traits\LogActivity;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class PostEditController extends Controller
{
    use Common;
    //
    use LogActivity;

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function editList($id)
    {
        //
        $post = Post::where('id', $id)->first();

        if ($post->created_by == Auth::id()) {
            $array = [];

            $array['description'] = $post->description;
            $array['visibility'] = $post->visibility;
            if ($post->visibility == 'select_class') {
                $array['visible_for'] = $post->visible_for;
            } else {
                $array['visible_for'] = '';
            }
            $array['post_created_at'] = date('d-m-Y H:i:s', strtotime($post->post_created_at));
            $array['is_posted'] = $post->is_posted;
            if ($post->is_posted == 1) {
                $array['post_later'] = 0;
            } else {
                $array['post_later'] = 1;
            }
            $array['attachment'] = $post->AttachmentPath;
            $array['standardLinkList'] = SiteHelper::getStandardLinkList(Auth::user()->school_id);

            return $array;
        } else {
            abort(403);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
        $post = Post::where('id', $id)->first();

        if ($post->created_by == Auth::id()) {
            $entity_id = Auth::id();
            $entity_name = 'App\Models\User';

            return view('/teacher/classwall/post/edit', ['post' => $post, 'entity_id' => $entity_id, 'entity_name' => $entity_name]);
        } else {
            abort(403);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(PostRequest $request, $id)
    {
        //
        try {
            $post = Post::where('id', $id)->first();

            $post->description = $request->description;
            $post->visibility = $request->visibility;
            if ($request->post_later == 'true') {
                $post->post_created_at = date('Y-m-d H:i:s', strtotime($request->posted_at));
                $post->status = 'pending';
            } else {
                $post->post_created_at = date('Y-m-d H:i:s');
                $post->posted_at = date('Y-m-d H:i:s');
                $post->is_posted = 1;
                $post->status = 'posted';
            }

            $post->save();

            $message = trans('messages.update_success_msg', ['module' => 'Post']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $post,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_EDIT_POST,
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
    public function editAttachment(Request $request, $id)
    {
        try {

            $post = Post::findOrFail($id);

            if ($request->hasFile('file')) {

                $files = $request->file('file');

                // If single file, convert to array
                if (! is_array($files)) {
                    $files = [$files];
                }

                // Get existing attachments safely
                $attachments = $post->attachment_file ?? [];

                if (! is_array($attachments)) {
                    $attachments = [];
                }

                foreach ($files as $file) {

                    $path = $this->uploadFile(
                        Auth::user()->school->slug.'/posts/'.$id,
                        $file
                    );

                    $attachments[] = $path;
                }

                $post->attachment_file = $attachments;
                $post->save();
            }

            return response()->json(['success' => true]);

        } catch (Exception $e) {

            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
