<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Student;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Classwall\Page as PageResource;
use App\Models\ClassRoomPage;
use App\Models\ClassRoomPageDetail;
use App\Traits\Common;
use App\Traits\LogActivity;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class PagesController extends Controller
{
    use Common;
    use LogActivity;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function list()
    {
        //
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);
        $pages = ClassRoomPage::where([
            ['school_id', $school_id],
            ['academic_year_id', $academic_year->id],
            ['status', 1],
        ])->paginate(5);
        $pages = PageResource::collection($pages);

        return $pages;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        return view('/student/classwall/page/index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function showList($id)
    {
        //
        $page = ClassRoomPage::where('id', $id)->first();

        $array = [];

        $array['page_name'] = $page->page_name;
        $array['category'] = $page->category;
        $array['description'] = $page->description;
        $array['cover_image'] = $page->CoverImagePath;
        $array['like_count'] = $page->classRoomPageDetail()->where('like', 1)->count();
        $array['unlike_count'] = $page->classRoomPageDetail()->where('dislike', 1)->count();
        $array['follow_count'] = $page->classRoomPageDetail()->where('is_following', 1)->count();
        $pagedetail = ClassRoomPageDetail::where([['user_id', Auth::id()], ['page_id', $page->id]])->first();
        if ($pagedetail != null) {
            $array['is_following'] = $pagedetail->is_following;
            $array['like'] = $pagedetail->like;
            $array['dislike'] = $pagedetail->dislike;
        }

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
        $page = ClassRoomPage::where('id', $id)->first();
        $entity_id = $page->id;
        $entity_name = 'App\Models\Page';

        return view('/student/classwall/page/show', ['page' => $page, 'entity_id' => $entity_id, 'entity_name' => $entity_name]);
    }
}
