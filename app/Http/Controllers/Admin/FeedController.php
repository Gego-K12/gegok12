<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Services\FeedReaderService;
use App\Traits\Common;
use App\Traits\LogActivity;
use Illuminate\Database\Eloquent\Builder;
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

    public function __construct(protected FeedReaderService $feedReader) {}

    /**
     * Show the feed listing.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $schoolId = Auth::user()->school_id;
        $academicYearId = SiteHelper::getAcademicYear($schoolId)->id;

        $feeds = $this->scopedFeedQuery($request, $schoolId, $academicYearId)
            ->orderBy('posted_at', 'desc')
            ->paginate(5);

        $banners = $this->feedReader->bannerPaths();

        return view('/admin/feed/feed', [
            'feeds' => $feeds,
            'tags' => $this->feedReader->tagCloud($schoolId),
            'birthday' => $banners['birthday'],
            'anniversary' => $banners['anniversary'],
            'exam' => $banners['exam'],
            'leftarrow' => $this->getFilePath('uploads/static/arrow-l.png'),
            'rightarrow' => $this->getFilePath('uploads/static/arrow-r.png'),
            'entity_id' => Auth::id(),
            'entity_name' => 'App\Models\User',
        ]);
    }

    /**
     * school_id/academic_year_id/is_posted-scoped Post query, with the
     * `list`/`search` request filters applied on top. Admin's default
     * (no `list`/`search` given) is deliberately left with no additional
     * visibility restriction - unlike every other role, Admin's feed
     * shows posts of any visibility.
     */
    private function scopedFeedQuery(Request $request, int $schoolId, int $academicYearId): Builder
    {
        $query = Post::query();
        $this->feedReader->scopeToTenant($query, $schoolId, $academicYearId);

        $this->feedReader->applyListOrSearchFilter($query, $request->list, $request->search, $schoolId);

        return $query;
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
        $schoolId = Auth::user()->school_id;
        $academicYearId = SiteHelper::getAcademicYear($schoolId)->id;

        $feeds = $this->scopedFeedQuery($request, $schoolId, $academicYearId)->get();

        $banners = $this->feedReader->bannerPaths();

        return view('/admin/feed/filter', [
            'feeds' => $feeds,
            'tags' => $this->feedReader->tagCloud($schoolId),
            'birthday' => $banners['birthday'],
            'anniversary' => $banners['anniversary'],
            'exam' => $banners['exam'],
        ]);
    }
}
