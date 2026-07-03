<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Services;

use App\Http\Resources\EditEvent as EditEventResource;
use App\Models\Events;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Class EventReaderService
 *
 * Owns school-scoped single-event lookups for Admin\EventsController.
 * Every one of these methods used to be a bare `Events::where('id', $id)`
 * with no `school_id` check, letting an admin from one school view, edit,
 * approve, reschedule, or delete another school's event by guessing its
 * id. Centralizing the lookup here means that fix only has to be right
 * once.
 *
 * This does not (yet) cover the read-side duplication shared across the
 * other 4 role dashboards (events()/showdetails()/showimage()/details())
 * - that's a separate follow-up, tracked in the todo list.
 */
class EventReaderService
{
    public function find(int $id, int $schoolId): ?Events
    {
        return Events::where('id', $id)->where('school_id', $schoolId)->first();
    }

    /**
     * Matches Admin\EventsController::edit()'s original behavior:
     * holidays are excluded (edited via HolidaysController instead) and
     * the result stays a resource collection (0 or 1 items) rather than
     * a single resource, since that's the shape the edit-form JS expects.
     */
    public function findForEdit(int $id, int $schoolId): AnonymousResourceCollection
    {
        $event = Events::where('id', $id)
            ->where('school_id', $schoolId)
            ->where('category', '!=', 'holidays')
            ->get();

        return EditEventResource::collection($event);
    }
}
