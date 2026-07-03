<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostTag;
use App\Models\Tag;
use App\Traits\Common;
use App\Traits\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FeedController extends Controller
{
    use Common;
    //
    use LogActivity;

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $feeds = Post::where('visibility', 'all_class')->orderBy('posted_at', 'DESC')->get();

        if ($request->list != '') {
            $category = $request->list;

            $feeds = Post::where('visibility', $category)->get();
        } elseif ($request->search != '') {
            $category = $request->search;

            $tags = Tag::where('tag_name', $category)->first();

            $post_tag = PostTag::where('tag_id', $tags->id)->pluck('post_id')->toArray();

            $feeds = Post::whereIn('id', $post_tag)->get();
        }

        return view('/reception/feed/feed', ['feeds' => $feeds]);
    }
}
