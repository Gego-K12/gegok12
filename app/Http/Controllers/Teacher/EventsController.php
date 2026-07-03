<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Teacher;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\Standard as StandardResource;
use App\Models\Events;
use App\Models\Exam;
use App\Models\ExamSchedule;
use App\Models\StandardLink;
use App\Models\Subject;
use App\Models\Subscription;
use App\Services\EventReaderService;
use App\Traits\Common;
use App\Traits\EventProcess;
use App\Traits\LogActivity;
use App\Traits\ReminderProcess;
use App\Traits\SendPushNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventsController extends Controller
{
    use Common;
    use EventProcess;
    use LogActivity;
    use ReminderProcess;
    use SendPushNotification;

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

        return view('teacher.events.index', ['events' => $events, 'count' => $count, 'subscription' => $subscription]);
    }

    public function list()
    {
        $standard = StandardLink::with('standard', 'section')->where('school_id', Auth::user()->school_id)->get();
        $standard = StandardResource::collection($standard);

        $array = [];

        $array['standardlist'] = $standard;

        return $array;
    }

    public function changeevent(Request $request, $id)
    {

        $event = Events::findOrFail($id);

        if ($request->end_date == 'undefined') {
            $request['end_date'] = date('Y-m-d H:i:s', strtotime($request->start_date));
        }

        if ($request->start_date == $request->end_date) {
            $request['allDay'] = 1;
        }

        $event->fill($request->all());
        $event->save();
        echo json_encode(['status' => 'Event has been update']);
    }

    /**
     * @return array
     */
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

        if ($event->category == 'holidays') {
            abort(403);
        }

        if ($event->category == 'exam') {
            $exam = Exam::where('name', $event->title)->where('standard_id', $event->standard_id)->first();
            $schedule = ExamSchedule::where('exam_id', $exam->id)->first();
            $subject = Subject::where('id', $schedule->subject_id)->first();
            $subject_name = $subject->name;
            $start = Carbon::createFromFormat('Y-m-d H:i:s', $event->start_date);
            $end = Carbon::createFromFormat('Y-m-d H:i:s', $event->end_date);
            $duration = $end->diffInHours($start) * 60;

            return view('teacher.events.detail', ['event' => $event, 'duration' => $duration, 'subject_name' => $subject_name]);
        }

        return view('teacher.events.show', ['event' => $event]);
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
