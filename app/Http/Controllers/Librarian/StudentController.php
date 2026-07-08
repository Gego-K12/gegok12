<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Librarian;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserProfileAddRequest;
use App\Http\Requests\UserProfileUpdateRequest;
use App\Models\Standard;
use App\Models\StandardLink;
use App\Models\StudentAcademic;
use App\Models\StudentParentLink;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Userprofile;
use App\Models\Users\StudentUser;
use App\Traits\Common;
use App\Traits\LogActivity;
use App\Traits\MemberProcess;
use App\Traits\RegisterUser;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class StudentController extends Controller
{
    use Common;
    use LogActivity;
    use MemberProcess;
    use RegisterUser;

    public function find(Request $request)
    {
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);

        $lowest_standard = Standard::where('school_id', $school_id)->orderBy('order')->first();

        $standard = StandardLink::where([['school_id', $school_id], ['academic_year_id', $academic_year->id], ['standard_id', $lowest_standard->id]])->first();

        if (count((array) \Request::getQueryString()) == 0) {
            $request['standard'] = $standard->id;
        }

        return $this->LibraryMemberFilter($request, Auth::user()->school_id, 6, 'active');
    }

    public function index()
    {
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);

        $count = StudentUser::ByRole(6)->where('school_id', $school_id)->where('deleted_at', null)->count();
        $alphabet = request('alphabet') ? request('alphabet') : '';
        $query = \Request::getQueryString();
        $standardLink = SiteHelper::getStandardLinkList($school_id);

        $lowest_standard = Standard::where('school_id', $school_id)->orderBy('order')->first();

        if (count((array) \Request::getQueryString()) == 0) {
            $standard = StandardLink::where([['school_id', $school_id], ['academic_year_id', $academic_year->id], ['standard_id', $lowest_standard->id]])->first();
        }
        if (request('date_of_birth') != null) {
            $birthday = 'true';
        }
        if (request('standard') != null) {
            $selected_standard = request('standard');
        } else {
            $selected_standard = $standard->id;
        }

        return view('/library/index', ['alphabet' => $alphabet, 'query' => $query, 'count' => $count, 'standardLinks' => $standardLink, 'standard' => $standard->id, 'birthday' => $birthday, 'selected_standard' => $selected_standard]);
    }

    /**
     * Show the form for creating a new resource.
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

    public function member()
    {
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);

        $array = [];

        $array['academic_year_id'] = $academic_year->id;
        $array['countrylist'] = SiteHelper::getCountries();
        $array['statelist'] = SiteHelper::getStates();
        $array['citylist'] = SiteHelper::getCities();
        $array['standardLinklist'] = SiteHelper::getStandardLinkList(Auth::user()->school_id);
        $array['blood_groups'] = SiteHelper::getBloodGroups();
        $array['castelist'] = SiteHelper::getCasteList();
        $array['transportlist'] = SiteHelper::getTransportList();
        $array['date_of_birth'] = date('Y-m-d', strtotime('-4 years', strtotime(date('Y'))));
        $array['joining_date'] = date('Y-m-d');

        return $array;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function validationUser(UserProfileAddRequest $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        //
        try {
            $school_id = Auth::user()->school_id;

            $academic_year = SiteHelper::getAcademicYear($school_id);

            $file = $request->file('avatar');
            if ($file) {
                $folder = Auth::user()->school->slug.'/student/avatar';
                $path = $this->uploadFile($folder, $file);
            } else {
                $path = '';
            }

            $user = $this->CreateUser($request, $school_id, $academic_year->id, $path, 6);
            $mes = trans('messages.add_success_msg', ['module' => 'Student']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $user,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_ADD_STUDENT,
                $mes
            );

            return redirect()->back()->with('successmessage', $mes);
        } catch (Exception $e) {
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function editStudent($name)
    {
        //
        $user = User::where('name', $name)->first();
        $userprofile = Userprofile::where('user_id', $user->id)->first();
        $studentAcademic = $user->studentAcademicLatest;

        $array = [];

        $array['firstname'] = $userprofile->firstname;
        $array['lastname'] = $userprofile->lastname;
        $array['date_of_birth'] = date('Y-m-d', strtotime($userprofile->date_of_birth));
        $array['gender'] = $userprofile->gender;
        $array['blood_group'] = $userprofile->blood_group;
        $array['aadhar_number'] = $userprofile->aadhar_number == null ? '' : $userprofile->aadhar_number;
        $array['city_id'] = $userprofile->city_id;
        $array['state_id'] = $userprofile->state_id;
        $array['country_id'] = $userprofile->country_id;
        $array['pincode'] = $userprofile->pincode == null ? '' : $userprofile->pincode;
        $array['birth_place'] = $userprofile->birth_place;
        $array['native_place'] = $userprofile->native_place;
        $array['mother_tongue'] = $userprofile->mother_tongue;
        $array['caste'] = $userprofile->caste;
        $array['sub_caste'] = $userprofile->sub_caste;
        $array['avatar'] = $userprofile->AvatarPath;
        $array['notes'] = $userprofile->notes;
        $array['registration_number'] = $user->registration_number == null ? $userprofile->registration_number : $user->registration_number;
        $array['EMIS_number'] = $userprofile->EMIS_number == null ? '' : $userprofile->EMIS_number;
        $array['joining_date'] = $userprofile->joining_date == null ? '' : date('Y-m-d', strtotime($userprofile->joining_date));

        $array['standardLink_id'] = $studentAcademic->standardLink_id;
        $array['roll_number'] = $studentAcademic->roll_number == null ? '' : $studentAcademic->roll_number;
        $array['id_card_number'] = $studentAcademic->id_card_number == null ? '' : $studentAcademic->id_card_number;
        $array['board_registration_number'] = $studentAcademic->board_registration_number == null ? '' : $studentAcademic->board_registration_number;
        $array['mode_of_transport'] = $studentAcademic->mode_of_transport;
        $array['driver_name'] = $studentAcademic->transport_details['driver_name'];
        $array['driver_contact_number'] = $studentAcademic->transport_details['driver_contact_number'];
        $array['siblings'] = $studentAcademic->siblings;
        $array['siblings_count'] = $studentAcademic->siblings_count;

        for ($i = 0; $i < $studentAcademic->siblings_count; $i++) {
            $array['sibling_details'][$i]['sibling_relation'] = $studentAcademic->sibling_details[$i]['sibling_relation'];
            $array['sibling_details'][$i]['sibling_name'] = $studentAcademic->sibling_details[$i]['sibling_name'];
            $array['sibling_details'][$i]['sibling_date_of_birth'] = date('Y-m-d', strtotime($studentAcademic->sibling_details[$i]['sibling_date_of_birth']));
            $array['sibling_details'][$i]['sibling_standard'] = $studentAcademic->sibling_details[$i]['sibling_standard'];
        }

        $array['countrylist'] = SiteHelper::getCountries();
        $array['statelist'] = SiteHelper::getStates();
        $array['citylist'] = SiteHelper::getCities();
        $array['standardLinklist'] = SiteHelper::getStandardLinkList(Auth::user()->school_id);
        $array['blood_groups'] = SiteHelper::getBloodGroups();
        $array['castelist'] = SiteHelper::getCasteList();
        $array['transportlist'] = SiteHelper::getTransportList();
        $array['today'] = date('Y-m-d');

        return $array;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($name)
    {
        //
        $user = User::where('name', $name)->first();
        $userprofile = Userprofile::where('user_id', $user->id)->first();
        if (Gate::allows('member', $user)) {
            return view('/admin/member/edit', ['user' => $user, 'userprofile' => $userprofile]);
        } else {
            abort(403);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function editValidationUser(UserProfileUpdateRequest $request, $name)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $name)
    {
        //
        try {
            $user = User::where('name', $name)->first();

            $userprofile = Userprofile::where('user_id', $user->id)->first();

            $school_id = Auth::user()->school_id;

            $academic_year = SiteHelper::getAcademicYear($school_id);

            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $folder = Auth::user()->school->slug.'/member/avatar';
                $path = $this->uploadFile($folder, $file);
            } else {
                $path = $userprofile->avatar;
            }

            $userprofile = $this->UpdateUser($request, $school_id, $academic_year->id, $user->id, $path);

            $message = trans('messages.update_success_msg', ['module' => 'Student']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $userprofile,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_EDIT_STUDENT,
                $message
            );
            \Session::put('successmessage', $message);

            return redirect()->back();
        } catch (Exception $e) {
        }
    }

    public function destroy($name)
    {
        try {
            $user = User::with('userprofile')->where('name', $name)->first();

            $studentacademic = StudentAcademic::where('user_id', $user->id);
            if (count($studentacademic) > 0) {
                $studentacademic->delete();
            }
            $studentparentlink = StudentParentLink::where('student_id', $user->id);
            if (count($studentparentlink) > 0) {
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

    public function blockedstudents()
    {
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);
        $count = User::ByRole(6)->where([['school_id', $school_id], ['status', 'inactive']])->where('deleted_at', null)->count();
        $alphabet = request('alphabet') ? request('alphabet') : '';
        $query = \Request::getQueryString();
        $standardLink = SiteHelper::getStandardLinkList($school_id);

        $lowest_standard = Standard::where('school_id', $school_id)->orderBy('order')->first();

        if (count(\Request::getQueryString()) == 0) {
            $standard = StandardLink::where([['school_id', $school_id], ['academic_year_id', $academic_year->id]])->first();
        }
        if (request('date_of_birth') != null) {
            $birthday = 'true';
        }
        if (request('standard') != null) {
            $selected_standard = request('standard');
        } else {
            $selected_standard = $standard->id;
        }

        return view('/admin/member/blockedstudents', ['alphabet' => $alphabet, 'query' => $query, 'count' => $count, 'standardLinks' => $standardLink, 'standard' => $standard->id, 'birthday' => $birthday, 'selected_standard' => $selected_standard]);
    }
}
