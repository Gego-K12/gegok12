<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers\Api;

use App\Http\Resources\API\Homework as HomeworkResource;
use App\Events\Notification\SingleNotificationEvent;
use App\Http\Requests\API\StudentHomeworkAddRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Events\SinglePushEvent;
use App\Models\StudentHomework;
use App\Models\StandardLink;
use Illuminate\Http\Request;
use App\Traits\LogActivity;
use App\Helpers\SiteHelper;
use App\Models\Homework;
use App\Traits\Common;
use App\Models\User;
use Exception;
use Log;

class HomeworkController extends Controller
{
    use LogActivity;
    use Common;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function pending(Request $request,$student_id)
    {
        //
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);

        $student = User::where('id',$student_id)->first();

        $query = Homework::where([
            ['standardLink_id',$student->studentAcademicLatest->standardLink_id],
            ['school_id',$school_id],
            ['academic_year_id',$academic_year->id]
        ])
        ->where('date','>=',date('Y-m-d'))
        ->where('status','publish');

        //subject filter  
        if (isset($request->subject_id)) {
            $query->where('subject_id', $request->subject_id);
        }
        
        //date filter 
        if (isset($request->date)) {
            $query->whereDate('date', $request->date);
        }

        $homeworks=$query->orderBy('date','DESC')->paginate(10);



        //$homework = HomeworkResource::collection($homework);
        $array = [];
        foreach ($homeworks as $key => $homework) 
        {
            $array[$key]['id']               =  $homework->id;
            $array[$key]['class']            =  $homework->standardLink->StandardSection;
            $array[$key]['subject']          =  $homework->subject->name==null ? '':$homework->subject->name;
             $array[$key]['teacher_name']          =  $homework->teacher->fullname==null ? '':$homework->teacher->fullname;
            $array[$key]['description']      =  strip_tags($homework->description);
            $array[$key]['date']             =  $homework->date=='' ? null:date('d-m-Y', strtotime($homework->date));
            $array[$key]['submission_date'] =  $homework->submission_date=='' ? null:date('d-m-Y', strtotime($homework->submission_date));
            $array[$key]['attachment']       =  $homework->attachment == '' ? '':$homework->AttachmentPath;
            $array[$key]['type']             =  $homework->AttachmentType;

            $studentHomework = StudentHomework::where([['homework_id',$homework->id],['user_id',$student_id]])->first();
            if($studentHomework != null)
            {
                $file = [];
                foreach ($studentHomework->AttachmentPath as $id => $attachments) 
                {
                    foreach ($attachments as $key1 => $value) 
                    {
                        if($key1 == 'path')
                        {
                            $file[$id] = $value;
                        }
                    }
                }

                $array[$key]['studentHomeworkStatus'] =  $studentHomework->status;
                $array[$key]['attachmentFile']        =  $file;
                $array[$key]['studentHomeworkId']     =  $studentHomework->id;
                $array[$key]['teacher_comments']      =  $studentHomework->comments==null ? '':$studentHomework->comments;
                $array[$key]['replycomment']          =  $studentHomework->reply_comment==null ? '':$studentHomework->reply_comment;
            }
            else
            {
                $array[$key]['studentHomeworkStatus'] =  null;
                $array[$key]['attachmentFile']        =  null;
                $array[$key]['studentHomeworkId']     =  null;
                $array[$key]['teacher_comments']      =  null;
                $array[$key]['replycomment']          =  null;
            }
        }
        
        return response()->json([
            'success'   =>  true,
            'message'   =>  'Pending Homework List',
            'data'      =>  $array == null ? []:$array,
            'meta' => [
                'current_page' => $homeworks->currentPage(),
                'last_page' => $homeworks->lastPage(),
                'per_page' => $homeworks->perPage(),
                'total' => $homeworks->total(),
                'next_page_url' => $homeworks->nextPageUrl(),
                'prev_page_url' => $homeworks->previousPageUrl(),
            ]
        ],200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function finished(Request $request,$student_id)
    {
        //
         $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);

        $student = User::where('id',$student_id)->first();

        $query = Homework::where([
                ['standardLink_id',$student->studentAcademicLatest->standardLink_id],
                ['school_id',$school_id],
                ['academic_year_id',$academic_year->id]
            ])
            ->where('date','<',date('Y-m-d'))
            ->where('status','publish');

        //subject filter  
        if (isset($request->subject_id)) {
            $query->where('subject_id', $request->subject_id);
        }
        
        //date filter 
        if (isset($request->date)) {
            $query->whereDate('date', $request->date);
        }
        $homeworks = $query->orderBy('date','DESC')->paginate(10);

        //$homework = HomeworkResource::collection($homework);
        $array = [];
        foreach ($homeworks as $key => $homework) 
        {
            $array[$key]['id']               =  $homework->id;
            $array[$key]['class']            =  $homework->standardLink->StandardSection;
            $array[$key]['subject']          =  $homework->subject->name==null ? '':$homework->subject->name;
             $array[$key]['teacher_name']          =  $homework->teacher->fullname==null ? '':$homework->teacher->fullname;
            $array[$key]['description']      =  strip_tags($homework->description);
            $array[$key]['date']             =  $homework->date=='' ? null:date('d-m-Y', strtotime($homework->date));
            $array[$key]['submission_date']  =  $homework->submission_date=='' ? null:date('d-m-Y', strtotime($homework->submission_date));
            $array[$key]['attachment']       =  $homework->attachment == '' ? '':$homework->AttachmentPath;
            $array[$key]['type']             =  $homework->AttachmentType;

            $studentHomework = StudentHomework::where([['homework_id',$homework->id],['user_id',$student_id]])->first();
            if($studentHomework != null)
            {
                $file = [];
                foreach ($studentHomework->AttachmentPath as $id => $attachments) 
                {
                    foreach ($attachments as $key1 => $value) 
                    {
                        if($key1 == 'path')
                        {
                            $file[$id] = $value;
                        }
                    }
                }

                $array[$key]['studentHomeworkStatus'] =  $studentHomework->status;
                $array[$key]['attachmentFile']        =  $file;
                $array[$key]['studentHomeworkId']     =  $studentHomework->id;
                $array[$key]['teacher_comments']      =  $studentHomework->comments==null ? '':$studentHomework->comments;
                $array[$key]['replycomment']          =  $studentHomework->reply_comment==null ? '':$studentHomework->reply_comment;
            }
            else
            {
                $array[$key]['studentHomeworkStatus'] =  null;
                $array[$key]['attachmentFile']        =  null;
                $array[$key]['studentHomeworkId']     =  null;
                $array[$key]['teacher_comments']      =  null;
                $array[$key]['replycomment']          =  null;
            }
        }
        
        return response()->json([
            'success'   =>  true,
            'message'   =>  'Finished Homework List',
            'data'      =>  $array == null ? []:$array,
            'meta' => [
                'current_page' => $homeworks->currentPage(),
                'last_page' => $homeworks->lastPage(),
                'per_page' => $homeworks->perPage(),
                'total' => $homeworks->total(),
                'next_page_url' => $homeworks->nextPageUrl(),
                'prev_page_url' => $homeworks->previousPageUrl(),
            ]
        ],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($student_id,$id)
    {
        //
        $homework = Homework::where('id',$id)->first();

        $array = [];

        //$homework = HomeworkResource::collection($homework);
            $array['id']               =  $homework->id;
            $array['class']            =  $homework->standardLink->StandardSection;
            $array['description']      =  strip_tags($homework->description);
            $array['date']             =  $homework->date=='' ? null:date('d-m-Y', strtotime($homework->date));
            $array[$key]['submission_date'] =  $homework->submission_date=='' ? null:date('d-m-Y', strtotime($homework->submission_date));
            $array['attachment']       =  $homework->attachment == '' ? '':$homework->AttachmentPath;

            $studentHomework = StudentHomework::where([['homework_id',$homework->id],['user_id',$student_id]])->first();
            if($studentHomework != null)
            {
                if ($studentHomework->status == 'checked') 
                {
                    $studentHomeworkStatus = 1;
                }
                else
                {
                    $studentHomeworkStatus = 0;
                }
                $file = [];
                foreach ($studentHomework->AttachmentPath as $id => $attachments) 
                {
                    foreach ($attachments as $key => $value) 
                    {
                        if($key == 'path')
                        {
                            $file[$id] = $value;
                        }
                    }
                }

                $array['studentHomeworkStatus']   =  $studentHomeworkStatus;
                $array['attachmentFile']          =  $file;
                $array['studentHomeworkId']       =  $studentHomework->id;
                $array['teacher_comments']        =  $studentHomework->comments;
            }
            else
            {
                $array['studentHomeworkStatus'] =  null;
                $array['attachmentFile']        =  null;
                $array['studentHomeworkId']     =  null;
                $array['teacher_comments']      =  null;
            }
        
        return response()->json([
            'success'   =>  true,
            'message'   =>  'Show Homework',
            'data'      =>  $array == null ? []:$array
        ],200);
    }

    public function store(StudentHomeworkAddRequest $request,$homework_id,$student_id)
    {
        //
        try
        {
                $files = $request->file;
                if(count($request->file))
                {
                    $student_homework = new StudentHomework;

                    $student_homework->homework_id       = $homework_id;
                    $student_homework->user_id           = $student_id;
                    $student_homework->submitted_on      = date('Y-m-d');
                    $student_homework->status            = 'unchecked';

                    $path = [];
                    $i = 1;
                    foreach($files as $file) 
                    {
                        $path[$i] = $this->uploadFile(Auth::user()->school->slug.'/homeworks/'.$homework_id,$file); 
                        $i++;     
                    }
                    $student_homework->attachment = $path;
                         
                    $student_homework->save(); 

                    $homework=Homework::find($homework_id);

                    $student = User::where('id',$student_id)->first();
                     
                    $standardLink = StandardLink::where('id',$student->studentAcademicLatest->standardLink_id)->first();
                    if($homework->teacher_id==null){
                    $teacher = User::where('id',$standardLink->class_teacher_id)->first();
                    }
                    else{
                    $teacher = User::where('id',$homework->teacher_id)->first(); 
                    }   

                    $array=[];

                    $array['school_id']  =   Auth::user()->school_id;
                    $array['user_id']    =   $teacher->id;
                    $array['message']    =   $student->FullName.' Added Homework File';
                    $array['type']       =   'homework';

                    event(new SinglePushEvent($array));

                    $data = [];

                    $data['user']       =   $teacher;
                    $data['details']    =   trans('notification.student_homework_add_msg',['student' => $student->FullName]);

                    event(new SingleNotificationEvent($data));

                    $message=trans('messages.add_success_msg',['module' => 'Homework File']);

                    $ip= $this->getRequestIP();
                    $this->doActivityLog(
                        $student_homework,
                        Auth::user(),
                        ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                        LOGNAME_ADD_STUDENT_HOMEWORK,
                        $message
                    );
                }
            return response()->json([
                'success'   =>  true,
                'message'   =>  $message,
            ],200);          
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }

    public function replycomment(Request $request,$homework_id,$student_id)
    {
        //
        try
        {
            $homework=Homework::find($homework_id);
            $studentHomework = StudentHomework::where([['homework_id',$homework->id],['user_id',$student_id]])->first();
            if($studentHomework){
            $studentHomework->reply_comment      = $request->reply_comment;
            $studentHomework->save();

            $student = User::where('id',$studentHomework->user_id)->first();
                    $standardLink = StandardLink::where('id',$student->studentAcademicLatest->standardLink_id)->first();
                    if($homework->teacher_id==null){
                    $teacher = User::where('id',$standardLink->class_teacher_id)->first();
                    }
                    else{
                    $teacher = User::where('id',$homework->teacher_id)->first(); 
                    }   

                    $array=[];

                    $array['school_id']  =   Auth::user()->school_id;
                    $array['user_id']    =   $teacher->id;
                    $array['message']    =   $student->FullName.' Replied for your comment';
                    $array['type']       =   'homework';

                    event(new SinglePushEvent($array));

                    $data = [];

                    $data['user']       =   $teacher;
                    $data['details']    =   trans('notification.student_homework_reply_msg',['student' => $student->FullName,'module'=>'Homework']);

                    event(new SingleNotificationEvent($data));

                    $message=trans('messages.add_reply_msg',['module' => 'Homework']);

                    $ip= $this->getRequestIP();
                    $this->doActivityLog(
                        $homework,
                        Auth::user(),
                        ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                        LOGNAME_REPLY_STUDENT_HOMEWORK,
                        $message
                    );

               return response()->json([
                    'success'   =>  true,
                    'message'   =>  $message,
                ],200); 
           }

           return response()->json([
                    'success'   =>  false,
                    'message'   =>  "Student homework doesn`t exists",
                ],406); 
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            dd($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id,$student_id)
    {
        //
        try
        {
            $studentHomework = StudentHomework::where('id',$id)->first();

            if(Gate::allows('studentHomework',$studentHomework))
            {
                $student = User::where('id',$student_id)->first();
                $standardLink = StandardLink::where('id',$student->studentAcademicLatest->standardLink_id)->first();
                $homework=Homework::find($studentHomework->homework_id);
                //$teacher = User::where('id',$standardLink->class_teacher_id)->first();
                    if($homework->teacher_id==null){
                    $teacher = User::where('id',$standardLink->class_teacher_id)->first();
                    }
                    else{
                    $teacher = User::where('id',$homework->teacher_id)->first(); 
                    }   

                $studentHomework->delete();

                $array = [];

                $array['school_id'] = $student->school_id;
                $array['user_id']   = $teacher->id;
                $array['message']   = $student->FullName.' Deleted Homework File';
                $array['type']      = 'homework';

                event(new SinglePushEvent($array));

                $data = [];

                $data['user']       =   $teacher;
                $data['details']    =   trans('notification.student_homework_delete_msg',['student' => $student->FullName]);

                event(new SingleNotificationEvent($data));

                $message=trans('messages.delete_success_msg',['module' => 'Homework File']);

                $ip= $this->getRequestIP();
                $this->doActivityLog(
                    $studentHomework,
                    Auth::user(),
                    ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                    LOGNAME_DELETE_STUDENT_HOMEWORK,
                    $message
                );

                return response()->json([
                    'success'   =>  true,
                    'message'   =>  $message,
                ],200);
            }
            else
            {
                abort(403);
            }
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }
}