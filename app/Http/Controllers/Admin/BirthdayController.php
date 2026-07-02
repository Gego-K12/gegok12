<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers\Admin;

use App\Http\Resources\WorkAnniversary as WorkAnniversaryResource;
use App\Http\Resources\Birthday as BirthdayResource;
use App\Events\Notification\SingleNotificationEvent;
use App\Http\Requests\BirthdayReminderRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Traits\SendPushNotification;
use App\Traits\SendMessageProcess;
use App\Events\SinglePushEvent;
use App\Traits\EventProcess;
use Illuminate\Http\Request;
use App\Models\Smstemplate;
use App\Models\Userprofile;
use App\Models\User;
use Exception;
use Log;

/**
 * Class BirthdayController
 *
 * Controller handling birthday and work-anniversary reminders,
 * message dispatching and related dashboard views for users.
 *
 * @package App\Http\Controllers\Admin
 */
class BirthdayController extends Controller
{
    use SendPushNotification;
    use EventProcess;
    use SendMessageProcess;
    //

    public function showBirthday()
    {
        //
        /*$birthday = Userprofile::with('user')
                    ->whereRaw("DATE_FORMAT(date_of_birth, '%m-%d') = DATE_FORMAT(now(),'%m-%d')")
                    ->where('school_id',Auth::user()->school_id)
                    ->ByRole(6)
                    ->get();*/
        $birthday=$this->getTodayBirthday(6);                    
        $users = BirthdayResource::collection($birthday);
         
        return $users;
    }

    public function birthdayUser()
    {
        $array = [];

       /* $birthday = Userprofile::with('user')->whereHas('user', function($q){
                        $q->where('deleted_at',null);
                    })->whereRaw("DATE_FORMAT(date_of_birth, '%m-%d') = DATE_FORMAT(now(),'%m-%d')")
                    ->where('school_id',Auth::user()->school_id)
                    ->ByRole(6)
                    ->get();*/
        $birthday=$this->getTodayBirthday(6);
        $templates = Smstemplate::where('name','birthday_message')->get();
  
        $array['birthdaylist'] = $birthday;
        $array['templatelist'] = $templates;

        return $array;
    }

    public function birthday()
    {
        return view('/admin/dashboard/birthday');
    }

    public function birthdayMessage(BirthdayReminderRequest $request)
    {  
        try
        {
            $school_id = Auth::user()->school_id;
            $data = array('subject'=>'Birthday Wishes' , 'message'=>$request->message , 'type'=>'birthday');

            $datas=[];
            $datas['subject']='Birthday Wishes';
            $datas['message']=$request->message;
            $datas=(object)$datas;
       
            foreach($request->to as $to)
            {
                foreach($to['user']['parents'] as $parentStudent)
                { 
                    $parent = User::where('id',$parentStudent['parent_id'])->first();
                    $student = User::where('id',$parentStudent['student_id'])->first();
                    $mobile_no = $parent->mobile_no;
                    $mail = $parent->email;
                    $entity_id = $parent->id;
                    $month = date('m-d',strtotime($to['date_of_birth']));
                    $current_year=date('Y');
                    $date_of_birth = $current_year.'-'.$month;

                    $send = $this->selectSendMessage($datas , $school_id ,  Auth::user()->email , $parent , Auth::user() , $student);

                    $this->sendToBirthdayReminder($school_id,$entity_id,$date_of_birth,$data,$mobile_no,$mail,'birthday');
                    
                    $array=[];

                    $array['school_id']  =   Auth::user()->school_id;
                    $array['user_id']    =   $parent->id;
                    $array['message']    =   $request->message;
                    $array['type']       =   'birthday';

                    event(new SinglePushEvent($array));

                    $data = [];
                    $student = User::where('id',$to['user_id'])->first();
                    $data['user']       =   $student;
                    //$data['type']       =   'birthday';
                    $data['details']    =   trans('notification.notification_msg',['module' => 'Birthday']);
                    event(new SingleNotificationEvent($data));
                }
            }

            $res['success']= trans('birthday.success_msg');
            return $res;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        }
    }

    public function showBirthdayTeacher()
    {
        //
       /* $birthday = Userprofile::with('user')
                    ->whereRaw("DATE_FORMAT(date_of_birth, '%m-%d') = DATE_FORMAT(now(),'%m-%d')")
                    ->where('school_id',Auth::user()->school_id)
                    ->ByRole(5)
                    ->get();*/
        $birthday=$this->getTodayBirthday(5);                    
        $users = BirthdayResource::collection($birthday);
         
        return $users;
    }

    public function birthdayTeacher()
    {
        $array = [];

       /* $birthday = Userprofile::with('user')
                    ->whereRaw("DATE_FORMAT(date_of_birth, '%m-%d') = DATE_FORMAT(now(),'%m-%d')")
                    ->where('school_id',Auth::user()->school_id)
                    ->ByRole(5)
                    ->get();*/
        $birthday=$this->getTodayBirthday(5);             
        $templates = Smstemplate::where('name','birthday_message')->get();

        $array['birthdaylist'] = $birthday;
        $array['templatelist'] = $templates;

        return $array;
    }

    public function birthdayCreate()
    {
        return view('/admin/dashboard/birthdayTeacher');
    }

    public function birthdayMessageTeacher(BirthdayReminderRequest $request)
    {  
        try
        {
            $school_id = Auth::user()->school_id;
            $data = array('subject'=>'Birthday Wishes' , 'message'=>$request->message , 'type'=>'birthday');
       
            foreach($request->to as $to)
            {
                $mobile_no = $to['user']['mobile_no'];
                $mail = $to['user']['email'];
                $entity_id = $to['user']['id'];
                $send = $to['user'];
                $month = date('m-d',strtotime($to['date_of_birth']));
                $current_year=date('Y');
                $date_of_birth = $current_year.'-'.$month;

                $this->sendToBirthdayReminder($school_id,$entity_id,$date_of_birth,$data,$mobile_no,$mail,'birthday');
                
                $array=[];

                $array['school_id']  =   Auth::user()->school_id;
                $array['user_id']    =   $to['user']['members'][0]['id'];
                $array['message']    =   $request->message;
                $array['type']       =   'birthday';

                event(new SinglePushEvent($array));

                $data = [];
                $teacher = User::where('id',$to['user_id'])->first();
                $data['user']       =   $teacher;
                $data['details']    =   trans('notification.notification_msg',['module' => 'Birthday']);
                event(new SingleNotificationEvent($data));
            }

            $res['success']= trans('birthday.success_msg');
            return $res;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        }
    }

    public function showWorkAnniversary()
    {
        //
        $workanniversary = Userprofile::with('user')
                    ->whereRaw("DATE_FORMAT(joining_date, '%m-%d') = DATE_FORMAT(now(),'%m-%d')")
                    ->where('school_id',Auth::user()->school_id)
                    ->ByRole(5)
                    ->get();
                            
        $workanniversary = WorkAnniversaryResource::collection($workanniversary);
         
        return $workanniversary;
    }

    public function workAnniversary()
    {
        $array = [];

        $workanniversary = Userprofile::with('user')
                    ->whereRaw("DATE_FORMAT(joining_date, '%m-%d') = DATE_FORMAT(now(),'%m-%d')")
                    ->where('school_id',Auth::user()->school_id)
                    ->ByRole(5)
                    ->get();
        $templates = Smstemplate::where('name','work_anniversary_message')->get();

        $array['workanniversarylist'] = $workanniversary;
        $array['templatelist'] = $templates;

        return $array;
    }

    public function workAnniversaryCreate()
    {
        return view('/admin/dashboard/workAnniversary');
    }

    public function workAnniversaryMessage(BirthdayReminderRequest $request)
    {  
        try
        {
            $school_id = Auth::user()->school_id;
            $data = array('subject'=>'Work Anniversary Wishes' , 'message'=>$request->message , 'type'=>'birthday');
       
            foreach($request->to as $to)
            {
                $mobile_no = $to['user']['mobile_no'];
                $mail = $to['user']['email'];
                $entity_id = $to['user']['id'];
                $send = $to['user'];
                $month = date('m-d',strtotime($to['date_of_birth']));
                $current_year=date('Y');
                $date_of_birth = $current_year.'-'.$month;

                $this->sendToBirthdayReminder($school_id,$entity_id,$date_of_birth,$data,$mobile_no,$mail,'work_anniversary');
                
                $array=[];

                $array['school_id']  =   Auth::user()->school_id;
                $array['user_id']    =   $to['user']['members'][0]['id'];
                $array['message']    =   $request->message;
                $array['type']       =   'birthday';

                event(new SinglePushEvent($array));

                $data = [];
                $teacher = User::where('id',$to['user_id'])->first();
                $data['user']       =   $teacher;
                $data['details']    =   trans('notification.notification_msg',['module' => 'Work Anniversary']);
                event(new SingleNotificationEvent($data));
            }

            $res['success']= trans('birthday.work_anniversary_success_msg');
            return $res;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        }
    }

    public function getTodayBirthday($usergroup_id)
    {

          $birthday = Userprofile::with('user')->whereHas('user', function($q){
                        $q->where('deleted_at',null);
                    })->whereRaw("DATE_FORMAT(date_of_birth, '%m-%d') = DATE_FORMAT(now(),'%m-%d')")
                    ->where('school_id',Auth::user()->school_id)
                    ->ByRole($usergroup_id)
                    ->get();

          return $birthday;          

    }
}