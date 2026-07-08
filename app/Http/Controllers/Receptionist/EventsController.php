<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Receptionist;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Services\EventReaderService;
use App\Traits\Common;
use Illuminate\Support\Facades\Auth;

class EventsController extends Controller
{
    use Common;

    public function __construct(protected EventReaderService $eventReader)
    {
        $this->academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
        // $this->academic_year=$this->academic_year->id;
    }

    public function index()
    {
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);

        $events = $this->eventReader->calendarEvents(
            schoolId: $school_id,
            academicYearId: $academic_year->id,
            applyGexamGate: false,
            standardLinkFilter: null,
            includeSelectTypeAndColor: true,
        );
        $count = $this->eventReader->count($school_id, $academic_year->id);
        $subscription = Subscription::where('school_id', $school_id)->first();

        $events = json_encode($events);

        return view('reception.events.index', ['events' => $events, 'count' => $count, 'subscription' => $subscription]);
    }

    public function events()
    {
        return $this->eventReader->expandedEvents(Auth::user()->school_id, $this->academic_year->id);
    }

    public function showdetails($id)
    {
        return $this->eventReader->showDetails($id, Auth::user()->school_id);
    }

    public function showimage($event_id)
    {
        return $this->eventReader->showImage($event_id, Auth::user()->school_id);
    }

    public function details($id)
    {
        return $this->eventReader->detailsForModal($id);
    }
}
