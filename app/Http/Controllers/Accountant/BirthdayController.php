<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use App\Http\Resources\Birthday as BirthdayResource;
use App\Http\Resources\WorkAnniversary as WorkAnniversaryResource;
use App\Models\Smstemplate;
use App\Models\Userprofile;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Class BirthdayController
 *
 * Handles birthday and work-anniversary related features
 * for the accountant dashboard.
 *
 * Responsibilities:
 * - Fetch student birthdays
 * - Fetch teacher birthdays
 * - Fetch teacher work anniversaries
 * - Provide data for dashboard views
 * - Return API resource collections for UI consumption
 */
class BirthdayController extends Controller
{
    /**
     * Controller responsible for birthday and work-anniversary endpoints
     * used by the accountant dashboard.
     */

    /**
     * Return today's student birthdays as a resource collection.
     *
     * @return AnonymousResourceCollection
     */
    public function showBirthday()
    {
        $birthday = Userprofile::with('user')
            ->whereRaw("DATE_FORMAT(date_of_birth, '%m-%d') = DATE_FORMAT(now(),'%m-%d')")
            ->where('school_id', Auth::user()->school_id)
            ->ByRole(6)
            ->get();

        return BirthdayResource::collection($birthday);
    }

    /**
     * Return data for the birthday UI: list of users and SMS templates.
     *
     * @return array{
     *     birthdaylist: Collection,
     *     templatelist: Collection
     * }
     */
    public function birthdayUser()
    {
        $birthday = Userprofile::with('user')
            ->whereRaw("DATE_FORMAT(date_of_birth, '%m-%d') = DATE_FORMAT(now(),'%m-%d')")
            ->where('school_id', Auth::user()->school_id)
            ->ByRole(6)
            ->get();

        $templates = Smstemplate::where('name', 'birthday_message')->get();

        return [
            'birthdaylist' => $birthday,
            'templatelist' => $templates,
        ];
    }

    /**
     * Show the accountant birthday dashboard view.
     *
     * @return View
     */
    public function birthday()
    {
        return view('/accountant/dashboard/birthday');
    }

    /**
     * Return today's teacher birthdays as a resource collection.
     *
     * @return AnonymousResourceCollection
     */
    public function showBirthdayTeacher()
    {
        $birthday = Userprofile::with('user')
            ->whereRaw("DATE_FORMAT(date_of_birth, '%m-%d') = DATE_FORMAT(now(),'%m-%d')")
            ->where('school_id', Auth::user()->school_id)
            ->ByRole(5)
            ->get();

        return BirthdayResource::collection($birthday);
    }

    /**
     * Return data for the teacher birthday UI: list of teachers and templates.
     *
     * @return array{
     *     birthdaylist: Collection,
     *     templatelist: Collection
     * }
     */
    public function birthdayTeacher()
    {
        $birthday = Userprofile::with('user')
            ->whereRaw("DATE_FORMAT(date_of_birth, '%m-%d') = DATE_FORMAT(now(),'%m-%d')")
            ->where('school_id', Auth::user()->school_id)
            ->ByRole(5)
            ->get();

        $templates = Smstemplate::where('name', 'birthday_message')->get();

        return [
            'birthdaylist' => $birthday,
            'templatelist' => $templates,
        ];
    }

    /**
     * Show the accountant teacher-birthday creation view.
     *
     * @return View
     */
    public function birthdayCreate()
    {
        return view('/accountant/dashboard/birthdayTeacher');
    }

    /**
     * Return today's teacher work anniversaries as a resource collection.
     *
     * @return AnonymousResourceCollection
     */
    public function showWorkAnniversary()
    {
        $workanniversary = Userprofile::with('user')
            ->whereRaw("DATE_FORMAT(joining_date, '%m-%d') = DATE_FORMAT(now(),'%m-%d')")
            ->where('school_id', Auth::user()->school_id)
            ->ByRole(5)
            ->get();

        return WorkAnniversaryResource::collection($workanniversary);
    }

    /**
     * Return data for work-anniversary UI: list and templates.
     *
     * @return array{
     *     workanniversarylist: Collection,
     *     templatelist: Collection
     * }
     */
    public function workAnniversary()
    {
        $workanniversary = Userprofile::with('user')
            ->whereRaw("DATE_FORMAT(joining_date, '%m-%d') = DATE_FORMAT(now(),'%m-%d')")
            ->where('school_id', Auth::user()->school_id)
            ->ByRole(5)
            ->get();

        $templates = Smstemplate::where('name', 'work_anniversary_message')->get();

        return [
            'workanniversarylist' => $workanniversary,
            'templatelist' => $templates,
        ];
    }

    /**
     * Show the accountant work-anniversary creation view.
     *
     * @return View
     */
    public function workAnniversaryCreate()
    {
        return view('/accountant/dashboard/workAnniversary');
    }
}
