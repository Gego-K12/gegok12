<?php

namespace App\Calendar;

use App\Events\PushEvent;
use App\Events\StandardPushEvent;
use App\Models\Events;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

/**
 * Class CalendarService
 *
 * Handles calendar event operations such as
 * creating, updating, fetching, and deleting events.
 */
class CalendarService
{
    /**
     * Test method to verify service binding.
     *
     * @return string
     */
    public function test()
    {
        return 'this works';
    }

    /**
     * Get all events for a given school and academic year.
     *
     * @param  int  $school_id
     * @param  int  $academic_year
     * @return Collection
     */
    public function events($school_id, $academic_year)
    {
        $events = Events::where([
            ['school_id', $school_id],
            ['academic_year_id', $academic_year],
        ])->get();

        return $events;
    }

    /**
     * Get countable events excluding holidays.
     *
     * @param  int  $school_id
     * @param  int  $academic_year
     * @return Collection
     */
    public function eventscount($school_id, $academic_year)
    {
        $events = Events::where([
            ['school_id', $school_id],
            ['academic_year_id', $academic_year],
            ['category', '!=', 'holidays'],
        ])->get();

        return $events;
    }

    /**
     * Create a new calendar event and trigger notifications.
     *
     * @param  int  $school_id
     * @param  int  $academic_year
     * @param  array|Request  $request
     * @return Events
     */
    public function createEvent($school_id, $academic_year, $request)
    {
        $events = Events::create($request);

        $executed_at = date(
            'Y-m-d',
            strtotime('-2 days', strtotime($events->start_date))
        );

        if ($events->select_type == 'class') {
            $data = [];

            $data['school_id'] = $school_id;
            $data['standard_id'] = $events->standard_id;
            $data['message'] = 'New Event created';
            $data['type'] = 'event';

            event(new StandardPushEvent($data));
        } else {
            $data = [];

            $data['school_id'] = $school_id;
            $data['message'] = 'New Event created';
            $data['type'] = 'event';

            event(new PushEvent($data));
        }

        return $events;
    }

    /**
     * Get a single event by ID and school.
     *
     * @param  int  $events_id
     * @param  int  $school_id
     * @return Events|null
     */
    public function getEvent($events_id, $school_id)
    {
        $event = Events::where([
            ['id', $events_id],
            ['school_id', $school_id],
        ])->first();

        return $event;
    }

    /**
     * Update an existing event and trigger notifications.
     *
     * @param  int  $event_id
     * @param  int  $school_id
     * @param  int  $academic_year
     * @param  Request  $request
     * @return Events
     */
    public function updateEvent($event_id, $school_id, $academic_year, $request)
    {
        $events = Events::where('id', $event_id)->first();

        $events->title = $request->title;
        $events->description = $request->description;
        $events->repeats = $request->repeats;

        if ($request->select_type == 'class') {
            $events->standard_id = $request->standard_id;
        }

        $events->freq = $request->freq;
        $events->freq_term = $request->freq_term;
        $events->location = $request->location;
        $events->category = $request->category;
        $events->organised_by = $request->organised_by;
        $events->start_date = date('Y-m-d H:i:s', strtotime($request->start_date));
        $events->end_date = date('Y-m-d H:i:s', strtotime($request->end_date));

        $events->save();

        if ($request->select_type == 'class') {
            $data = [];

            $data['school_id'] = $school_id;
            $data['standard_id'] = $request->standard_id;
            $data['message'] = 'Event updated';
            $data['type'] = 'event';

            event(new StandardPushEvent($data));
        } else {
            $data = [];

            $data['school_id'] = $school_id;
            $data['message'] = 'Event updated';
            $data['type'] = 'event';

            event(new PushEvent($data));
        }

        return $events;
    }

    /**
     * Delete an event by ID.
     *
     * @param  int  $id
     * @return Events
     */
    public function deleteEvent($id)
    {
        $event = Events::where('id', $id)->first();
        $event->delete();

        return $event;
    }
}
