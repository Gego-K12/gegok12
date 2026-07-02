<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Classwall\PostAttachmentRequest;
use App\Http\Requests\Classwall\PostRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Traits\LogActivity;
use App\Helpers\SiteHelper;
use App\Traits\Common;
use App\Models\Post;
use Exception;

/**
 * Class PostEditController
 *
 * Handles editing of classwall posts including
 * fetching edit data, updating post content,
 * managing scheduled posts, updating attachments,
 * and logging activities.
 *
 * @package App\Http\Controllers\Admin
 */
class PostEditController extends Controller
{
    //
    use LogActivity;
    use Common;

    /**
     * Get post data for edit form (API use).
     *
     * Returns post details such as description,
     * visibility, attachments, scheduling info,
     * and standard link list for editing.
     *
     * @param int $id Post ID
     * @return array
     */
    public function editList($id)
    {
        //
        $post = Post::where('id', $id)->first();

        if ($post->created_by == Auth::id())
        {
            $array = [];

            $array['description']      = $post->description;
            $array['visibility']       = $post->visibility;

            if ($post->visibility == 'select_class')
            {
                $array['visible_for'] = $post->visible_for;
            }
            else
            {
                $array['visible_for'] = '';
            }

            $array['post_created_at']  = date('d-m-Y H:i:s', strtotime($post->post_created_at));
            $array['is_posted']        = $post->is_posted;

            if ($post->is_posted == 1)
            {
                $array['post_later'] = 0;
            }
            else
            {
                $array['post_later'] = 1;
            }

            $array['attachment']       = $post->AttachmentPath;
            $array['standardLinkList'] = SiteHelper::getStandardLinkList(Auth::user()->school_id);

            return $array;
        }
        else
        {
            abort(403);
        }
    }

    /**
     * Show the post edit page.
     *
     * Ensures only the post creator can access
     * the edit screen.
     *
     * @param int $id Post ID
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        //
        $post = Post::where('id', $id)->first();

        if ($post->created_by == Auth::id())
        {
            $entity_id   = Auth::id();
            $entity_name = 'App\Models\User';

            return view('/admin/classwall/post/edit', [
                'post'        => $post,
                'entity_id'   => $entity_id,
                'entity_name' => $entity_name
            ]);
        }
        else
        {
            abort(403);
        }
    }

    /**
     * Update post content and scheduling.
     *
     * Updates description, visibility, and
     * scheduled or immediate posting status.
     *
     * @param \App\Http\Requests\Classwall\PostRequest $request
     * @param int $id Post ID
     * @return array|null
     */
    public function update(PostRequest $request, $id)
    {
        //
        try
        {
            $post = Post::where('id', $id)->first();

            $post->description = $request->description;
            $post->visibility  = $request->visibility;

            if ($request->post_later == 'true')
            {
                $post->post_created_at = date('Y-m-d H:i:s', strtotime($request->posted_at));
                $post->status          = 'pending';
            }
            else
            {
                $post->post_created_at = date('Y-m-d H:i:s');
                $post->posted_at       = date('Y-m-d H:i:s');
                $post->is_posted       = 1;
                $post->status          = 'posted';
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
        }
        catch (Exception $e)
        {
        }
    }

    /**
     * Update post attachments.
     *
     * Handles existing attachment retention,
     * removal, and new file uploads.
     *
     * @param \App\Http\Requests\Classwall\PostAttachmentRequest $request
     * @param int $id Post ID
     * @return void
     */
    public function editAttachment(PostAttachmentRequest $request, $id)
    {
        //
        try
        {
            $post = Post::where('id', $id)->first();

            if ($request->attachment_count > 0)
            {
                $post->attachment_file = null;
                $post->save();

                $initial_path = [];
                for ($j = 0; $j < $request->attachment_count; $j++)
                {
                    $attachment = 'attachment' . $j;
                    $initial_path[$j] = $request->$attachment;
                }

                $post->attachment_file = $initial_path;
                $post->save();
            }

            $files = $request->file;

            if (count($files) > 0)
            {
                $i = $request->count + 1;
                $path = [];

                foreach ($files as $file)
                {
                    $path[$i] = $this->uploadFile(
                        Auth::user()->school->slug . '/posts/' . $id,
                        $file
                    );
                    $i++;
                }

                $post->attachment_file = array_merge($post->attachment_file, $path);
                $post->save();
            }
        }
        catch (Exception $e)
        {
        }
    }
}
