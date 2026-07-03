<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostTag;
use App\Models\Tag;
use App\Traits\Common;
use App\Traits\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

/**
 * FeedController
 *
 * This controller handles the admin feed functionality such as:
 * - Displaying feeds
 * - Filtering feeds by visibility or tags
 * - Preparing data required for the feed view
 */
class FeedController extends Controller
{
    use Common;
    use LogActivity;

    /**
     * Show the feed listing.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        // get Tag data and join with post_tag
        $tags = Tag::join('post_tags', 'tags.id', '=', 'post_tags.tag_id')
            ->groupBy('tags.id')
            ->select(['tags.*', \DB::raw('COUNT(*) as cnt')])
            ->orderBy('cnt', 'desc')
            ->take(20)
            ->get();

        $birthday = $this->getFilePath('uploads/images/birthday.jpg');
        $anniversary = $this->getFilePath('uploads/images/work_anniversary.jpg');
        $exam = $this->getFilePath('uploads/images/exam-banner.jpg');
        $leftarrow = $this->getFilePath('uploads/static/arrow-l.png');
        $rightarrow = $this->getFilePath('uploads/static/arrow-r.png');

        if ($request->list != '') {
            $category = $request->list;
            $feeds = Post::where('visibility', $category)->paginate(5);
        } elseif ($request->search != '') {
            $category = $request->search;

            $tags = Tag::where('tag_name', $category)->first();
            $post_tag = PostTag::where('tag_id', $tags->id)->pluck('post_id')->toArray();
            $feeds = Post::whereIn('id', $post_tag)->paginate(5);
        } else {
            $feeds = Post::orderBy('posted_at', 'desc')->paginate(5);
        }

        $entity_id = Auth::id();
        $entity_name = 'App\Models\User';

        return view('/admin/feed/feed', [
            'feeds' => $feeds,
            'tags' => $tags,
            'birthday' => $birthday,
            'anniversary' => $anniversary,
            'exam' => $exam,
            'leftarrow' => $leftarrow,
            'rightarrow' => $rightarrow,
            'entity_id' => $entity_id,
            'entity_name' => $entity_name,
        ]);
    }

    /**
     * Show feed create page.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        if (count((array) \Request::getQueryString()) > 0) {
            if ($request->entity_id != '') {
                $entity_id = $request->entity_id;
            }
            if ($request->entity_name != '') {
                $entity_name = $request->entity_name;
            }
        } else {
            $entity_id = Auth::id();
            $entity_name = 'App\Models\User';
        }

        return view('/admin/feed/feed', [
            'entity_id' => $entity_id,
            'entity_name' => $entity_name,
        ]);
    }

    /**
     * Filter feed posts.
     *
     * @return Response
     */
    public function filter(Request $request)
    {
        $tags = Tag::join('post_tag', 'tags.id', '=', 'post_tag.tag_id')
            ->groupBy('tags.id')
            ->select(['tags.*', \DB::raw('COUNT(*) as cnt')])
            ->orderBy('cnt', 'desc')
            ->take(20)
            ->get();

        $birthday = $this->getFilePath('uploads/images/birthday.jpg');
        $anniversary = $this->getFilePath('uploads/images/work_anniversary.jpg');
        $exam = $this->getFilePath('uploads/images/exam-banner.jpg');

        if ($request->list != '') {
            $category = $request->list;
            $feeds = Post::where('visibility', $category)->get();
        } elseif ($request->search != '') {
            $category = $request->search;

            $tags = Tag::where('tag_name', $category)->first();
            $post_tag = PostTag::where('tag_id', $tags->id)->pluck('post_id')->toArray();
            $feeds = Post::whereIn('id', $post_tag)->get();
        }

        return view('/admin/feed/filter', [
            'feeds' => $feeds,
            'tags' => $tags,
            'birthday' => $birthday,
            'anniversary' => $anniversary,
            'exam' => $exam,
        ]);
    }
}
