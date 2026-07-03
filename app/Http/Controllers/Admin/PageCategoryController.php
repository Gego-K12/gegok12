<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Classwall\PageCategoryRequest;
use App\Http\Resources\Classwall\PageCategory as PageCategoryResource;
use App\Models\ClassRoomPageCategory;
use App\Models\User;
use App\Traits\Common;
use App\Traits\LogActivity;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Log;

/**
 * Class PageCategoryController
 *
 * Manages classroom page categories including listing
 * and creation of categories with activity logging.
 */
class PageCategoryController extends Controller
{
    use Common;
    //
    use LogActivity;

    /**
     * Get active page categories for the current school and academic year.
     *
     * Returns a resource collection of page categories
     * associated with the authenticated user's school.
     *
     * @return AnonymousResourceCollection
     */
    public function list()
    {
        //
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);

        $category = ClassRoomPageCategory::where([
            ['school_id', $school_id],
            ['academic_year_id', $academic_year->id],
            ['status', 1],
        ])->get();

        $category = PageCategoryResource::collection($category);

        return $category;
    }

    /**
     * Store a newly created page category.
     *
     * Creates a new classroom page category for the
     * current academic year and logs the activity.
     *
     * @return array|null
     */
    public function store(PageCategoryRequest $request)
    {
        //
        try {
            $school_id = Auth::user()->school_id;
            $academic_year = SiteHelper::getAcademicYear($school_id);

            $category = new ClassRoomPageCategory;

            $category->school_id = $school_id;
            $category->academic_year_id = $academic_year->id;
            $category->name = strtolower(str_replace(' ', '_', $request->name));
            $category->status = 1;

            $category->save();

            $message = trans('messages.add_success_msg', ['module' => 'Page Category']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $category,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_ADD_PAGE_CATEGORY,
                $message
            );

            $res['success'] = $message;

            return $res;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
