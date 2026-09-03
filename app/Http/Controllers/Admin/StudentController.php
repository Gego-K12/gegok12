<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Standard;
use App\Models\StandardLink;
use App\Models\StudentAcademic;
use App\Models\StudentParentLink;
use App\Models\Subscription;
use App\Models\Userprofile;
use App\Models\Users\StudentUser;
use App\Traits\Common;
use App\Traits\LogActivity;
use App\Traits\MemberProcess;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

/**
 * Class StudentController
 *
 * Handles student management including
 * listing, creation, update, deletion,
 * attendance, academic mapping, and
 * blocked student handling.
 */
class StudentController extends Controller
{
    use Common;
    use LogActivity;
    use MemberProcess;

    /**
     * Filter and fetch students list.
     *
     * Applies standard-based default filtering
     * and returns filtered student collection.
     *
     * @return mixed
     */
    public function find(Request $request)
    {
        // Default to names starting with A (all classes) so a bare request
        // never loads the whole unpaginated student list.
        if (count((array) \Request::getQueryString()) == 0) {
            $request['alphabet'] = 'A';
        }

        return $this->MemberFilter($request, Auth::user()->school_id, 6, 'active');
    }

    /**
     * Display student listing page.
     *
     * @return Response
     */
    public function index()
    {
        // Default view is all classes filtered to names starting with A --
        // the list is unpaginated, so an unfiltered load is too heavy.
        if (count((array) \Request::getQueryString()) == 0) {
            return redirect(url()->current().'?alphabet=A');
        }

        $school_id = Auth::user()->school_id;
        $count = StudentUser::ByRole(6)->where('school_id', $school_id)->where('deleted_at', null)->count();
        $alphabet = request('alphabet') ? request('alphabet') : '';
        $query = \Request::getQueryString();
        $standardLink = SiteHelper::getStandardLinkList($school_id);

        $birthday = request('date_of_birth') != null ? 'true' : false;

        $selected_standard = request('standard') != null ? request('standard') : '';

        return view('/admin/member/index', ['alphabet' => $alphabet, 'query' => $query, 'count' => $count, 'standardLinks' => $standardLink, 'standard' => $selected_standard, 'birthday' => $birthday, 'selected_standard' => $selected_standard]);
    }

    /**
     * Show student creation form.
     *
     * @return Response
     */
    public function create()
    {
        //
        $count = StudentUser::where('school_id', Auth::user()->school_id)->where('usergroup_id', 6)->count();
        $subscription = Subscription::where('school_id', Auth::user()->school_id)->first();

        return view('/admin/member/create', ['count' => $count, 'subscription' => $subscription]);
    }

    /**
     * Load standard/transport/blood-group/caste dropdown data used by the
     * student list filter (see resources/assets/js/components/student/Filter.vue
     * and librarycard/Filter.vue) — unrelated to the Add/Edit Student forms.
     *
     * @return array
     */
    public function member()
    {
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);

        $standardlinks = collect(
            SiteHelper::getStandardLinkList(Auth::user()->school_id)
        )->map(function ($item) {

            $item['groups'] = Group::where('standardLink_id', $item['id'])
                ->select('id', 'group_name')
                ->get();

            return $item;
        });

        $array = [];

        $array['academic_year_id'] = $academic_year->id;
        $array['countrylist'] = SiteHelper::getCountries();
        $array['statelist'] = SiteHelper::getStates();
        $array['citylist'] = SiteHelper::getCities();
        $array['standardLinklist'] = $standardlinks;
        $array['blood_groups'] = SiteHelper::getBloodGroups();
        $array['castelist'] = SiteHelper::getCasteList();
        $array['transportlist'] = SiteHelper::getTransportList();
        $array['date_of_birth'] = date('Y-m-d', strtotime('-4 years'));
        $array['joining_date'] = date('Y-m-d');

        return $array;
    }

    /**
     * Show student edit form.
     *
     * @param  string  $name
     * @return Response
     */
    public function edit($name)
    {
        //
        $user = StudentUser::where('name', $name)->first();

        if (Gate::allows('member', $user)) {
            return view('/admin/member/edit', ['user' => $user]);
        } else {
            abort(403);
        }
    }

    /**
     * Delete student and related records.
     *
     * @param  string  $name
     * @return Response
     */
    public function destroy($name)
    {
        try {
            $user = StudentUser::with('userprofile')->where('name', $name)->first();

            $studentacademic = StudentAcademic::where('user_id', $user->id);
            if ($studentacademic != null) {
                $studentacademic->delete();
            }
            $studentparentlink = StudentParentLink::where('student_id', $user->id);
            if ($studentparentlink != null) {
                $studentparentlink->delete();
            }
            $userprofile = Userprofile::where('user_id', $user->id);
            $userprofile->delete();
            $user->delete();

            $message = trans('messages.delete_success_msg', ['module' => 'Student']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $user,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_DELETE_STUDENT,
                $message
            );
            \Session::put('successmessage', $message);

            return redirect('/admin/students');
        } catch (Exception $e) {
        }
    }

    /**
     * Display blocked students list.
     *
     * @return Response
     */
    public function blockedstudents()
    {
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);
        $count = StudentUser::ByRole(6)->where([['school_id', $school_id], ['status', 'inactive']])->where('deleted_at', null)->count();
        $alphabet = request('alphabet') ? request('alphabet') : '';
        $query = \Request::getQueryString();
        $standardLink = SiteHelper::getStandardLinkList($school_id);

        $lowest_standard = Standard::where('school_id', $school_id)->orderBy('order')->first();

        if (count(\Request::getQueryString()) == 0) {
            $standard = StandardLink::where([['school_id', $school_id], ['academic_year_id', $academic_year->id]])->first();
        }
        $birthday = request('date_of_birth') != null ? 'true' : false;

        if (request('standard') != null) {
            $selected_standard = request('standard');
        } else {
            $selected_standard = $standard->id;
        }

        return view('/admin/member/blockedstudents', ['alphabet' => $alphabet, 'query' => $query, 'count' => $count, 'standardLinks' => $standardLink, 'standard' => $standard->id, 'birthday' => $birthday, 'selected_standard' => $selected_standard]);
    }
}
