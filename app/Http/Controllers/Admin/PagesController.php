<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Classwall\PageAddRequest;
use App\Http\Requests\Classwall\PageUpdateRequest;
use App\Http\Resources\Classwall\Page as PageResource;
use App\Models\ClassRoomPage;
use App\Models\ClassRoomPageDetail;
use App\Models\User;
use App\Traits\Common;
use App\Traits\LogActivity;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Log;

/**
 * Class PagesController
 *
 * Handles classroom page management including listing,
 * creation, viewing, editing, updating, and deletion.
 * Includes activity logging and authorization checks.
 */
class PagesController extends Controller
{
    use Common;
    use LogActivity;

    /**
     * Get paginated list of active classroom pages.
     *
     * @return AnonymousResourceCollection
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
     * Display classroom page index view.
     *
     * @return View
     */
    public function index()
    {
        //
        return view('/admin/classwall/page/index');
    }

    /**
     * Show classroom page creation form.
     *
     * @return View
     */
    public function create()
    {
        //
        return view('/admin/classwall/page/create');
    }

    /**
     * Store a newly created classroom page.
     *
     * @return array|null
     */
    public function store(PageAddRequest $request)
    {
        //
        try {
            $school_id = Auth::user()->school_id;
            $academic_year = SiteHelper::getAcademicYear($school_id);

            $page = new ClassRoomPage;

            $page->school_id = $school_id;
            $page->academic_year_id = $academic_year->id;
            $page->page_name = $request->page_name;
            $page->category_id = $request->category;
            $page->description = $request->description;
            $page->created_by = Auth::id();
            $page->status = 1;

            $file = $request->file('cover_image');
            if ($file) {
                $folder = Auth::user()->school->slug.'/pages';
                $path = $this->uploadFile($folder, $file);
                $page->cover_image = $path;
            }

            $page->save();

            $message = trans('messages.add_success_msg', ['module' => 'Page']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $page,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_ADD_PAGE,
                $message
            );

            $res['success'] = $message;

            return $res;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Get page statistics and user interaction data.
     *
     * @param  int  $id
     * @return array
     */
    public function showList($id)
    {
        //
        $page = ClassRoomPage::where('id', $id)->first();

        $array = [];

        $array['page_name'] = $page->page_name;
        $array['category'] = $page->category_id;
        $array['description'] = $page->description;
        $array['cover_image'] = $page->CoverImagePath;
        $array['like_count'] = $page->classRoomPageDetail()->where('like', 1)->count();
        $array['unlike_count'] = $page->classRoomPageDetail()->where('dislike', 1)->count();
        $array['follow_count'] = $page->classRoomPageDetail()->where('is_following', 1)->count();

        $pagedetail = ClassRoomPageDetail::where([
            ['user_id', Auth::id()],
            ['page_id', $page->id],
        ])->first();

        if ($pagedetail != null) {
            $array['is_following'] = $pagedetail->is_following;
            $array['like'] = $pagedetail->like;
            $array['dislike'] = $pagedetail->dislike;
        }

        return $array;
    }

    /**
     * Display page detail view.
     *
     * @param  int  $id
     * @return View
     */
    public function show($id)
    {
        //
        $page = ClassRoomPage::where('id', $id)->first();

        $entity_id = $page->id;
        $entity_name = 'App\Models\Page';

        return view('/admin/classwall/page/show', [
            'page' => $page,
            'entity_id' => $entity_id,
            'entity_name' => $entity_name,
        ]);
    }

    /**
     * Get page data for edit form.
     *
     * @param  int  $id
     * @return array
     */
    public function editList($id)
    {
        //
        $page = ClassRoomPage::where('id', $id)->first();

        $array = [];

        $array['page_name'] = $page->page_name;
        $array['category'] = $page->category_id;
        $array['description'] = $page->description;
        $array['cover_image'] = $page->CoverImagePath;

        return $array;
    }

    /**
     * Show classroom page edit form.
     *
     * @param  int  $id
     * @return View
     */
    public function edit($id)
    {
        //
        $page = ClassRoomPage::where('id', $id)->first();

        return view('/admin/classwall/page/edit', ['page' => $page]);
    }

    /**
     * Update a classroom page.
     *
     * @param  int  $id
     * @return array|null
     */
    public function update(PageUpdateRequest $request, $id)
    {
        //
        try {
            $page = ClassRoomPage::where('id', $id)->first();

            $page->page_name = $request->page_name;
            $page->category_id = $request->category;
            $page->description = $request->description;
            $page->created_by = Auth::id();

            $file = $request->file('cover_image');
            if ($file) {
                $folder = Auth::user()->school->slug.'/pages';
                $path = $this->uploadFile($folder, $file);
                $page->cover_image = $path;
            }

            $page->save();

            $message = trans('messages.update_success_msg', ['module' => 'Page']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $page,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_EDIT_PAGE,
                $message
            );

            $res['success'] = $message;

            return $res;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Delete a classroom page.
     *
     * @param  int  $id
     * @return array|null
     */
    public function destroy($id)
    {
        //
        try {
            $page = ClassRoomPage::where('id', $id)->first();

            if (Gate::allows('page', $page)) {
                $page->delete();

                $message = trans('messages.delete_success_msg', ['module' => 'Page']);

                $ip = $this->getRequestIP();
                $this->doActivityLog(
                    $page,
                    Auth::user(),
                    ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                    LOGNAME_DELETE_PAGE,
                    $message
                );

                $res['success'] = $message;

                return $res;
            } else {
                abort(403);
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
