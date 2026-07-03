<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Api\Teacher;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\Teacher\ShowEvent as ShowEventResource;
use App\Http\Resources\API\Teacher\ShowHoliday as ShowHolidayResource;
use App\Services\EventReaderService;
use App\Services\HolidaysReaderService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventsController extends Controller
{
    public function __construct(
        protected EventReaderService $eventReader,
        protected HolidaysReaderService $holidaysReader
    ) {}

    public function show($id)
    {
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);

        return ShowEventResource::collection(
            $this->eventReader->mobileFind($id, $school_id, $academic_year->id)
        );
    }

    public function index()
    {
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);

        return ShowEventResource::collection(
            $this->eventReader->mobileIndex($school_id, $academic_year->id)
        );
    }

    public function upcoming(Request $request)
    {
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);

        return ShowEventResource::collection(
            $this->eventReader->mobileUpcoming($school_id, $academic_year->id, requireActiveStatus: false)
        );
    }

    public function showpast()
    {
        try {
            $school_id = Auth::user()->school_id;
            $academic_year = SiteHelper::getAcademicYear($school_id);
            $pastevent = ShowEventResource::collection(
                $this->eventReader->mobilePast($school_id, $academic_year->id)
            );

            return response()->json([
                'success' => true,
                'message' => 'Past Event List',
                'data' => $pastevent,
            ], 200);

        } catch (Exception $e) {
        }
    }

    public function school()
    {
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);

        return ShowEventResource::collection(
            $this->eventReader->mobileSchoolWide($school_id, $academic_year->id)
        );
    }

    /**
     * Previously resolved a teacher's classes via
     * pluck('standardLink.standard_id') - the *grade* id off the related
     * StandardLink, not the StandardLink id itself that Events.standard_id
     * actually stores, and only checked subject-teacher assignments (not
     * classes the teacher is the class teacher for). Now shares the same
     * correct resolution the web dashboard and NoticeBoardReaderService use.
     */
    public function class($teacher_id)
    {
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);

        $standardLinks = $this->eventReader->standardLinksForTeacher($school_id, $academic_year->id, $teacher_id);

        $classevent = ShowEventResource::collection(
            $this->eventReader->mobileForClasses($school_id, $academic_year->id, $standardLinks)
        );

        return response()->json([
            'success' => true,
            'message' => 'Class Event List',
            'data' => $classevent,
        ], 200);
    }

    public function holidaylist()
    {
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);

        $holiday = ShowHolidayResource::collection(
            $this->holidaysReader->list($school_id, $academic_year->id)
        );

        return response()->json([
            'success' => true,
            'message' => 'Holiday list',
            'data' => $holiday,
            'count' => count($holiday),
        ], 200);
    }
}
