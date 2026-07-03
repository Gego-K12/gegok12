<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Services;

use App\Http\Resources\EditEvent as EditEventResource;
use App\Http\Resources\ShowEvent as ShowEventResource;
use App\Http\Resources\ShowEventGallery as ShowEventGalleryResource;
use App\Models\EventGallery;
use App\Models\Events;
use App\Models\StandardLink;
use App\Models\Teacherlink;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;

/**
 * Class EventReaderService
 *
 * Owns event read/display logic shared by Admin, Accountant, Teacher,
 * Student, and Receptionist dashboards. Mobile-app reads (Api and
 * Api\Teacher EventsControllers) are covered by the mobile* methods
 * below - those use different Resource classes per app, so unlike the
 * web methods they return raw query results for each controller to wrap
 * itself.
 *
 * Several web-dashboard behaviors genuinely differ per role today (which
 * fields index() returns, whether the exam-module gate applies, whether
 * a class filter is supported) - this service preserves each role's
 * exact current behavior via explicit parameters rather than silently
 * unifying them. That's a separate product decision, tracked in the
 * todo list.
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

    /**
     * Count of non-holiday events for a school/year - used alongside the
     * calendar widget's event list.
     */
    public function count(int $schoolId, int $academicYearId): int
    {
        return Events::where('school_id', $schoolId)
            ->where('academic_year_id', $academicYearId)
            ->where('category', '!=', 'holidays')
            ->count();
    }

    /**
     * Mapped event list for the calendar widget's index() page.
     *
     * $applyGexamGate, $standardLinkFilter, and $includeSelectTypeAndColor
     * preserve each role's current, genuinely different behavior (Admin
     * supports a class filter and includes select_type/color; Teacher/
     * Student gate on the exam module but don't; Accountant/Receptionist
     * don't gate at all) rather than guessing at a single "correct" shape.
     */
    public function calendarEvents(
        int $schoolId,
        int $academicYearId,
        bool $applyGexamGate,
        ?int $standardLinkFilter,
        bool $includeSelectTypeAndColor
    ): array {
        $query = Events::where('school_id', $schoolId)->where('academic_year_id', $academicYearId);

        if ($standardLinkFilter) {
            $query->where('standard_id', $standardLinkFilter);
        }

        if ($applyGexamGate && ! config('gexam.enabled', false)) {
            $query->where('category', '!=', 'exam');
        }

        return $query->get()->map(function (Events $event) use ($includeSelectTypeAndColor) {
            $data = [
                'id' => $event->id,
                'title' => $event->title,
                'start' => date('Y-m-d', strtotime($event->start_date)).'T'.date('H:i:s', strtotime($event->start_date)),
                'end' => date('Y-m-d', strtotime($event->end_date)).'T'.date('H:i:s', strtotime($event->end_date)),
                'allDay' => $event->allDay,
            ];

            if ($includeSelectTypeAndColor) {
                $data['select_type'] = $event->select_type;
                $data['color'] = $event->color;
            }

            return $data;
        })->all();
    }

    /**
     * Full expansion of events into calendar occurrences, including
     * repeats. $includeColor matches Admin's getEvent() output, which
     * uniquely includes a color key the other 4 dashboards' copies don't.
     */
    public function expandedEvents(int $schoolId, int $academicYearId, bool $includeColor = false): array
    {
        $events = Events::where('school_id', $schoolId)->where('academic_year_id', $academicYearId)->get();

        $items = [];

        foreach ($events as $event) {
            if ($event->repeats == 1) {
                $items = array_merge($items, match ($event->freq_term) {
                    'day' => $this->getDailyTasks($event, $includeColor),
                    'week' => $this->getWeeklyTasks($event, $includeColor),
                    'month' => $this->getMonthlyTasks($event, $includeColor),
                    'year' => $this->getYearlyTasks($event, $includeColor),
                    default => [],
                });
            } else {
                $items = array_merge($items, $this->getDayTask($event, $includeColor));
            }
        }

        return $items;
    }

    /**
     * Event details as an API resource collection, scoped to a school.
     */
    public function showDetails(int $id, int $schoolId): AnonymousResourceCollection
    {
        $event = Events::where('id', $id)->where('school_id', $schoolId)->get();

        return ShowEventResource::collection($event);
    }

    /**
     * Gallery images for an event, scoped to a school.
     */
    public function showImage(int $eventId, int $schoolId): AnonymousResourceCollection
    {
        $images = EventGallery::where('event_id', $eventId)->where('school_id', $schoolId)->get();

        return ShowEventGalleryResource::collection($images);
    }

    /**
     * Modal detail data, gated by the "event" Gate (which itself checks
     * school_id match, so this is safe against cross-tenant access even
     * though the initial lookup isn't scoped - matches the original).
     */
    public function detailsForModal(int $id): array
    {
        $event = Events::where('id', $id)->first();

        if (! Gate::allows('event', $event)) {
            abort(403);
        }

        if ($event->category == 'holidays') {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'start_date' => date('d-F-Y', strtotime($event->start_date)),
                'end_date' => $event->end_date,
                'category' => $event->category,
            ];
        }

        $array = [
            'id' => $event->id,
            'select_type' => $event->select_type,
            'title' => $event->title,
            'description' => $event->description,
            'repeats' => $event->repeats,
        ];

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

        return $array;
    }

    /**
     * Standard IDs a teacher's classes correspond to, for the Teacher
     * app's "my class events" screen. Mirrors
     * NoticeBoardReaderService::standardLinksForTeacher() - both include
     * classes the teacher is the class teacher for, not just ones they
     * teach a subject in.
     *
     * @return array<int>
     */
    public function standardLinksForTeacher(int $schoolId, int $academicYearId, int $teacherId): array
    {
        $classTeacherOf = StandardLink::where('school_id', $schoolId)
            ->where('academic_year_id', $academicYearId)
            ->where('class_teacher_id', $teacherId)
            ->pluck('id')
            ->toArray();

        $subjectTeacherOf = Teacherlink::where('school_id', $schoolId)
            ->where('academic_year_id', $academicYearId)
            ->where('teacher_id', $teacherId)
            ->pluck('standardLink_id')
            ->toArray();

        return array_values(array_unique(array_merge($classTeacherOf, $subjectTeacherOf)));
    }

    /**
     * Single event lookup for the mobile apps' show($id) - scoped to
     * school and academic year, excludes holidays. Returns a Collection
     * (0 or 1 items) rather than a nullable model since that's the shape
     * both mobile controllers wrap in their own Resource::collection().
     */
    public function mobileFind(int $id, int $schoolId, int $academicYearId): Collection
    {
        return Events::where('id', $id)
            ->where('school_id', $schoolId)
            ->where('academic_year_id', $academicYearId)
            ->where('category', '!=', 'holidays')
            ->get();
    }

    public function mobileIndex(int $schoolId, int $academicYearId): Collection
    {
        return Events::where('school_id', $schoolId)
            ->where('academic_year_id', $academicYearId)
            ->where('category', '!=', 'holidays')
            ->get();
    }

    /**
     * $requireActiveStatus preserves a genuine difference: the Parent
     * app's "upcoming" only shows approved (status=active) events, the
     * Teacher app's doesn't.
     */
    public function mobileUpcoming(int $schoolId, int $academicYearId, bool $requireActiveStatus): Collection
    {
        $query = Events::where('school_id', $schoolId)
            ->where('academic_year_id', $academicYearId)
            ->where('end_date', '>=', Carbon::now())
            ->where('category', '!=', 'holidays');

        if ($requireActiveStatus) {
            $query->where('status', 'active');
        }

        return $query->get();
    }

    public function mobilePast(int $schoolId, int $academicYearId): Collection
    {
        return Events::has('eventgallery', '>', 0)
            ->where('school_id', $schoolId)
            ->where('academic_year_id', $academicYearId)
            ->where('end_date', '<', Carbon::now())
            ->where('category', '!=', 'holidays')
            ->where('category', '!=', 'exam')
            ->get();
    }

    public function mobileSchoolWide(int $schoolId, int $academicYearId): Collection
    {
        return Events::where('school_id', $schoolId)
            ->where('academic_year_id', $academicYearId)
            ->where('category', '!=', 'holidays')
            ->where('select_type', 'school')
            ->get();
    }

    /**
     * Class-scoped events for one or more StandardLink ids - used by both
     * the Parent app (a single class, the student's own) and the Teacher
     * app (potentially several classes the teacher is linked to).
     *
     * @param  array<int>  $standardLinkIds
     */
    public function mobileForClasses(int $schoolId, int $academicYearId, array $standardLinkIds): Collection
    {
        return Events::whereIn('standard_id', $standardLinkIds)
            ->where('school_id', $schoolId)
            ->where('academic_year_id', $academicYearId)
            ->where('select_type', 'class')
            ->where('category', '!=', 'holidays')
            ->get();
    }

    private function getEvent(Events $event, Carbon $start, Carbon $end, bool $includeColor): array
    {
        $data = [
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
        ];

        if ($includeColor) {
            $data['color'] = $event->color;
        }

        $data['repeats_class'] = $event->repeats == 1 ? 'repeats_class' : 'repeatsclass';

        return $data;
    }

    private function getDayTask(Events $event, bool $includeColor): array
    {
        $end = Carbon::parse($event->end_date);
        $start = Carbon::parse($event->start_date);

        return [$this->getEvent($event, $start, $end, $includeColor)];
    }

    private function getDailyTasks(Events $event, bool $includeColor): array
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

            $events[] = $this->getEvent($event, $date, $date, $includeColor);
            $date = Carbon::parse($date)->addDays($event->freq);
        }

        return $events;
    }

    private function getWeeklyTasks(Events $event, bool $includeColor): array
    {
        $end = Carbon::parse($event->end_date);
        $start = Carbon::parse($event->start_date);
        $weeks = $end->diffInWeeks($start);

        $events = [];
        $date = $start;
        for ($i = 1; $i <= $weeks + 1; $i++) {
            if ($event->status == 'completed') {
                continue;
            }

            $events[] = $this->getEvent($event, $date, $date, $includeColor);
            $date = Carbon::parse($date)->addWeeks($event->freq);
        }

        return $events;
    }

    /**
     * Uses diffInWeeks, not diffInMonths, to compute the occurrence count
     * - matches the original bug identically across all 5 controllers
     * this replaces. Not fixed here since that would change recurring
     * monthly event behavior as a side effect of a pure dedup pass.
     */
    private function getMonthlyTasks(Events $event, bool $includeColor): array
    {
        $end = Carbon::parse($event->end_date);
        $start = Carbon::parse($event->start_date);
        $months = $end->diffInWeeks($start);

        $events = [];
        $date = $start;
        for ($i = 1; $i <= $months + 1; $i++) {
            if ($event->status == 'completed') {
                continue;
            }

            $events[] = $this->getEvent($event, $date, $date, $includeColor);
            $date = Carbon::parse($date)->addMonths($event->freq);
        }

        return $events;
    }

    private function getYearlyTasks(Events $event, bool $includeColor): array
    {
        $end = Carbon::parse($event->end_date);
        $start = Carbon::parse($event->start_date);
        $years = $end->diffInYears($start);

        $events = [];
        $date = $start;
        for ($i = 1; $i <= $years + 1; $i++) {
            if ($event->status == 'completed') {
                continue;
            }

            $events[] = $this->getEvent($event, $date, $date, $includeColor);
            $date = Carbon::parse($date)->addYears($event->freq);
        }

        return $events;
    }
}
