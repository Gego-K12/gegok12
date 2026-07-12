<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Student;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Subscription;
use App\Services\EventReaderService;
use App\Traits\Common;
use Carbon\Carbon;
use Gegok12\Exam\Models\Exam;
use Gegok12\Exam\Models\ExamSchedule;
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
        $academic_year = $this->academic_year;

        $events = $this->eventReader->calendarEvents(
            schoolId: $school_id,
            academicYearId: $academic_year->id,
            applyGexamGate: true,
            standardLinkFilter: null,
            includeSelectTypeAndColor: false,
        );
        $count = $this->eventReader->count($school_id, $academic_year->id);
        $subscription = Subscription::where('school_id', $school_id)->first();

        $events = json_encode($events);

        return view('student.events.index', ['events' => $events, 'count' => $count, 'subscription' => $subscription]);
    }

    public function events()
    {
        return $this->eventReader->expandedEvents(Auth::user()->school_id, $this->academic_year->id);
    }

    public function show($id)
    {
        $event = $this->eventReader->find($id, Auth::user()->school_id);

        if (! $event) {
            abort(404);
        }

        if ($event->category == 'exam') {
            $exam = Exam::where('name', $event->title)->where('standard_id', $event->standard_id)->first();
            $schedule = ExamSchedule::where('exam_id', $exam->id)->first();
            $subject = Subject::where('id', $schedule->subject_id)->first();
            $subject_name = $subject->name;
            $start = Carbon::createFromFormat('Y-m-d H:i:s', $event->start_date);
            $end = Carbon::createFromFormat('Y-m-d H:i:s', $event->end_date);
            $duration = $end->diffInHours($start) * 60;

            return view('student.events.detail', ['event' => $event, 'duration' => $duration, 'subject_name' => $subject_name]);
        }

        return view('student.events.show', ['event' => $event]);
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
