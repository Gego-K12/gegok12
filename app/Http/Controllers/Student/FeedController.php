<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Student;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\StudentAcademic;
use App\Services\FeedReaderService;
use App\Traits\Common;
use App\Traits\LogActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class FeedController extends Controller
{
    use Common;

    //
    use LogActivity;

    public function __construct(protected FeedReaderService $feedReader) {}

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $schoolId = Auth::user()->school_id;
        $academicYearId = SiteHelper::getAcademicYear($schoolId)->id;

        $feeds = $this->scopedFeedQuery($request, $schoolId, $academicYearId)
            ->orderBy('posted_at', 'desc')
            ->paginate(10);

        $banners = $this->feedReader->bannerPaths();

        return view('/student/feed/feed', [
            'feeds' => $feeds,
            'tags' => $this->feedReader->tagCloud($schoolId),
            'birthday' => $banners['birthday'],
            'anniversary' => $banners['anniversary'],
            'exam' => $banners['exam'],
        ]);
    }

    /**
     * school_id/academic_year_id/is_posted-scoped Post query, with the
     * `list`/`search` request filters applied on top. Student's default
     * (no `list`/`search` given) is posts targeted at the student's own
     * class, or broadcast to all classes. The two conditions are grouped
     * in a nested where() so they don't break out of the tenant scoping
     * above them - the same `orWhere`-breaks-AND-grouping bug already
     * fixed for Task/Notice/Holidays/Events in this cleanup.
     */
    private function scopedFeedQuery(Request $request, int $schoolId, int $academicYearId): Builder
    {
        $query = Post::query();
        $this->feedReader->scopeToTenant($query, $schoolId, $academicYearId);

        if (! $this->feedReader->applyListOrSearchFilter($query, $request->list, $request->search, $schoolId)) {
            $class = StudentAcademic::where('user_id', Auth::id())->first();

            $query->where(function ($q) use ($class) {
                $q->where('visible_for', $class?->standardLink_id)
                    ->orWhere('visibility', 'all_class');
            });
        }

        return $query;
    }

    public function filter(Request $request)
    {
        $schoolId = Auth::user()->school_id;
        $academicYearId = SiteHelper::getAcademicYear($schoolId)->id;

        $feeds = $this->scopedFeedQuery($request, $schoolId, $academicYearId)->get();

        $banners = $this->feedReader->bannerPaths();

        return view('/student/feed/filter', [
            'feeds' => $feeds,
            'tags' => $this->feedReader->tagCloud($schoolId),
            'birthday' => $banners['birthday'],
            'anniversary' => $banners['anniversary'],
            'exam' => $banners['exam'],
        ]);
    }
}
