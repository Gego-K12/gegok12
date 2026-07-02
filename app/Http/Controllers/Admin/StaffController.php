<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Traits\MemberProcess;
use Illuminate\Http\Request;
use App\Traits\RegisterUser;
use App\Traits\LogActivity;
use App\Traits\Common;
use App\Models\Subscription;
use App\Models\Userprofile;
use App\Models\TeacherProfile;
use App\Helpers\SiteHelper;
use App\Models\User;
use League\Csv\Writer;
use Exception;
use Log;
use PDF;

/**
 * Class StaffController
 *
 * Handles staff management operations such as
 * creation, update, deletion, export, and ID card
 * generation within the admin module.
 *
 * @package App\Http\Controllers\Admin
 */
class StaffController extends Controller
{
    use RegisterUser;
    use LogActivity;
    use MemberProcess;
    use Common;

    /**
     * Filter and fetch staff members.
     *
     * Applies group-based filtering depending
     * on inventory configuration.
     *
     * @param Request $request
     * @return mixed
     */
    public function find(Request $request)
    {
        $groups = [8, 10, 11, 13];

        if (config('ginventory.enabled', false)) {
            $groups[] = 12; 
        }

        return $this->StaffFilter($request, Auth::user()->school_id, $groups);
    }

    /**
     * Display staff listing page.
     *
     * Shows staff count, alphabet filter,
     * and birthday filter.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $query = User::where('school_id', Auth::user()->school_id);

        $groups = [8, 10, 11, 13];

        if (config('ginventory.enabled', false)) {
            $groups[] = 12; 
        }

        $count = $query->whereIn('usergroup_id', $groups)->count();
        
        $alphabet = request('alphabet') ? request('alphabet') : 'A';
        $query    = \Request::getQueryString();

        if(request('date_of_birth') != null)
        {
            $birthday = 'true';
        }

        return view('/admin/staff/index',[
            'alphabet' => $alphabet,
            'query'    => $query,
            'birthday' => $birthday,
            'count'    => $count
        ]);
    }

    /**
     * Show the form for creating a new staff member.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $count = User::where('school_id',Auth::user()->school_id)
                     ->where('usergroup_id',5)
                     ->count();

        $subscription = Subscription::with('plan')
            ->where('school_id',Auth::user()->school_id)
            ->first();

        return view('/admin/staff/create',[
            'count' => $count,
            'subscription' => $subscription
        ]);
    }

    /**
     * Store a newly created staff member.
     *
     * Creates staff based on designation,
     * logs activity, and redirects back.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        try
        {
            $school_id = Auth::user()->school_id;
            $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
            $path = \Session::get('avatar_path');

            if($request->designation=="librarian")
            {
                $usergroup_id=8;
            }
            else if($request->designation=="receptionist")
            {
                $usergroup_id=10;
            }
            else if($request->designation=="accountant")
            {
                $usergroup_id=11;
            }
            else if($request->designation=="stock_keeper")
            {
                $usergroup_id=12;
            }
            else
            {
                $usergroup_id=13;
            }

            $user = $this->CreateTeacher(
                $request,
                $school_id,
                $academic_year,
                $path,
                $usergroup_id
            );

            $mes = trans('messages.add_success_msg',['module' => 'Staff']);
            \Session::forget('avatar_path');

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $user,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_ADD_TEACHER,
                $mes
            ); 

            return redirect()->back()->with('successmessage',$mes);
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        } 
    }

    /**
     * Display the specified staff profile.
     *
     * @param string $name
     * @return \Illuminate\Http\Response
     */
    public function show($name)
    {
        //
        $user = User::where('name',$name)->first(); 
        return view('/admin/staff/show',['user' => $user]);
    }

    /**
     * Show the form for editing a staff member.
     *
     * @param string $name
     * @return \Illuminate\Http\Response
     */
    public function edit($name)
    {
        //
        $user = User::where('name',$name)->first();
        $userprofile = Userprofile::where('user_id',$user->id)->first();
       
        return view('/admin/staff/edit',[
            'user' => $user,
            'userprofile' => $userprofile
        ]);
    }

    /**
     * Update the specified staff member.
     *
     * Updates profile, qualifications,
     * and logs the activity.
     *
     * @param Request $request
     * @param string $name
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $name)
    {
        //
        try
        {
            $school_id = Auth::user()->school_id;
            $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);

            if($request->designation=="librarian")
            {
                $usergroup_id=8;
            }
            else if($request->designation=="receptionist")
            {
                $usergroup_id=10;
            }
            else if($request->designation=="accountant")
            {
                $usergroup_id=11;
            }
            else if($request->designation=="stock_keeper")
            {
                $usergroup_id=12;
            }
            else
            {
                $usergroup_id=13;
            }

            $user = User::where('name',$name)->first();
            $userprofile = Userprofile::where('user_id',$user->id)->first();

            if(Request('avatar'))
            {
                $file = $request->file('avatar');
                $path = $this->uploadFile(
                    Auth::user()->school->slug.'/uploads/admin/teacher/avatar',
                    $file
                ); 
                $userprofile->avatar = $path;  
            }

            $userprofile->firstname      = $request->firstname;
            $userprofile->lastname       = $request->lastname;
            $userprofile->gender         = $request->gender;
            $userprofile->date_of_birth  = $request->date_of_birth;
            $userprofile->blood_group    = $request->blood_group;
            $userprofile->address        = $request->address;
            $userprofile->city_id        = $request->city_id;
            $userprofile->state_id       = $request->state_id;
            $userprofile->country_id     = $request->country_id;
            $userprofile->pincode        = $request->pincode;
            $userprofile->aadhar_number  = $request->aadhar_number;
            $userprofile->marital_status = $request->marital_status;
            $userprofile->notes          = $request->notes;
            $userprofile->joining_date   = date('Y-m-d',strtotime($request->joining_date));

            $userprofile->save();

            TeacherProfile::where([
                ['school_id',$school_id],
                ['user_id',$user->id]
            ])->delete();

            if($request->qualification_id == null)
            {
                $teacherprofile = new TeacherProfile;
                $teacherprofile->school_id        = $school_id;
                $teacherprofile->academic_year_id = $academic_year->id;
                $teacherprofile->user_id          = $user->id;
                $teacherprofile->qualification_id = $request->qualification_id;
                $teacherprofile->sub_qualification= $request->sub_qualification;
                $teacherprofile->ug_degree        = $request->ug_degree;
                $teacherprofile->pg_degree        = $request->pg_degree;
                $teacherprofile->specialization   = $request->specialization;
                $teacherprofile->designation      = $request->designation;
                $teacherprofile->sub_designation  = $request->sub_designation;
                $teacherprofile->employee_id      = $request->employee_id;
                $teacherprofile->job_type         = $request->job_type;
                $teacherprofile->interested_in    = $request->interested_in;
                $teacherprofile->reporting_to     = $request->reporting_to;
                $teacherprofile->status           = 1;
                $teacherprofile->save();
            }
            else
            {
                foreach($request->qualification_id as $qualification)
                {
                    $teacherprofile = new TeacherProfile;
                    $teacherprofile->school_id        = $school_id;
                    $teacherprofile->academic_year_id = $academic_year->id;
                    $teacherprofile->user_id          = $user->id;
                    $teacherprofile->employee_id      = $request->employee_id;
                    $teacherprofile->qualification_id = $qualification;
                    $teacherprofile->sub_qualification= $request->sub_qualification;
                    $teacherprofile->ug_degree        = $request->ug_degree;
                    $teacherprofile->pg_degree        = $request->pg_degree;
                    $teacherprofile->specialization   = $request->specialization;
                    $teacherprofile->designation      = $request->designation;
                    $teacherprofile->sub_designation  = $request->sub_designation;
                    $teacherprofile->job_type         = $request->job_type;
                    $teacherprofile->interested_in    = $request->interested_in;
                    $teacherprofile->reporting_to     = $request->reporting_to;
                    $teacherprofile->status           = 1;
                    $teacherprofile->save();
                }
            }

            $message = trans('messages.update_success_msg',['module' => 'Staff']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $userprofile,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_EDIT_TEACHER,
                $message
            ); 

            \Session::put('successmessage',$message);
            return redirect()->back();
        }
        catch(Exception $e)
        {
        } 
    }

    /**
     * Remove the specified staff member.
     *
     * @param string $name
     * @return \Illuminate\Http\Response
     */
    public function destroy($name)
    {
        try
        {
            $user = User::where('name',$name)->first();
            $user->delete();

            $message = trans('messages.delete_success_msg',['module' => 'Teacher']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $user,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_DELETE_TEACHER,
                $message
            ); 

            \Session::put('successmessage',$message);
            return redirect('/admin/staffs');
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        } 
    }

    /**
     * Store selected staff export headings.
     *
     * @param Request $request
     * @return void
     */
    public function staffexport(Request $request)
    {
        \Session::forget('staff_headings');
        $heads = array_values($request->headings);
        \Session::put('staff_headings', $heads);
    }

    /**
     * Export staff details to CSV.
     *
     * @param Request $request
     * @return void
     */
    public function staffexports(Request $request)
    {
        $headings = \Session::get('staff_headings');
        $heads = array_values($headings);

        $users = $this->StaffFilter($request,Auth::user()->school_id,[8,10,11,12,13]);
        $csv = Writer::createFromFileObject(new \SplTempFileObject());

        $default = [
            'employee_id','designation','name','email','mobile_no',
            'gender','Joining_date','adhaar','blood_group',
            'date_of_birth','address','city','state','country','pincode'
        ];

        $result = array_map('ucfirst', array_intersect($default, $heads));

        if(count($users) > 0)
        {
            $csv->insertOne($result);

            foreach($users as $user)
            {
                $data=[];
                if(in_array('employee_id', $heads)) $data[] = $user->getTeacherDetails()['employee_id'];
                if(in_array('designation', $heads)) $data[] = $user->getTeacherDetails()['designation']=='others'
                    ? $user->getTeacherDetails()['sub_designation']
                    : $user->getTeacherDetails()['designation'];
                if(in_array('name', $heads)) $data[] = $user->FullName;
                if(in_array('email', $heads)) $data[] = $user->email;
                if(in_array('mobile_no', $heads)) $data[] = $user->mobile_no;
                if(in_array('gender', $heads)) $data[] = $user->userprofile->gender;
                if(in_array('Joining_date', $heads)) $data[] = $user->userprofile->joining_date;
                if(in_array('adhaar', $heads)) $data[] = $user->userprofile->aadhar_number;
                if(in_array('blood_group', $heads)) $data[] = $user->userprofile->blood_group;
                if(in_array('date_of_birth', $heads)) $data[] = date('d-m-Y',strtotime($user->userprofile->date_of_birth));
                if(in_array('address', $heads)) $data[] = $user->userprofile->address;
                if(in_array('city', $heads)) $data[] = $user->userprofile->city->name;
                if(in_array('state', $heads)) $data[] = $user->userprofile->state->name;
                if(in_array('country', $heads)) $data[] = $user->userprofile->country->name;
                if(in_array('pincode', $heads)) $data[] = $user->userprofile->pincode;

                $csv->insertOne($data);
            }
        }
        else
        {
            $csv->insertOne(['No Records Found']);
        }

        $csv->output('SP Student Export'.date('_d-m-Y_H:i').'.csv');

        $message = trans('messages.export_success_msg',['module' => 'Student']);
        $ip = $this->getRequestIP();

        $this->doActivityLog(
            Auth::user(),
            Auth::user(),
            ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
            LOGNAME_EXPORT_STUDENT,
            $message
        );
    }

    /**
     * Show staff ID card list.
     *
     * @return \Illuminate\Http\Response
     */
    public function idcard()
    {
        $academic = SiteHelper::getAcademicYear(Auth::user()->school_id);
        $staffs = User::where('school_id',Auth::user()->school_id)
            ->whereIn('usergroup_id',[8,10,11,12,13])
            ->get();

        return view('/admin/staff/idcard', compact('staffs','academic'));
    }

    /**
     * Print staff ID cards as PDF.
     *
     * @return \Illuminate\Http\Response
     */
    public function printidcard()
    {
        $academic = SiteHelper::getAcademicYear(Auth::user()->school_id);
        $staffs = User::where('school_id',Auth::user()->school_id)
            ->whereIn('usergroup_id',[8,10,11,12,13])
            ->get();

        $pdf = PDF::loadView('admin/staff/idcard-print', compact('staffs','academic'));
        return $pdf->stream('result.pdf', ['Attachment'=>0]); 
    }

    /**
     * Show individual staff ID card.
     *
     * @param string $name
     * @return \Illuminate\Http\Response
     */
    public function showidcard($name)
    {
        $academic = SiteHelper::getAcademicYear(Auth::user()->school_id);
        $staffs = User::where('name',$name)->first();

        return view('/admin/staff/showidcard',[
            'staffs' => $staffs,
            'academic' => $academic
        ]);
    }

    /**
     * Print individual staff ID card as PDF.
     *
     * @param string $name
     * @return \Illuminate\Http\Response
     */
    public function showprintidcard($name)
    {
        $academic = SiteHelper::getAcademicYear(Auth::user()->school_id);
        $staffs = User::where('name',$name)->first();

        $pdf = PDF::loadView(
            'admin/staff/show-idcardprint',
            ['staffs' => $staffs,'academic'=>$academic]
        );

        return $pdf->stream('result.pdf', ['Attachment'=>0]); 
    }
}
