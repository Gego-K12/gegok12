<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Librarian;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\TeacherProfile;
use App\Models\User;
use App\Models\Userprofile;
use App\Traits\Common;
use App\Traits\LogActivity;
use App\Traits\MemberProcess;
use App\Traits\RegisterUser;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use League\Csv\Writer;
use Log;
use PDF;

class StaffController extends Controller
{
    use Common;
    use LogActivity;
    use MemberProcess;
    use RegisterUser;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function find(Request $request)
    {
        //
        // return $this->TeacherFilter($request,Auth::user()->school_id,5);
        return $this->LibraryStaffFilter($request, Auth::user()->school_id, [8, 10, 11, 12, 13]);
    }

    public function index()
    {
        //

        $count = User::where('school_id', Auth::user()->school_id)->whereIn('usergroup_id', [8, 10, 11, 12, 13])->count();
        $alphabet = request('alphabet') ? request('alphabet') : 'A';
        $query = \Request::getQueryString();
        if (request('date_of_birth') != null) {
            $birthday = 'true';
        }

        return view('/library/index_staff', ['alphabet' => $alphabet, 'query' => $query, 'birthday' => $birthday, 'count' => $count]);
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

        return view('/admin/staff/create', ['count' => $count, 'subscription' => $subscription]);
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
            } elseif ($request->designation == 'stock_keeper') {
                $usergroup_id = 12;
            } else {
                $usergroup_id = 13;
            }

            $user = $this->CreateTeacher($request, $school_id, $academic_year, $path, $usergroup_id);
            $mes = trans('messages.add_success_msg', ['module' => 'Staff']);
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
            Log::info($e->getMessage());
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($name)
    {
        //
        $user = User::where('name', $name)->first();

        return view('/admin/staff/show', ['user' => $user]);
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

        return view('/admin/staff/edit', ['user' => $user, 'userprofile' => $userprofile]);
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
            $school_id = Auth::user()->school_id;
            $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);

            if ($request->designation == 'librarian') {
                $usergroup_id = 8;
            } elseif ($request->designation == 'receptionist') {
                $usergroup_id = 10;
            } elseif ($request->designation == 'accountant') {
                $usergroup_id = 11;
            } elseif ($request->designation == 'stock_keeper') {
                $usergroup_id = 12;
            } else {
                $usergroup_id = 13;
            }

            $user = User::where('name', $name)->first();
            $userprofile = Userprofile::where('user_id', $user->id)->first();
            if (Request('avatar')) {
                $file = $request->file('avatar');
                $path = $this->uploadFile(Auth::user()->school->slug.'/uploads/admin/teacher/avatar', $file);
                $userprofile->avatar = $path;
            } else {
                $userprofile->avatar = $userprofile->avatar;
            }

            $userprofile->firstname = $request->firstname;
            $userprofile->lastname = $request->lastname;
            $userprofile->gender = $request->gender;
            $userprofile->date_of_birth = $request->date_of_birth;
            $userprofile->blood_group = $request->blood_group;
            $userprofile->address = $request->address;
            $userprofile->city_id = $request->city_id;
            $userprofile->state_id = $request->state_id;
            $userprofile->country_id = $request->country_id;
            $userprofile->pincode = $request->pincode;
            $userprofile->aadhar_number = $request->aadhar_number;
            $userprofile->marital_status = $request->marital_status;
            $userprofile->notes = $request->notes;
            $userprofile->joining_date = date('Y-m-d', strtotime($request->joining_date));

            $userprofile->save();

            $teacherprofiles = TeacherProfile::where([['school_id', $school_id], ['user_id', $user->id]])->get();
            foreach ($teacherprofiles as $profile) {
                $profile->delete();
            }

            if ($request->qualification_id == null) {
                $teacherprofile = new TeacherProfile;

                $teacherprofile->school_id = $school_id;
                $teacherprofile->academic_year_id = $academic_year->id;
                $teacherprofile->user_id = $user->id;
                $teacherprofile->qualification_id = $request->qualification_id;
                if ($teacherprofile->qualification_id == 1) {
                    $teacherprofile->sub_qualification = $request->sub_qualification;
                }
                $teacherprofile->ug_degree = $request->ug_degree;
                $teacherprofile->pg_degree = $request->pg_degree;
                $teacherprofile->specialization = $request->specialization;
                $teacherprofile->designation = $request->designation;
                $teacherprofile->sub_designation = $request->sub_designation;
                $teacherprofile->employee_id = $request->employee_id;
                $teacherprofile->job_type = $request->job_type;
                $teacherprofile->interested_in = $request->interested_in;
                $teacherprofile->reporting_to = $request->reporting_to;
                $teacherprofile->status = 1;

                $teacherprofile->save();
            } else {
                foreach ($request->qualification_id as $qualification) {
                    $teacherprofile = new TeacherProfile;

                    $teacherprofile->school_id = $school_id;
                    $teacherprofile->academic_year_id = $academic_year->id;
                    $teacherprofile->user_id = $user->id;
                    $teacherprofile->employee_id = $request->employee_id;
                    $teacherprofile->qualification_id = $qualification;
                    $teacherprofile->sub_qualification = $request->sub_qualification;
                    $teacherprofile->ug_degree = $request->ug_degree;
                    $teacherprofile->pg_degree = $request->pg_degree;
                    $teacherprofile->specialization = $request->specialization;
                    $teacherprofile->designation = $request->designation;
                    $teacherprofile->sub_designation = $request->sub_designation;
                    $teacherprofile->job_type = $request->job_type;
                    $teacherprofile->interested_in = $request->interested_in;
                    $teacherprofile->reporting_to = $request->reporting_to;
                    $teacherprofile->status = 1;

                    $teacherprofile->save();
                }
            }

            /*$roleUsers = RoleUser::where('user_id',$user->id)->get();

            foreach($roleUsers as $roleUser)
            {
              $roleUser->delete();
            }

            if($request->designation == 'principal')
            {
              $user->addRole('principal');
            }

            if( ($request->designation == 'principal') || ($request->designation == 'vice_principal') || ($request->designation == 'head_of_the_department') )
            {
              $user->addRole('leave_checker');
            }
            else
            {
              $user->addRole('leave_applier');
            }
*/
            $message = trans('messages.update_success_msg', ['module' => 'Staff']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $userprofile,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_EDIT_TEACHER,
                $message
            );
            \Session::put('successmessage', $message);

            return redirect()->back();
        } catch (Exception $e) {
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($name)
    {
        try {
            $user = User::where('name', $name)->first();
            $user->delete();

            $message = trans('messages.delete_success_msg', ['module' => 'Teacher']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $user,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_DELETE_TEACHER,
                $message
            );
            \Session::put('successmessage', $message);

            return redirect('/admin/staffs');
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    public function staffexport(Request $request)
    {

        /* if(!\Session::has('headings'))
          {*/
        \Session::forget('staff_headings');
        // }
        $heads = [];
        $heads = array_values($request->headings);
        \Session::put('staff_headings', $heads);

    }

    public function staffexports(Request $request)
    {
        $headings = \Session::get('staff_headings');
        $heads = array_values($headings);
        $users = $this->StaffFilter($request, Auth::user()->school_id, [8, 10, 11, 12, 13]);
        $csv = Writer::createFromFileObject(new \SplTempFileObject);
        $default = ['employee_id', 'designation', 'name', 'email', 'mobile_no', 'gender', 'Joining_date', 'adhaar', 'blood_group', 'date_of_birth', 'address', 'city', 'state', 'country', 'pincode'];
        $result = [];
        $result = array_intersect($default, $heads);
        $result = array_map('ucfirst', $result);

        if (count($users) > 0) {
            $csv->insertOne($result);

            foreach ($users as $user) {
                $data = [];
                if (in_array('employee_id', $heads)) {
                    $data[] = $user->getTeacherDetails()['employee_id'];
                }
                if (in_array('designation', $heads)) {
                    $data[] = $user->getTeacherDetails()['designation'] == 'others' ? $user->getTeacherDetails()['sub_designation'] : $user->getTeacherDetails()['designation'];
                }
                if (in_array('name', $heads)) {
                    $data[] = $user->FullName;
                }
                if (in_array('email', $heads)) {
                    $data[] = $user->email;
                }
                if (in_array('mobile_no', $heads)) {
                    $data[] = $user->mobile_no;
                }
                if (in_array('gender', $heads)) {
                    $data[] = $user->userprofile->gender;
                }
                if (in_array('Joining_date', $heads)) {
                    $data[] = $user->userprofile->joining_date;
                }
                if (in_array('caste', $heads)) {
                    $data[] = $user->userprofile->caste;
                }
                if (in_array('adhaar', $heads)) {
                    $data[] = $user->userprofile->aadhar_number;
                }
                if (in_array('blood_group', $heads)) {
                    $data[] = $user->userprofile->blood_group;
                }
                if (in_array('date_of_birth', $heads)) {
                    $data[] = date('d-m-Y', strtotime($user->userprofile->date_of_birth));
                }
                if (in_array('address', $heads)) {
                    $data[] = $user->userprofile->address;
                }
                if (in_array('city', $heads)) {
                    $data[] = $user->userprofile->city->name;
                }
                if (in_array('state', $heads)) {
                    $data[] = $user->userprofile->state->name;
                }
                if (in_array('country', $heads)) {
                    $data[] = $user->userprofile->country->name;
                }
                if (in_array('pincode', $heads)) {
                    $data[] = $user->userprofile->pincode;
                }

                $csv->insertOne($data);
            }
        } else {
            $csv->insertOne(['No Records Found']);
            $csv->output('SP Student Export'.date('_d-m-Y_H:i').'.csv');
        }
        $csv->output('SP Student Export'.date('_d-m-Y_H:i').'.csv');
        $message = trans('messages.export_success_msg', ['module' => 'Student']);

        $ip = $this->getRequestIP();
        $this->doActivityLog(
            Auth::user(),
            Auth::user(),
            ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
            LOGNAME_EXPORT_STUDENT,
            $message
        );

    }

    public function idcard()
    {

        $academic = SiteHelper::getAcademicYear(Auth::user()->school_id);
        $staffs = User::where('school_id', Auth::user()->school_id)->whereIn('usergroup_id', [8, 10, 11, 12, 13])->get();

        return view('/admin/staff/idcard', compact('staffs', 'academic'));
    }

    public function printidcard()
    {
        $academic = SiteHelper::getAcademicYear(Auth::user()->school_id);
        $staffs = User::where('school_id', Auth::user()->school_id)->whereIn('usergroup_id', [8, 10, 11, 12, 13])->get();
        $pdf = PDF::loadView('admin/staff/idcard-print', compact('staffs', 'academic'));

        return $pdf->stream('result.pdf', ['Attachment' => 0]);
    }

    public function showidcard($name)
    {

        $academic = SiteHelper::getAcademicYear(Auth::user()->school_id);

        $staffs = User::where('name', $name)->first();

        return view('/admin/staff/showidcard', ['staffs' => $staffs, 'academic' => $academic]);
    }

    public function showprintidcard($name)
    {
        $academic = SiteHelper::getAcademicYear(Auth::user()->school_id);
        $staffs = User::where('name', $name)->first();

        $pdf = PDF::loadView('admin/staff/show-idcardprint', ['staffs' => $staffs, 'academic' => $academic]);

        return $pdf->stream('result.pdf', ['Attachment' => 0]);
    }
}
