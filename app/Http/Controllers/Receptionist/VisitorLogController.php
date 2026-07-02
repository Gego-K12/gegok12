<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers\Receptionist;

use App\Http\Resources\VisitorLog as VisitorLogResource;
use App\Http\Resources\User as UserResource;
use App\Http\Requests\VisitorLogRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Users\StudentUser;
use App\Models\ParentProfile;
use Illuminate\Http\Request;
use App\Traits\LogActivity;
use App\Models\Userprofile;
use App\Helpers\SiteHelper;
use App\Models\VisitorLog;
use App\Traits\Common;
use App\Models\School;
use App\Models\User;
use Exception;
use PDF;
use Log;

class VisitorLogController extends Controller
{
    use LogActivity;
    use Common;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showlist(Request $request)
    {
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);

        $visitorlog = VisitorLog::where([['school_id',Auth::user()->school_id],['academic_year_id',$academic_year->id]])->orderBy('date_of_visit','DESC')->paginate(5);       
       
        $visitorloglist = VisitorlogResource::collection($visitorlog);
        
        return $visitorloglist;
    }

    public function index()
    { 
        return view('/reception/visitorlog/index');
    }

    public function list(Request $request)
    {
        //
        $array = [];

        $teacher    =   User::BySchool(Auth::user()->school_id)->where('usergroup_id',5)->orWhere('usergroup_id',8)->ByStatus('active')->get();
        $array['teacherlist']   = UserResource::collection($teacher);

        $array['standardlist']  = SiteHelper::getStandardLinkList(Auth::user()->school_id);

        if($request->standardLink_id != null)
        {
            $studentlist = StudentUser::BySchool(Auth::user()->school_id)->ByRole(6)->ByStandard($request->standardLink_id)->get();
            $array['studentlist']   = UserResource::collection($studentlist);
        }

        return $array;
    }

    public function create()
    {
        $date = date('Y-m-d');

        return view('/reception/visitorlog/create',['date' => $date]);
    }

    public function store(VisitorLogRequest $request)
    {
        try 
        {
            $school_id = Auth::user()->school_id;
            $academic_year = SiteHelper::getAcademicYear($school_id);

            $visitorlog = new VisitorLog;

            $visitorlog->school_id              =   $school_id;
            $visitorlog->academic_year_id       =   $academic_year->id;           
            $visitorlog->student_id             =   $request->student_id;
            $visitorlog->relation               =   $request->relation;
            $visitorlog->relation_with_student  =   $request->relation_with_student;
            if($request->relation=='parent')
            {
                $student = User::where('id',$request->student_id)->first();

                if( ($request->relation_with_student == 'father') || ($request->relation_with_student == 'mother') )
                {
                    foreach($student->parents as $parent)
                    { 
                        $parentprofile = ParentProfile::where('user_id',$parent->userParent->id)->first();

                        if($request->relation_with_student == $parentprofile->relation)
                        {
                            $visitorlog->name           = $parentprofile->user->FullName;
                            $visitorlog->contact_number = $parentprofile->user->mobile_no;
                            $visitorlog->email          = $parentprofile->user->email;
                            $visitorlog->address        = $parentprofile->official_address;
                        }
                    }
                }
                elseif($request->relation_with_student == 'others')
                {
                    $visitorlog->relation_name          =   $request->relation_name;
                    $visitorlog->name                   =   $request->name;
                    $visitorlog->contact_number         =   $request->contact_number;
                    $visitorlog->email                  =   $request->email;
                    $visitorlog->address                =   $request->address;
                }             
            }
            else
            {
                $visitorlog->name                   =   $request->name;
                $visitorlog->contact_number         =   $request->contact_number;
                $visitorlog->email                  =   $request->email;
            }

            $visitorlog->employee_id            =   $request->employee_id;
            $visitorlog->visiting_purpose       =   $request->visiting_purpose;
            $visitorlog->number_of_visitors     =   $request->number_of_visitors;
            $visitorlog->company_name           =   $request->company_name;
            $visitorlog->address                =   $request->address;
            $visitorlog->date_of_visit          =   date('Y-m-d',strtotime($request->date_of_visit));
            $visitorlog->entry_time             =   $request->entry_time;
            $visitorlog->exit_time              =   $request->exit_time;
            $visitorlog->remark                 =   $request->remark;

            $visitorlog->save();

            $message = trans('messages.add_success_msg',['module' => 'Visitor Log']);

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $visitorlog,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_ADD_VISITOR_LOG,
                $message
            ); 

            $res['success'] = $message;
            return $res;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        }
    }

    public function show($id)
    {
        $visitorlog=VisitorLog::where('id',$id)->first();

        $array = [];

        $array['id']                    =  $visitorlog->id;
        $array['name']                  =  $visitorlog->name;
        $array['relation']              =  $visitorlog->relation;   
        if($visitorlog->relation == 'parent')
        {
            $array['standardLink_id']       =  $visitorlog->getstandard($visitorlog->student_id);
            $array['student_id']            =  $visitorlog->student_id;
            $array['relation_with_student'] =  $visitorlog->relation_with_student;
            if($visitorlog->relation_with_student == 'others')
            {
                $array['relation_name']    =   $visitorlog->relation_name;
            }
        } 
        else
        {           
            $array['company_name']          =  $visitorlog->company_name;
            $array['contact_number']        =  $visitorlog->contact_number;
            $array['address']               =  $visitorlog->address;
        }
        $array['email']                 =  $visitorlog->email == null ? "":$visitorlog->email;
        $array['number_of_visitors']    =  $visitorlog->number_of_visitors;
        $array['visiting_purpose']      =  $visitorlog->visiting_purpose;      
        $array['employee_id']           =  $visitorlog->employee_id;
        $array['date_of_visit']         =  date('Y-m-d',strtotime($visitorlog->date_of_visit));
        $array['entry_time']            =  $visitorlog->entry_time;
        $array['exit_time']             =  $visitorlog->exit_time;
        $array['remark']                =  $visitorlog->remark == null ? "":$visitorlog->remark;

        return $array;
    }


     /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $visitorlog = VisitorLog::where([['id',$id],['school_id',Auth::user()->school_id]])->first();

        return view('/reception/visitorlog/edit' , ['visitorlog' => $visitorlog]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(VisitorLogRequest $request, $id)
    {
        try
        {
            $visitorlog = VisitorLog::where('id',$id)->first();

            $visitorlog->student_id             =   $request->student_id;
            $visitorlog->relation               =   $request->relation;
            $visitorlog->relation_with_student  =   $request->relation_with_student;
            if($request->relation=='parent')
            {
                $student = User::where('id',$request->student_id)->first();

                if( ($request->relation_with_student == 'father') || ($request->relation_with_student == 'mother') )
                {
                    foreach($student->parents as $parent)
                    { 
                        $parentprofile = ParentProfile::where('user_id',$parent->userParent->id)->first();

                        if($request->relation_with_student == $parentprofile->relation)
                        {
                            $visitorlog->name           = $parentprofile->user->FullName;
                            $visitorlog->contact_number = $parentprofile->user->mobile_no;
                            $visitorlog->email          = $parentprofile->user->email;
                            $visitorlog->address        = $parentprofile->official_address;
                        }
                    }
                }
                elseif($request->relation_with_student == 'others')
                {
                    $visitorlog->relation_name          =   $request->relation_name;
                    $visitorlog->name                   =   $request->name;
                    $visitorlog->contact_number         =   $request->contact_number;
                    $visitorlog->email                  =   $request->email;
                    $visitorlog->address                =   $request->address;
                }             
            }
            else
            {
                $visitorlog->name                   =   $request->name;
                $visitorlog->contact_number         =   $request->contact_number;
                $visitorlog->email                  =   $request->email;
                $visitorlog->address                =   $request->address;
            }

            $visitorlog->employee_id            =   $request->employee_id;
            $visitorlog->visiting_purpose       =   $request->visiting_purpose;
            $visitorlog->number_of_visitors     =   $request->number_of_visitors;
            if($request->company_name != null)
            {
                $visitorlog->company_name           =   $request->company_name;
            }
            $visitorlog->date_of_visit          =   date('Y-m-d',strtotime($request->date_of_visit));
            $visitorlog->entry_time             =   $request->entry_time;
            $visitorlog->exit_time              =   $request->exit_time;
            if($request->remark != null)
            {
                $visitorlog->remark                 =   $request->remark;
            }

            $visitorlog->save();

            $message=trans('messages.update_success_msg',['module' => 'Visitor Log']);

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $visitorlog,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_EDIT_VISITOR_LOG,
                $message
            );

            $res['success'] = $message;
            return $res;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try 
        {
            $visitorlog=VisitorLog::where('id',$id)->first();

            $visitorlog->delete();

            $message=trans('messages.delete_success_msg',['module' => 'Visitor Log']);

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $visitorlog,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_DELETE_VISITOR_LOG,
                $message
            );

            $res['message'] = $message;
            return $res;
        }
        catch(Exception $e) 
        {
            Log::info($e->getMessage());
        }
    }

    public function view($id)
    {
        $visitorlog = VisitorLog::where('id',$id)->first();

        return view('/reception/visitorlog/print',['visitorlog' => $visitorlog]);
    }

    
    public function print($id)
    {
        try
        {
            $visitorlog=VisitorLog::where('id',$id)->first();

            if($visitorlog->relation_with_student == 'others')
            {
                $visitorlog->relation_with_student = ucfirst($visitorlog->relation_name);
            }
            else
            {
                $visitorlog->relation_with_student = ucfirst($visitorlog->relation_with_student);
            }

            $school=School::where('id',Auth::user()->school_id)->first(); 

            $array['visitorlog']=$visitorlog;
            $array['school']=$school;

            if($visitorlog->relation=='parent')
            {
                $pdf = PDF::loadView('/reception/visitorlog/parent', $array);

            }
            else
            {
                $pdf = PDF::loadView('/reception/visitorlog/other', $array);
                
            }

            $folder = Auth::user()->school->slug.'/visitorlog';

            $filename="visitorpass-".$visitorlog->name.'.pdf';

            $file=$this->putContents($folder.'/'.$filename, $pdf->output());

            $message=trans('messages.print_success_msg',['module' => 'Visitor Log']);

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $visitorlog,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_PRINT_VISITOR_LOG,
                $message
            );

            return $pdf->download($filename);
        }
        catch(Exception $e)
        {
           Log::info($e->getMessage());
        }
    }
}