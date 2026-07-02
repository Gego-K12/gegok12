<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers;

use App\Http\Requests\Admission\AdmissionAcademicRequest;
use App\Http\Requests\Admission\AdmissionStandardRequest;
use App\Http\Requests\Admission\AdmissionPersonalRequest;
use App\Http\Requests\Admission\AdmissionStudentRequest;
use App\Http\Requests\Admission\AdmissionAvatarRequest;
use App\Http\Requests\Admission\AdmissionParentRequest;
use App\Http\Resources\Admission as AdmissionResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Traits\AdmissionUser;
use App\Models\SchoolDetail;
use Illuminate\Http\Request;
use App\Helpers\SiteHelper;
use App\Traits\LogActivity;
use App\Models\Userprofile;
use App\Models\Admission;
use App\Models\Standard;
use App\Traits\Common;
use App\Models\School;
use App\Models\User;
use Exception;
use Log;

class AdmissionController extends Controller
{  
    use AdmissionUser;
    use LogActivity;
    use Common;

    public function list(Request $request,$slug)
    {
        $school=School::where('slug',$slug)->first();

        $academic_year  = SiteHelper::getAcademicYear($school->id);
        $date_of_birth  = date('Y-m-d',strtotime('-25 years',strtotime(date('Y'))));

        $array = [];

        $array['transportList']     = SiteHelper::getTransportList();
        $array['standardlist']      = SiteHelper::getStandardList($school->id);
        $array['blood_group_list']  = SiteHelper::getBloodGroups();
        $array['qualificationlist'] = SiteHelper::getQualifications();

        return $array;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($slug)
    {
        $school = School::where('slug',$slug)->first();

        $admission_open = SchoolDetail::where('school_id',$school->id)->where('meta_key','admission_open')->first();

        $logo = SchoolDetail::where('school_id',$school->id)->where('meta_key','school_logo')->first();
        $logo = $logo->LogoPath;

        $closedetails = SchoolDetail::where('school_id',$school->id)->where('meta_key','admission_close_message')->first();

        return view('/pages/admission/admission',['admission_open' => $admission_open , 'closedetails' => $closedetails , 'slug' => $slug , 'logo' => $logo]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function validationStandard(AdmissionStandardRequest $request)
    {
        //  
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function validationStudentDetail(AdmissionStudentRequest $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function validationAcademicDetail(AdmissionAcademicRequest $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function validationParentDetail(AdmissionParentRequest $request)
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function validationPersonalDetail(AdmissionPersonalRequest $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$slug)
    {
        $school=School::where('slug',$slug)->first();

        $academic_year  = SiteHelper::getAcademicYear($school->id);
        try
        {  
            $admin = User::where('school_id',$school->id)->ByRole(3)->first();
       
            $admission = new Admission;

            $admission->school_id           = $school->id;
            $admission->academic_year_id    = $academic_year->id;
            $admission->standard_id         = $request->standard_id;
            $admission->name                = $request->name;
            $admission->date_of_birth       = $request->date_of_birth;
            $file=$request->avatar;
            if($file)
            {
                $file_name  = $request->avatar->getClientOriginalName();
                $folder     = $school->id.'/student/avatar';
                $path       = $this->uploadFile($folder,$file);
                
                $admission->avatar=$path;
            }
            $admission->gender                      = $request->gender;
            $admission->height                      = $request->height;
            $admission->weight                      = $request->weight;
            $admission->birth_place                 = $request->birth_place;
            $admission->nationality                 = $request->nationality;
            $admission->religion                    = $request->religion;
            $admission->community                   = $request->community;
            $admission->mother_tongue               = $request->mother_tongue;
            $admission->identification_marks        = $request->identification_marks;
            $admission->aadhar_number               = $request->aadhar_number;
            $admission->blood_group                 = $request->blood_group;
            $admission->school_last_studied         = $request->school_last_studied;
            $admission->reason_for_leaving          = $request->reason_for_leaving;
            $admission->permanent_address           = $request->permanent_address;
            $admission->address_for_communication   = $request->address_for_communication;
            $admission->siblings                    = $request->siblings;
            //$admission->siblings_details          = $request->siblings_details;

            $array=[];

            $array['english']   = $request->english; 
            $array['tamil']     = $request->tamil;
            $array['maths']     = $request->maths;
            $array['science']   = $request->science;
            $array['social']    = $request->social;

            $admission->half_yearly_mark_details  = $array;

            $admission->board_of_education        = $request->board_of_education;
            $admission->choice_of_language        = $request->choice_of_language;
            $admission->group_selection           = $request->group_selection;
            $admission->father_name               = $request->father_name;
            $admission->father_qualification_id   = $request->father_qualification_id;
            $admission->father_designation        = $request->father_designation;
            $admission->father_occupation         = $request->father_occupation;
            $admission->father_organisation       = $request->father_organisation;
            $admission->father_income             = $request->father_income;
            $admission->father_mobile_no          = $request->father_mobile_no;
            $admission->father_email              = $request->father_email;
            $admission->father_aadhar_number      = $request->father_aadhar_number;

            $motherfile = $request->mother_avatar;
            if($motherfile)
            {
                $motherfile_name = $request->mother_avatar->getClientOriginalName();
                $folder          = $school->id.'/student/avatar';
                $mother_path     = $this->uploadFile($folder,$motherfile);

                $admission->mother_avatar = $mother_path;
            }

            $fatherfile = $request->father_avatar;
            if($fatherfile)
            {
                $fatherfile_name = $request->father_avatar->getClientOriginalName();
                $folder          = $school->id.'/student/avatar';
                $father_path     = $this->uploadFile($folder,$fatherfile);

                $admission->father_avatar = $father_path;
            }
      
            $admission->mother_name               = $request->mother_name;
            $admission->mother_qualification_id   = $request->mother_qualification_id;
            $admission->mother_designation        = $request->mother_designation;
            $admission->mother_occupation         = $request->mother_occupation;
            $admission->mother_organisation       = $request->mother_organisation;
            $admission->mother_income             = $request->mother_income;
            $admission->mother_mobile_no          = $request->mother_mobile_no;
            $admission->mother_email              = $request->mother_email;
            $admission->mother_aadhar_number      = $request->mother_aadhar_number;

            $admission->emergency_contact_1             = $request->emergency_contact_1;
            $admission->relation_with_student_1         = $request->relation_with_student_1;
            $admission->emergency_contact_2             = $request->emergency_contact_2;
            $admission->relation_with_student_2         = $request->relation_with_student_2;
            $admission->medical_history                 = $request->medical_history;
            $admission->medical_details                 = $request->medical_details;
            $admission->extra_curricular_activities     = $request->extra_curricular_activities;
            $admission->activities                      = $request->activities;
            $admission->mode_of_transport               = $request->mode_of_transport;

            if($admission->mode_of_transport == 'car' || $admission->mode_of_transport == 'taxi' || $admission->mode_of_transport == 'auto')
            {
                $array=[];
                $array['driver_name']           = $request->driver_name; 
                $array['driver_mobile_number']  = $request->driver_mobile_number;

                $admission->transport_details   = $array;
            }
    
            $admission->application_status      = 'Draft';
            $admission->application_no          = 'APP-FORM-'.date('YmdHis');

            $admission->save();

            $message = trans('messages.add_success_msg',['module' => 'Admission Form']);

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $admission,
                $admin,
                ['ip' => $ip],
                LOGNAME_ADD_ADMISSION_FORM,
                $message
            ); 

            return redirect()->back()->with('successmessage',$message);
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        }   
    }  
}