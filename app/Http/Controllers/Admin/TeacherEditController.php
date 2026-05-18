<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers\Admin;

use App\Http\Requests\TeacherQualificationUpdateRequest;
use App\Http\Requests\TeacherQualificationAddRequest;
use App\Http\Requests\TeacherAddressUpdateRequest;
use App\Http\Requests\TeacherAddressAddRequest;
use App\Http\Requests\TeacherProfileAddRequest;
use App\Http\Requests\TeacherAvatarAddRequest;
use App\Http\Requests\TeacherNoteAddRequest;
use App\Http\Requests\TeacherUpdateRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\TeacherProfile;
use App\Models\Users\TeacherUser;
use App\Traits\RegisterUser;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Models\AcademicYear;
use App\Helpers\SiteHelper;
use Illuminate\Support\Facades\Log;
use App\Traits\LogActivity;
use App\Models\Userprofile;
use App\Models\RoleUser;
use App\Traits\Common;
use App\Models\User;
use Exception;

class TeacherEditController extends Controller
{
    use RegisterUser; 
    use LogActivity;
    use Common;

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editTeacher($name)
    {
      //
      $user           = TeacherUser::where('name',$name)->first();  //User
      $userprofile    = Userprofile::where('user_id',$user->id)->first();
      $teacherprofile = $user->getTeacherDetails();
        
      $array = [];

      $array['firstname']         = $userprofile->firstname;
      $array['lastname']          = $userprofile->lastname;
      $array['date_of_birth']     = date('Y-m-d',strtotime($userprofile->date_of_birth));
      $array['gender']            = $userprofile->gender;
      $array['blood_group']       = $userprofile->blood_group;
      $array['aadhar_number']     = $userprofile->aadhar_number==NULL ? '':$userprofile->aadhar_number;
      $array['country_id']        = $userprofile->country_id;
      $array['state_id']          = $userprofile->state_id;
      $array['city_id']           = $userprofile->city_id;
      $array['pincode']           = $userprofile->pincode==NULL ? '':$userprofile->pincode;
      $array['avatar']            = $userprofile->AvatarPath;
      $array['marital_status']    = $userprofile->marital_status;
      $array['notes']             = $userprofile->notes;
      $array['employee_id']       = $teacherprofile['employee_id'];
      $array['joining_date']      = $userprofile->joining_date==NULL ? '':date('Y-m-d',strtotime($userprofile->joining_date));

      $array['designation']       = $teacherprofile['designation'];
      $array['sub_designation']   = $teacherprofile['sub_designation'];
      $array['qualification_id']  = $teacherprofile['qualification_id'];
      $array['sub_qualification'] = $teacherprofile['sub_qualification'];
      $array['ug_degree']         = $teacherprofile['ug_degree'];
      $array['pg_degree']         = $teacherprofile['pg_degree'];
      $array['specialization']    = $teacherprofile['specialization'];
      $array['job_type']          = $teacherprofile['job_type'];
      $array['interested_in']     = $teacherprofile['interested_in'];
      $array['reporting_to']      = $teacherprofile['reporting_to'];

      $array['countrylist']       =   SiteHelper::getCountries();
      $array['statelist']         =   SiteHelper::getStates();
      $array['citylist']          =   SiteHelper::getCities();
      $array['qualificationlist'] =   SiteHelper::getAdditionalCertificates();
      $array['pglist']            =   SiteHelper::getPGList();
      $array['uglist']            =   SiteHelper::getUGList();
      $array['designationlist']   =   SiteHelper::getTeachingDesignations();
      $array['nonteachinglist']   =   SiteHelper::getNonTeachingDesignations();
      $array['blood_groups']      =   SiteHelper::getBloodGroups();
      $array['maritalList']       =   SiteHelper::getMaritalList();
      $array['HODList']           =   SiteHelper::getHODList(Auth::user()->school_id);
      $array['principalList']     =   SiteHelper::getPrincipalList(Auth::user()->school_id);

      return $array;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($name)
    {
      //
      $user = TeacherUser::where('name',$name)->first();
      $userprofile = Userprofile::where('user_id',$user->id)->first();
       
      return view('/admin/teacher/edit',['user' => $user , 'userprofile' => $userprofile ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function editValidationProfile(TeacherUpdateRequest $request,$name)
    {
      //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function editValidationQualification(TeacherQualificationUpdateRequest $request,$name)
    {
      //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function editValidationNote(TeacherNoteAddRequest $request,$name)
    {
      //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function editValidationAddress(TeacherAddressUpdateRequest $request,$name)
    {
      //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $name)
    {
      // 
      try
      {
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);


        if($request->designation == "librarian")
        {
          $usergroup_id = 8;
        }
        else if($request->designation == "receptionist")
        {
          $usergroup_id = 10;
        }
        else if($request->designation=="accountant")
        {
          $usergroup_id= 11;
        }
        else if($request->designation=="others")
        {
          $usergroup_id= 13;
        }
        else
        {
          $usergroup_id = 5;
        }

        $user = User::where('name',$name)->first();
        $userprofile = Userprofile::where('user_id',$user->id)->first();
        if(Request('avatar'))
        {
          $file = $request->file('avatar');
          $path = $this->uploadFile(Auth::user()->school->slug.'/uploads/admin/teacher/avatar',$file); 
          $userprofile->avatar = $path;  
        }
        else
        {
          $userprofile->avatar = $userprofile->avatar;
        }
            
        $userprofile->firstname             = $request->firstname;
        $userprofile->lastname              = $request->lastname;
        $userprofile->gender                = $request->gender;
        $userprofile->date_of_birth         = $request->date_of_birth;
        $userprofile->blood_group           = $request->blood_group;
        $userprofile->address               = $request->address;
        $userprofile->city_id               = $request->city_id;
        $userprofile->state_id              = $request->state_id;
        $userprofile->country_id            = $request->country_id;
        $userprofile->pincode               = $request->pincode;
        $userprofile->aadhar_number         = $request->aadhar_number;
        $userprofile->marital_status        = $request->marital_status;
        $userprofile->notes                 = $request->notes;
        $userprofile->joining_date          = date('Y-m-d',strtotime($request->joining_date));
            
        $userprofile->save();

        $teacherprofiles = TeacherProfile::where([['school_id',$school_id],['user_id',$user->id]])->get();
        foreach($teacherprofiles as $profile)
        {
          $profile->delete();
        }

            if($request->qualification_id == null)
            {
                $teacherprofile = new TeacherProfile;

                $teacherprofile->school_id            = $school_id;
                $teacherprofile->academic_year_id     = $academic_year->id;
                $teacherprofile->user_id              = $user->id;
                $teacherprofile->qualification_id     = $request->qualification_id;
                if($teacherprofile->qualification_id == 1)
                {
                    $teacherprofile->sub_qualification    = $request->sub_qualification;
                }
                $teacherprofile->ug_degree            = $request->ug_degree;
                $teacherprofile->pg_degree            = $request->pg_degree;
                $teacherprofile->specialization       = $request->specialization;
                $teacherprofile->designation          = $request->designation;
                $teacherprofile->sub_designation      = $request->sub_designation;
                $teacherprofile->employee_id          = $request->employee_id;                
                $teacherprofile->job_type             = $request->job_type;                
                $teacherprofile->interested_in        = $request->interested_in;              
                $teacherprofile->reporting_to         = $request->reporting_to;                 
                $teacherprofile->status               = 1;

                $teacherprofile->save();
            }
            else
            {
                foreach($request->qualification_id as $qualification)
                {
                  $teacherprofile = new TeacherProfile;

                  $teacherprofile->school_id            = $school_id;
                  $teacherprofile->academic_year_id     = $academic_year->id;
                  $teacherprofile->user_id              = $user->id;
                  $teacherprofile->employee_id          = $request->employee_id;
                  $teacherprofile->qualification_id     = $qualification;
                  $teacherprofile->sub_qualification    = $request->sub_qualification;
                  $teacherprofile->ug_degree            = $request->ug_degree;
                  $teacherprofile->pg_degree            = $request->pg_degree;
                  $teacherprofile->specialization       = $request->specialization;
                  $teacherprofile->designation          = $request->designation;
                  $teacherprofile->sub_designation      = $request->sub_designation;               
                  $teacherprofile->job_type             = $request->job_type;               
                  $teacherprofile->interested_in        = $request->interested_in;               
                  $teacherprofile->reporting_to         = $request->reporting_to; 
                  $teacherprofile->status               = 1;

                  $teacherprofile->save();
                }
            }

        /*$roleUsers = RoleUser::where('user_id',$user->id)->get();

        foreach($roleUsers as $roleUser)
        {
          $roleUser->delete();
        }*/

        $user->roles()->detach(); 


        if($request->designation == 'principal')
        {
          $user->addRole('principal');
        }

        if( ($request->designation == 'principal') || ($request->designation == 'vice_principal') || ($request->designation == 'head_of_the_department') )
        {
          $user->addRole('leave_checker');
          $user->addRole('class_coordinator');
        }
        else
        {
          $user->addRole('leave_applier');
        }

        $message=trans('messages.update_success_msg',['module' => 'Teacher']);

        $ip= $this->getRequestIP();
        $this->doActivityLog(
          $userprofile,
          Auth::user(),
          ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
          LOGNAME_EDIT_TEACHER,
          $message
        ); 
        \Session::put('successmessage',$message);
        return redirect()->back();
      }
      catch(Exception $e)
      {
        //dd($e->getMessage());
      } 
    }
}
