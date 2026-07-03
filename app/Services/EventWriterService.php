<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Services;

use App\Events\Notification\ClassNotificationEvent;
use App\Events\Notification\SchoolNotificationEvent;
use App\Events\PushEvent;
use App\Events\StandardPushEvent;
use App\Http\Requests\EventRequest;
use App\Models\Events;
use App\Traits\Common;
use App\Traits\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class EventWriterService
 *
 * Owns create/update/approve/reschedule/delete for events. Every lookup
 * of an existing event goes through EventReaderService::find(), which is
 * school-scoped - this is what fixes the cross-tenant edit/approve/
 * reschedule/delete bug that used to live directly in
 * Admin\EventsController (bare `Events::where('id', $id)`, no
 * `school_id` check).
 */
class EventWriterService
{
    use Common;
    use LogActivity;

    public function __construct(protected EventReaderService $eventReader) {}

    public function store(EventRequest $request, int $schoolId, int $academicYearId): array
    {
        $event = new Events;

        $event->school_id = $schoolId;
        $event->academic_year_id = $academicYearId;
        $event->select_type = $request->select_type;
        $event->title = $request->title;
        $event->description = $request->description;
        $event->repeats = $request->repeats;

        if ($request->select_type == 'class') {
            $event->standard_id = $request->standard_id;
        }

        if ($request->select_type == 'alumni') {
            $event->batch = $request->batch;
        }

        $event->freq = $request->freq;
        $event->location = $request->location;
        $event->category = $request->category;
        $event->organised_by = $request->organised_by;
        $event->start_date = date('Y-m-d H:i:s', strtotime($request->start_date));
        $event->end_date = date('Y-m-d H:i:s', strtotime($request->end_date));
        $event->color = $event->select_type == 'class' ? 'blue' : 'green';

        $event->save();

        $message = trans('messages.add_success_msg', ['module' => 'Event']);
        $this->logActivity($event, $message, LOGNAME_ADD_EVENT);

        return ['success' => $message];
    }

    /**
     * Returns null if no event with this id exists for this school -
     * the controller should treat that as a 404, not silently no-op.
     */
    /**
     * Takes a plain Request, not EventUpdateRequest, deliberately -
     * Admin\EventsController::update() always did too (its own signature
     * type-hinted plain Request), so EventUpdateRequest's validation
     * rules were never actually being enforced. Preserved as-is rather
     * than silently starting to enforce validation that wasn't before.
     */
    public function update(Request $request, int $id, int $schoolId): ?array
    {
        $event = $this->eventReader->find($id, $schoolId);

        if (! $event) {
            return null;
        }

        $event->title = $request->title;
        $event->description = $request->description;
        $event->repeats = $request->repeats;

        if ($request->select_type == 'class') {
            $event->standard_id = $request->standard_id;
        }

        if ($request->select_type == 'alumni') {
            $event->batch = $request->batch;
        }

        $event->freq = $request->freq;
        $event->freq_term = $request->freq_term;
        $event->location = $request->location;
        $event->category = $request->category;
        $event->organised_by = $request->organised_by;
        $event->start_date = date('Y-m-d H:i:s', strtotime($request->start_date));
        $event->end_date = date('Y-m-d H:i:s', strtotime($request->end_date));
        $event->color = $event->select_type == 'class' ? 'blue' : 'green';

        $event->save();

        // Note: select_type itself is never updated above (matching the
        // original controller code exactly, bug or not) - the color
        // assignment above reads the event's existing select_type, but
        // this notification was always branched on the request's
        // select_type instead. Preserved as two different conditions.
        $this->notifyAudience($event, $request->select_type, 'Event updated', 'notification.event_update_success_msg');

        $message = trans('messages.update_success_msg', ['module' => 'Event']);
        $this->logActivity($event, $message, LOGNAME_EDIT_EVENT);

        return ['success' => $message];
    }

    /**
     * Returns null if no event with this id exists for this school.
     */
    public function approve(int $id, int $schoolId): ?Events
    {
        $event = $this->eventReader->find($id, $schoolId);

        if (! $event) {
            return null;
        }

        $event->status = 'active';
        $event->save();

        $this->notifyAudience($event, $event->select_type, 'New Event created', 'notification.event_add_success_msg');

        return $event;
    }

    /**
     * Calendar drag/drop date change. Returns null if no event with this
     * id exists for this school.
     */
    public function reschedule(Request $request, int $id, int $schoolId): ?Events
    {
        $event = $this->eventReader->find($id, $schoolId);

        if (! $event) {
            return null;
        }

        if ($request->end_date == 'undefined') {
            $request['end_date'] = date('Y-m-d H:i:s', strtotime($request->start_date));
        }

        if ($request->start_date == $request->end_date) {
            $request['allDay'] = 1;
        }

        $event->fill($request->all());
        $event->save();

        return $event;
    }

    /**
     * Returns null if no event with this id exists for this school.
     */
    public function destroy(int $id, int $schoolId): ?array
    {
        $event = $this->eventReader->find($id, $schoolId);

        if (! $event) {
            return null;
        }

        $event->delete();

        $message = trans('messages.delete_success_msg', ['module' => 'Event']);
        $this->logActivity($event, $message, LOGNAME_DELETE_EVENT);

        return ['success' => $message];
    }

    private function logActivity(Events $event, string $message, string $logName): void
    {
        $ip = $this->getRequestIP();
        $this->doActivityLog(
            $event,
            Auth::user(),
            ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
            $logName,
            $message
        );
    }

    private function notifyAudience(Events $event, ?string $selectType, string $pushMessage, string $detailsKey): void
    {
        if ($selectType == 'class') {
            event(new StandardPushEvent([
                'school_id' => $event->school_id,
                'standard_id' => $event->standard_id,
                'message' => $pushMessage,
                'type' => 'event',
            ]));

            event(new ClassNotificationEvent([
                'school_id' => $event->school_id,
                'standardLink_id' => $event->standard_id,
                'details' => trans($detailsKey),
            ]));
        } else {
            event(new PushEvent([
                'school_id' => $event->school_id,
                'message' => $pushMessage,
                'type' => 'event',
            ]));

            event(new SchoolNotificationEvent([
                'school_id' => $event->school_id,
                'details' => trans($detailsKey),
            ]));
        }
    }
}
