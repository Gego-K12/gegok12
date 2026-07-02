<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers\Student;

use App\Http\Resources\Student\Homework as HomeworkResource;
use App\Events\Notification\SingleNotificationEvent;
use App\Http\Requests\StudentHomeworkAddRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
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
    public function showList(Request $request)
    {
        //
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
        $homework = Homework::where([
                ['school_id',Auth::user()->school_id],
                ['academic_year_id',$academic_year->id],
                ['standardLink_id',Auth::user()->studentAcademicLatest->standardLink_id],
            ])->whereHas('homeworkApproval' ,function ($query) {
            $query->where('status','approved');
        });
           
            if($request->showPast == 'true')
            { 
                $homework->where('date','<',date('Y-m-d'));
            }
            else
            {
               $homework->where('date','>=',date('Y-m-d'));
            }
        if(count((array)\Request::getQueryString())>0)
        {

            if($request->search != '')
            { 
                $homework = $homework->where('description','LIKE','%'.$request->search.'%')->orWhereHas('subject',function ($q) use($request){
                    $q->where('name','LIKE','%'.$request->search.'%');
                });
            }
        }
        $homework=$homework->orderBy('id','DESC')->paginate(10);
        $homeworklist = HomeworkResource::collection($homework);
        
        return $homeworklist;
    }

    public function index()
    {
        //
        $query = \Request::getQueryString();

        $standardLink_id = Auth()->user()->studentAcademicLatest->standardLink_id;

        return view('/student/homework/index' , ['query' => $query , 'standardLink_id' => $standardLink_id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        try {

            // $request->validate([
            //     'file' => 'required',
            //     'file.*' => 'image|mimes:jpg,jpeg,png|max:8192'
            // ]);

            // if (!$request->hasFile('file')) {
            //     return response()->json([
            //         'status' => false,
            //         'message' => 'No files uploaded'
            //     ], 422);
            // }

            $files = $request->file('file');

            // Convert single file to array
            if (!is_array($files)) {
                $files = [$files];
            }

            $studentHomework = new StudentHomework;
            $studentHomework->homework_id  = $id;
            $studentHomework->user_id      = Auth::id();
            $studentHomework->submitted_on = now();
            $studentHomework->status       = 'unchecked';

            $uploadedPaths = [];

            foreach ($files as $file) {

                if ($file instanceof \Illuminate\Http\UploadedFile) {

                    $path = $this->uploadFile(
                        Auth::user()->school->slug . '/homeworks/' . $id,
                        $file
                    );

                    $uploadedPaths[] = $path;
                }
            }

            // If you added casting in model
            $studentHomework->attachment = $uploadedPaths;

            $studentHomework->save();


            $homework = Homework::findOrFail($id);

            $standardLink = StandardLink::find(
                Auth::user()->studentAcademicLatest->standardLink_id
            );

            if ($homework->teacher_id == null) {
                $teacher = User::find($standardLink->class_teacher_id);
            } else {
                $teacher = User::find($homework->teacher_id);
            }

            $student = Auth::user();

            event(new SinglePushEvent([
                'school_id' => $student->school_id,
                'user_id'   => $teacher->id,
                'message'   => $student->FullName . ' Added Homework File',
                'type'      => 'homework'
            ]));

            event(new SingleNotificationEvent([
                'user'    => $teacher,
                'details' => trans(
                    'notification.student_homework_add_msg',
                    ['student' => $student->FullName]
                )
            ]));

            $message = trans('messages.add_success_msg', [
                'module' => 'Homework File'
            ]);

            $this->doActivityLog(
                $studentHomework,
                $student,
                [
                    'ip' => $request->ip(),
                    'details' => $request->userAgent()
                ],
                LOGNAME_ADD_STUDENT_HOMEWORK,
                $message
            );

            return response()->json([
                'status' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {

            Log::error($e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Upload failed'
            ], 500);
        }
    }

    public function reply(Request $request,$homework_id)
    {
        //
        try
        {
            $homework=Homework::find($homework_id);
            $studentHomework = StudentHomework::where([['homework_id',$homework->id],['user_id',Auth::id()]])->first();
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
                    'success'   =>  $message,
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
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $studentHomework = StudentHomework::where('id',$id)->first();

        $array=[];

        $array['standardLink_name'] =   $studentHomework->homework->standardLink->StandardSection;
        $array['description']       =   $studentHomework->homework->description;
        $array['date']              =   date('Y-m-d',strtotime($studentHomework->homework->date));
        $array['attachment']        =   $studentHomework->homework->attachment==null ? '':$studentHomework->homework->AttachmentPath;
        $array['attachment_file']   =   $studentHomework->AttachmentPath;

        return $array;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try
        {
            $studentHomework = StudentHomework::with('homework')->where('id',$id)->first();

            if(Gate::allows('studentHomework',$studentHomework))
            {
                $standardLink = StandardLink::where('id',Auth::user()->studentAcademicLatest->standardLink_id)->first();
                 $homework=Homework::find($studentHomework->homework_id);
                //$teacher = User::where('id',$standardLink->class_teacher_id)->first();
                    if($homework->teacher_id==null){
                    $teacher = User::where('id',$standardLink->class_teacher_id)->first();
                    }
                    else{
                    $teacher = User::where('id',$homework->teacher_id)->first(); 
                    }   

                $studentHomework->delete();
                $student = Auth::user();

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
                $res['success'] = $message;
                return $res;
            }
            else
            {
                abort(403);
            }
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        }
    }
}