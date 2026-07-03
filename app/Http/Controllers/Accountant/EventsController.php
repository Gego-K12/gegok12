<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Accountant;

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
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Events controller for accountant area.
 *
 * Handles calendar/event listing, repetition logic and event details used
 * by the accountant dashboard.
 */
class EventsController extends Controller
{
    use Common;
    use EventProcess;
    use LogActivity;
    use ReminderProcess;
    use SendPushNotification;

    /**
     * EventsController constructor.
     */
    public function __construct(protected EventReaderService $eventReader)
    {
        $this->academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
        // $this->academic_year=$this->academic_year->id;
    }

    /**
     * Render calendar index with events JSON for the accountant.
     *
     * @return View
     */
    public function index()
    {
        $school_id = Auth::user()->school_id;
        $academic_year = $this->academic_year;

        $events = $this->eventReader->calendarEvents(
            schoolId: $school_id,
            academicYearId: $academic_year->id,
            applyGexamGate: false,
            standardLinkFilter: null,
            includeSelectTypeAndColor: false,
        );
        $count = $this->eventReader->count($school_id, $academic_year->id);
        $subscription = Subscription::where('school_id', $school_id)->first();

        $events = json_encode($events);

        return view('accountant.events.index', ['events' => $events, 'count' => $count, 'subscription' => $subscription]);
    }

    /**
     * Return standard list used by the events UI.
     *
     * @return array{
     *     standardlist: AnonymousResourceCollection
     * }
     */
    public function list()
    {
        $standard = StandardLink::with('standard', 'section')->where('school_id', Auth::user()->school_id)->get();
        $standard = StandardResource::collection($standard);

        $array = [];

        $array['standardlist'] = $standard;

        return $array;
    }

    /**
     * Build and return expanded events (including repeats) for calendar consumption.
     *
     * @return array<int, array>
     */
    public function events()
    {
        return $this->eventReader->expandedEvents(Auth::user()->school_id, $this->academic_year->id);
    }

    /**
     * Show a single event detail view.
     *
     * @param  int  $id
     * @return View
     */
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

            return view('accountant.events.detail', ['event' => $event, 'duration' => $duration, 'subject_name' => $subject_name]);
        }

        return view('accountant.events.show', ['event' => $event]);
    }

    /**
     * Return event details as API resource collection.
     *
     * @param  int  $id
     * @return AnonymousResourceCollection
     */
    public function showdetails($id)
    {
        return $this->eventReader->showDetails($id, Auth::user()->school_id);
    }

    /**
     * Return gallery images for an event as a resource collection.
     *
     * @param  int  $event_id
     * @return AnonymousResourceCollection
     */
    public function showimage($event_id)
    {
        return $this->eventReader->showImage($event_id, Auth::user()->school_id);
    }

    /**
     * Return event details array if authorized.
     *
     * @param  int  $id
     * @return array|
     *
     * @throws AccessDeniedHttpException
     */
    public function details($id)
    {
        return $this->eventReader->detailsForModal($id);
    }

    /*   public function destroy($id)
       {
         $event=Events::where('id',$id)->first();
         $event->delete();

          $message=('Event Deleted Successfully');

                       $ip= $this->getRequestIP();
                       $this->doActivityLog(
                           $member,
                           Auth::user(),
                           ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                           LOGNAME_DELETE_EVENT,
                           $message
                       );

                   return redirect()->back()->with(['successmessage' => 'Event Deleted Successfully']);
       }
*/

}
