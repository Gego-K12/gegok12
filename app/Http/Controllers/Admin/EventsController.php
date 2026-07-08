<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Http\Requests\EventUpdateRequest;
use App\Http\Resources\ShowEvent as ShowEventResource;
use App\Http\Resources\ShowEventGallery as ShowEventGalleryResource;
use App\Models\EventGallery;
use App\Models\Events;
use App\Models\Exam;
use App\Models\ExamSchedule;
use App\Models\SchoolDetail;
use App\Models\Subject;
use App\Models\Subscription;
use App\Services\EventReaderService;
use App\Services\EventWriterService;
use App\Traits\Common;
use App\Traits\EventProcess;
use App\Traits\LogActivity;
use App\Traits\ReminderProcess;
use App\Traits\SendPushNotification;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Log;

/**
 * Class EventsController
 *
 * Handles creation, update, approval, listing, calendar rendering,
 * notifications, and recurring logic for school events.
 */
class EventsController extends Controller
{
    use Common;
    use EventProcess;
    use LogActivity;
    use ReminderProcess;
    use SendPushNotification;

    public function __construct(
        protected EventReaderService $eventReader,
        protected EventWriterService $eventWriter
    ) {}

    /**
     * Display the event calendar page.
     *
     * @return View
     */
    public function index(Request $request)
    {
        //
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);
        $events = Events::where([['school_id', $school_id], ['academic_year_id', $academic_year->id]]); // ,['status','active']
        $count = Events::where([['school_id', $school_id], ['academic_year_id', $academic_year->id], ['category', '!=', 'holidays']])->count(); // ,['status','active']
        $subscription = Subscription::where('school_id', $school_id)->first();

        if (count((array) \Request::getQueryString()) > 0) {
            if ($request->standardLink_id != '') {
                $events = $events->where('standard_id', $request->standardLink_id);
            }
        }

        // new
        if (! config('gexam.enabled', false)) {
            $events = $events->where('category', '!=', 'exam');
        }

        $events = $events->get();

        $events = $events->map(function ($event, $key) {
            $eventData = [
                'id' => $event->id,
                'title' => $event->title,
                'start' => date('Y-m-d', strtotime($event->start_date)).'T'.date('H:i:s', strtotime($event->start_date)),
                'end' => date('Y-m-d', strtotime($event->end_date)).'T'.date('H:i:s', strtotime($event->end_date)),
                'allDay' => $event->allDay,
                'select_type' => $event->select_type,
                'color' => $event->color,
            ];

            return $eventData;
        });
        $events = json_encode($events);

        $standard = $request->standardLink_id;

        return view('admin.events.index', ['events' => $events, 'count' => $count, 'subscription' => $subscription, 'standard' => $standard]);
    }

    /**
     * Fetch standard list and academic year range for event filters.
     *
     * @return array
     */
    public function list()
    {
        $school = SchoolDetail::where('school_id', Auth::user()->school_id)->where('meta_key', 'date_of_establishment')->first();

        // $end_date = Carbon::parse($school['meta_value'])->format('Y');
        $end_date = date('Y');

        $start_date = date('Y');

        $array = [];

        $array['standardlist'] = SiteHelper::getStandardLinkList(Auth::user()->school_id);
        $array['start'] = $start_date;
        $array['end'] = $end_date;

        return $array;
    }

    /**
     * Store a newly created event.
     *
     * @return array|null
     */
    public function store(EventRequest $request)
    {
        try {
            $school_id = Auth::user()->school_id;
            $academic_year = SiteHelper::getAcademicYear($school_id);

            return $this->eventWriter->store($request, $school_id, $academic_year->id);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Fetch event data for editing.
     *
     * @param  int  $id
     * @return AnonymousResourceCollection
     */
    public function edit($id)
    {
        return $this->eventReader->findForEdit($id, Auth::user()->school_id);
    }

    public function validateedit(EventUpdateRequest $request)
    {
        //
    }

    /**
     * Update an existing event.
     *
     * @param  int  $id
     * @return array|null
     */
    public function update(Request $request, $id)
    {
        $school_id = Auth::user()->school_id;

        if (! $this->eventReader->find($id, $school_id)) {
            abort(404);
        }

        try {
            return $this->eventWriter->update($request, $id, $school_id);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Approve an event and trigger notifications.
     *
     * @param  int  $id
     * @return RedirectResponse
     */
    public function eventapprove($id)
    {
        $event = $this->eventWriter->approve($id, Auth::user()->school_id);

        if (! $event) {
            abort(404);
        }

        return redirect('/admin/dashboard')->with('successmessage', 'Event has been approved successfully');
    }

    /**
     * Update event dates via calendar drag/drop.
     *
     * @param  int  $id
     * @return void
     */
    public function changeevent(Request $request, $id)
    {
        $event = $this->eventWriter->reschedule($request, $id, Auth::user()->school_id);

        if (! $event) {
            abort(404);
        }

        echo json_encode(['status' => 'Event has been update']);
    }

    /**
     * Delete an event.
     *
     * @param  int  $id
     * @return RedirectResponse|null
     */
    public function destroy($id)
    {
        $school_id = Auth::user()->school_id;

        if (! $this->eventReader->find($id, $school_id)) {
            abort(404);
        }

        try {
            $result = $this->eventWriter->destroy($id, $school_id);

            return redirect('/admin/events')->with('successmessage', $result['success']);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Fetch all events including repeated occurrences.
     *
     * @return array
     */
    public function events()
    {
        //
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);

        $events = Events::where([['school_id', $school_id], ['academic_year_id', $academic_year->id]])->get();

        $items = [];

        foreach ($events as $event) {
            if ($event->repeats == 1) {
                // create multiple entries for repeating events
                // count days from start to end and repeat
                if ($event->freq_term == 'day') {
                    foreach ($this->getDailyTasks($event) as $s) {
                        array_push($items, $s);
                    }
                }

                if ($event->freq_term == 'week') {
                    foreach ($this->getWeeklyTasks($event) as $s) {
                        array_push($items, $s);
                    }
                }

                if ($event->freq_term == 'month') {
                    foreach ($this->getMonthlyTasks($event) as $s) {
                        array_push($items, $s);
                    }
                }

                if ($event->freq_term == 'year') {
                    foreach ($this->getYearlyTasks($event) as $s) {
                        array_push($items, $s);
                    }
                }
            } else {
                foreach ($this->getDayTask($event) as $s) {
                    array_push($items, $s);
                }
            }
        }

        return $items;
    }

    /**
     * Build a single calendar event structure.
     *
     * @param  Events  $event
     * @param  Carbon  $start
     * @param  Carbon  $end
     * @return array
     */
    public function getEvent($event, $start, $end)
    {
        $repeats_class = 'repeatsclass';
        if ($event->repeats == 1) {
            $repeats_class = 'repeats_class';
        }

        return [
            'id' => (int) $event->id,
            'school_id' => $event->school_id,
            'academic_year_id' => $event->academic_year_id,
            'select_type' => $event->select_type,
            'title' => $event->title,
            'description' => $event->description,
            'repeats' => $event->repeats,
            'standard_id' => $event->standard_id,
            'freq' => $event->freq,
            'freq_term' => $event->freq_term,
            'location' => $event->location,
            'category' => $event->category,
            'organised_by' => $event->organised_by,
            'image' => $event->image,
            'start' => $start->format('Y-m-d H:i:s'),
            'end' => $end->format('Y-m-d H:i:s'),
            'color' => $event->color,
            'repeats_class' => $repeats_class,
        ];
    }

    /**
     * Get non-repeating single-day event.
     *
     * @param  Events  $event
     * @return array
     */
    public function getDayTask($event)
    {
        $end = Carbon::parse($event->end_date);
        $start = Carbon::parse($event->start_date);

        $events[] = $this->getEvent($event, $start, $end);

        return $events;
    }

    /**
     * Generate daily repeating events.
     *
     * @param  Events  $event
     * @return array
     */
    public function getDailyTasks($event)
    {
        $end = Carbon::parse($event->end_date);
        $start = Carbon::parse($event->start_date);

        $days = $end->diffInDays($start);

        $events = [];
        $date = $start;
        for ($i = 1; $i <= $days + 1; $i++) {
            if ($event->status == 'completed') {
                continue;
            }

            $events[] = $this->getEvent($event, $date, $date);
            $date = Carbon::parse($date)->addDays($event->freq);

        }

        return $events;
    }

    /**
     * Generate weekly repeating events.
     *
     * @param  Events  $event
     * @return array
     */
    public function getWeeklyTasks($event)
    {
        $end = Carbon::parse($event->end_date);
        $start = Carbon::parse($event->start_date);

        $weeks = $end->diffInWeeks($start);

        $events = [];
        $date = $start;
        for ($i = 1; $i <= $weeks + 1; $i++) {
            // skip completed.
            if ($event->status == 'completed') {
                continue;
            }

            $events[] = $this->getEvent($event, $date, $date);
            $date = Carbon::parse($date)->addWeeks($event->freq);
        }

        return $events;

    }

    /**
     * Generate monthly repeating events.
     *
     * @param  Events  $event
     * @return array
     */
    public function getMonthlyTasks($event)
    {
        $end = Carbon::parse($event->end_date);
        $start = Carbon::parse($event->start_date);

        $months = $end->diffInWeeks($start);

        $events = [];
        $date = $start;
        // daily tasks
        for ($i = 1; $i <= $months + 1; $i++) {
            // skip completed.
            if ($event->status == 'completed') {
                continue;
            }

            $events[] = $this->getEvent($event, $date, $date);
            $date = Carbon::parse($date)->addMonths($event->freq);
        }

        return $events;
    }

    /**
     * Generate yearly repeating events.
     *
     * @param  Events  $event
     * @return array
     */
    public function getYearlyTasks($event)
    {
        $end = Carbon::parse($event->end_date);
        $start = Carbon::parse($event->start_date);

        $years = $end->diffInYears($start);

        $events = [];
        $date = $start;
        // daily tasks
        for ($i = 1; $i <= $years + 1; $i++) {
            // skip completed.
            if ($event->status == 'completed') {
                continue;
            }

            $events[] = $this->getEvent($event, $date, $date);
            $date = Carbon::parse($date)->addYears($event->freq);
        }

        return $events;
    }

    /**
     * Display event detail page.
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

        $now = date('Y-m-d H:i:s');

        // if($event->category != 'holidays')
        // {
        //     $exam=Exam::where('name',$event->title)->where('standard_id',$event->standard_id)->first();

        //     $schedule=ExamSchedule::where('exam_id',$exam->id)->first();
        //     $subject=Subject::where('id',$schedule->subject_id)->first();
        //     //$start=$event->start_date;
        //     $subject_name=$subject->name;
        //     $start=\Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$event->start_date);

        //     $end=\Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$event->end_date);
        //     $diff_in_hours = $end->diffInHours($start);

        //     $duration=$diff_in_hours*60;
        //     if($event->category=='exam')
        //     {
        //         return view('admin.events.detail',['event'=>$event,'duration'=>$duration,'subject_name'=>$subject_name,'now'=>$now]);
        //     }
        //     else
        //     {
        //         return view('admin.events.show',['event'=>$event,'now'=>$now]);
        //     }
        // }
        if ($event->category != 'holidays') {

            if ($event->category == 'exam') {
                // Added in if condition
                if (class_exists('Gegok12\Exam\Models\Exam')) {
                    $exam = \Gegok12\Exam\Models\Exam::where('name', $event->title)->where('standard_id', $event->standard_id)->first();
                    $schedule = \Gegok12\Exam\Models\ExamSchedule::where('exam_id', $exam->id)->first();
                } else {
                    $exam = Exam::where('name', $event->title)->where('standard_id', $event->standard_id)->first();
                    $schedule = ExamSchedule::where('exam_id', $exam->id)->first();
                }

                $subject = Subject::where('id', $schedule->subject_id)->first();
                // $start=$event->start_date;
                $subject_name = $subject->name;
                $start = Carbon::createFromFormat('Y-m-d H:i:s', $event->start_date);

                $end = Carbon::createFromFormat('Y-m-d H:i:s', $event->end_date);
                $diff_in_hours = $end->diffInHours($start);

                $duration = $diff_in_hours * 60;

                // end
                return view('admin.events.detail', ['event' => $event, 'duration' => $duration, 'subject_name' => $subject_name, 'now' => $now]);
            } else {
                return view('admin.events.show', ['event' => $event, 'now' => $now]);
            }
        } else {
            abort(403);
        }
    }

    /**
     * Show event details as API resource.
     *
     * @param  int  $id
     * @return AnonymousResourceCollection
     */
    public function showdetails($id)
    {
        $event = Events::where([['id', $id], ['school_id', Auth::user()->school_id]])->get();
        $event = ShowEventResource::collection($event);

        return $event;
    }

    /**
     * Show event gallery images.
     *
     * @param  int  $event_id
     * @return AnonymousResourceCollection
     */
    public function showimage($event_id)
    {
        $event = EventGallery::where([['event_id', $event_id], ['school_id', Auth::user()->school_id]])->get();

        $event = ShowEventGalleryResource::collection($event);

        return $event;
    }

    /**
     * Fetch event details for modal display with permission check.
     *
     * @param  int  $id
     * @return array
     */
    public function details($id)
    {
        $event = Events::where('id', $id)->first();

        if (Gate::allows('event', $event)) {
            $array = [];
            if ($event->category == 'holidays') {
                $array['id'] = $event->id;
                $array['title'] = $event->title;
                $array['start_date'] = date('d-F-Y', strtotime($event->start_date));
                $array['end_date'] = $event->end_date;
                $array['category'] = $event->category;
            } else {
                $array['id'] = $event->id;
                $array['select_type'] = $event->select_type;
                $array['title'] = $event->title;
                $array['description'] = $event->description;
                $array['repeats'] = $event->repeats;
                if ($array['repeats'] == 'yes') {
                    $array['freq'] = $event->freq;
                    $array['freq_term'] = $event->freq_term;
                }
                $array['standard_id'] = $event->standardlink->StandardSection;
                $array['location'] = $event->location;
                $array['category'] = $event->category;
                $array['organised_by'] = $event->organised_by;
                $array['image'] = $event->ImagePath;
                $array['start_date'] = date('d-F-Y', strtotime($event->start_date));
                $array['end_date'] = $event->end_date;
            }

            return $array;
        } else {
            abort(403);
        }
    }
}
