<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Student;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\ShowEvent as ShowEventResource;
use App\Http\Resources\ShowEventGallery as ShowEventGalleryResource;
use App\Models\EventGallery;
use App\Models\Events;
use App\Models\Subscription;
use App\Traits\Common;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class EventsController extends Controller
{
    use Common;

    public function __construct()
    {
        $this->academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
        // $this->academic_year=$this->academic_year->id;
    }

    public function index()
    {
        $school_id = Auth::user()->school_id;
        $academic_year = $this->academic_year;

        /* $events         =   Events::where([['school_id',$school_id],['academic_year_id',$academic_year->id],['standard_id',$standardLink_id]])->orWhere('standard_id',NULL)->get(); */
        $events = Events::where([['school_id', $school_id], ['academic_year_id', $academic_year->id]]);
        // new
        if (! config('gexam.enabled', false)) {
            $events = $events->where('category', '!=', 'exam');
        }
        $events = $events->get();
        $count = Events::where([['school_id', $school_id], ['academic_year_id', $academic_year->id], ['category', '!=', 'holidays']])->count();
        $subscription = Subscription::where('school_id', $school_id)->first();

        $events = $events->map(function ($event, $key) {
            $eventData = [
                'id' => $event->id,
                'title' => $event->title,
                // 'start'=>  $event->start_date->format('Y-m-d').'T'.$event->start_date->format('H:i:s'),
                // 'end'=> $event->end_date->format('Y-m-d').'T'.$event->end_date->format('H:i:s'),

                'start' => date('Y-m-d', strtotime($event->start_date)).'T'.date('H:i:s', strtotime($event->start_date)),
                'end' => date('Y-m-d', strtotime($event->end_date)).'T'.date('H:i:s', strtotime($event->end_date)),
                'allDay' => $event->allDay,
            ];

            return $eventData;
        });
        $events = json_encode($events);

        return view('student.events.index', ['events' => $events, 'count' => $count, 'subscription' => $subscription]);
    }

    public function events()
    {
        //
        $school_id = Auth::user()->school_id;
        $academic_year = $this->academic_year;

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
            'repeats_class' => $repeats_class,
        ];
    }

    /**
     * single day task
     *
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
     * repeating tasks even (n) days. Note if you can even put 7 days to make them weekly.
     *
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
     * Weekly events repeating every (n) weeks
     *
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
     * Monthly events repeating every (n) months
     *
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
     * yearly repeating events repeats every (n) years
     *
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

    public function show($id)
    {
        $event = Events::where('id', $id)->first();

        /* if($event->category != 'holidays')
         {*/
        $exam = Exam::where('name', $event->title)->where('standard_id', $event->standard_id)->first();

        $schedule = ExamSchedule::where('exam_id', $exam->id)->first();
        $subject = Subject::where('id', $schedule->subject_id)->first();
        // $start=$event->start_date;
        $subject_name = $subject->name;
        $start = Carbon::createFromFormat('Y-m-d H:i:s', $event->start_date);

        $end = Carbon::createFromFormat('Y-m-d H:i:s', $event->end_date);
        $diff_in_hours = $end->diffInHours($start);

        $duration = $diff_in_hours * 60;
        if ($event->category == 'exam') {
            return view('student.events.detail', ['event' => $event, 'duration' => $duration, 'subject_name' => $subject_name]);
        } else {
            return view('student.events.show', ['event' => $event]);
        }
        /* }
         else
         {
           abort(403);
         } */
    }

    public function showdetails($id)
    {
        $event = Events::where([['id', $id], ['school_id', Auth::user()->school_id]])->get();
        $event = ShowEventResource::collection($event);

        return $event;
    }

    public function showimage($event_id)
    {
        $event = EventGallery::where([['event_id', $event_id], ['school_id', Auth::user()->school_id]])->get();

        $event = ShowEventGalleryResource::collection($event);

        return $event;
    }

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
