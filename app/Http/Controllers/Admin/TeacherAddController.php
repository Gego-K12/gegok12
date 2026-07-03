<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\TeacherAddressAddRequest;
use App\Http\Requests\TeacherAvatarAddRequest;
use App\Http\Requests\TeacherNoteAddRequest;
use App\Http\Requests\TeacherProfileAddRequest;
use App\Http\Requests\TeacherQualificationAddRequest;
use App\Models\Subscription;
use App\Models\TeacherProfile;
use App\Models\User;
use App\Traits\Common;
use App\Traits\LogActivity;
use App\Traits\RegisterUser;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class TeacherAddController extends Controller
{
    use Common;
    use LogActivity;
    use RegisterUser;

    public function member()
    {
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);
        $teacher_count = User::where([['school_id', $school_id], ['usergroup_id', 5]])->count();
        $date_of_birth = date('Y-m-d', strtotime('-25 years', strtotime(date('Y'))));

        $array = [];

        $array['academic_year_id'] = $academic_year->id;
        $array['countrylist'] = SiteHelper::getCountries();
        $array['statelist'] = SiteHelper::getStates();
        $array['citylist'] = SiteHelper::getCities();
        $array['qualificationlist'] = SiteHelper::getAdditionalCertificates();
        $array['pglist'] = SiteHelper::getPGList();
        $array['uglist'] = SiteHelper::getUGList();
        $array['date_of_birth'] = $date_of_birth;
        $array['employee_id'] = $this->employeenumber();
        $array['designationlist'] = SiteHelper::getTeachingDesignations();
        $array['nonteachinglist'] = SiteHelper::getNonTeachingDesignations();
        $array['blood_groups'] = SiteHelper::getBloodGroups();
        $array['maritalList'] = SiteHelper::getMaritalList();
        $array['HODList'] = SiteHelper::getHODList($school_id);
        $array['principalList'] = SiteHelper::getPrincipalList($school_id);

        return $array;
    }

    protected function employeenumber()
    {

        if (count(TeacherProfile::where([['school_id', Auth::user()->school_id], ['employee_id', '!=', null]])->get()) <= 0) {
            $employee_id = 'EMP001';
        } else {
            $employee_number = TeacherProfile::where([['school_id', Auth::user()->school_id], ['employee_id', '!=', null]])->orderBy('id', 'DESC')->take(1)->first()->employee_id;
            $employee_id = ++$employee_number;
        }

        return $employee_id;

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
        $count = User::where('school_id', Auth::user()->school_id)->where('usergroup_id', 5)->count();
        $subscription = Subscription::with('plan')->where('school_id', Auth::user()->school_id)->first();

        return view('/admin/teacher/create', ['count' => $count, 'subscription' => $subscription]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function validationProfile(TeacherProfileAddRequest $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function validationAvatar(TeacherAvatarAddRequest $request)
    {
        //
        try {
            $image_parts = explode(';base64,', $request->avatar);
            $image_type_aux = explode('image/', $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $location = Auth::user()->school->slug.'/uploads/admin/teacher/avatar/';
            $file = uniqid().'.jpg';
            $upload_path = $location.$file;
            $this->putContents($upload_path, $image_base64);

            \Session::put('avatar_path', $upload_path);
        } catch (Exception $e) {
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function validationQualification(TeacherQualificationAddRequest $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function validationNote(TeacherNoteAddRequest $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function validationAddress(TeacherAddressAddRequest $request)
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

            $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);

            $path = \Session::get('avatar_path');
            if ($request->designation == 'librarian') {
                $usergroup_id = 8;
            } elseif ($request->designation == 'receptionist') {
                $usergroup_id = 10;
            } elseif ($request->designation == 'accountant') {
                $usergroup_id = 11;
            } elseif ($request->designation == 'others') {
                $usergroup_id = 13;
            } else {
                $usergroup_id = 5;
            }

            $user = $this->CreateTeacher($request, $school_id, $academic_year, $path, $usergroup_id);
            $mes = trans('messages.add_success_msg', ['module' => 'Teacher']);
            \Session::forget('avatar_path');

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $user,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_ADD_TEACHER,
                $mes
            );

            return redirect()->back()->with('successmessage', $mes);
        } catch (Exception $e) {
        }
    }
}
