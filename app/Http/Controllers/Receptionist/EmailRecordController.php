<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers\Receptionist;
use App\Http\Resources\EmailRecord as EmailRecordResource;
use App\Http\Resources\User as UserResource;;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\EmailRecordRequest;
use App\Helpers\SiteHelper;
use App\Models\EmailRecord;
use App\Models\User;
use App\Traits\Common;
use App\Traits\LogActivity;
use Log;

class EmailRecordController extends Controller
{
    use Common;
    use LogActivity;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showlist(Request $request)
    {
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);

        $emailrecord = EmailRecord::where([['school_id',Auth::user()->school_id],['academic_year_id',$academic_year->id]])->get();
       
       
        $emailrecordlist = EmailRecordResource::collection($emailrecord);
        
        return $emailrecordlist;
    }
    
    public function index()
    { 
        //$emailrecord=emailrecord::where('id',$id)->first();
        return view('/reception/emailrecord/index');
    }


    public function create()
    {
        return view('/reception/emailrecord/create');
    }

    public function store(EmailRecordRequest $request)
    {
        try 
        {
            $school_id = Auth::user()->school_id;

            $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);

            $emailrecord=new EmailRecord;

            $emailrecord->school_id=$school_id;
            $emailrecord->academic_year_id=$academic_year->id;
            $emailrecord->type=$request->type;
            $emailrecord->subject=$request->subject;
            $emailrecord->sender_email=$request->sender_email;
            $emailrecord->receiver_email=$request->receiver_email;
           
            $emailrecord->date=$request->date;
            $emailrecord->time=$request->time;

            $file = $request->file('attachment');
            if($file)
            {
                $folder=Auth::user()->school->slug.'/emailrecord';
                $path = $this->uploadFile($folder,$file);
                $emailrecord->attachment = $path; 
            }

            $emailrecord->save();

            $message = trans('messages.add_success_msg',['module' => 'Email Record']);

              $ip= $this->getRequestIP();
                $this->doActivityLog(
                  $emailrecord,
                  Auth::user(),
                  ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                  LOGNAME_ADD_EMAIL_RECORD,
                  $message
                ); 

            $res['success']=trans('messages.add_success_msg',['module' => 'Email Record']);
            return $res;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        }

    }

     public function show($id)
    {
        $emailrecord=EmailRecord::where('id',$id)->get();

        $emailrecord=EmailRecordResource::collection($emailrecord);

        return $emailrecord;
    }


     /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $emailrecord = EmailRecord::where([['id',$id],['school_id',Auth::user()->school_id]])->first();

        return view('/reception/emailrecord/edit' , ['emailrecord' => $emailrecord]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

          $school_id = Auth::user()->school_id;

          $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
        try
        {
            $emailrecord=EmailRecord::find($id);

           
            $emailrecord->school_id=$school_id;
            $emailrecord->academic_year_id=$academic_year->id;
            $emailrecord->type=$request->type;
            $emailrecord->subject=$request->subject;
            $emailrecord->sender_email=$request->sender_email;
            $emailrecord->receiver_email=$request->receiver_email;
           
            $emailrecord->date=$request->date;
            $emailrecord->time=$request->time;

            $file = $request->file('attachment');
            if($file)
            {
                $folder=Auth::user()->school->slug.'/emailrecord';
                $path = $this->uploadFile($folder,$file);
                $emailrecord->attachment = $path; 
            }
          

            $emailrecord->save();


            $message=trans('messages.update_success_msg',['module' => 'Email Record']);

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $emailrecord,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_EDIT_POSTAL_RECORD,
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
            $emailrecord=EmailRecord::where('id',$id)->first();
          
            $emailrecord->delete();

            $message=trans('messages.delete_success_msg',['module' => 'Email Record']);

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $emailrecord,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_DELETE_EMAIL_RECORD,
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
}