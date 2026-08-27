<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\TimetableEdit as TimetableEditResource;
use App\Http\Resources\StandardLink as StandardLinkResource;
use App\Http\Resources\TeacherLink as TeacherLinkResource;
use App\Http\Requests\TimetableUpdateRequest;
use App\Http\Requests\TimetableAddRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\StandardLink;
use Illuminate\Http\Request;
use App\Models\Teacherlink;
use App\Helpers\SiteHelper;
use App\Traits\LogActivity;
use Gegok12\Timetable\Models\Timetable;
use App\Traits\Common;
use Exception;
use Log;

class TimetableController extends Controller
{
    use LogActivity;
    use Common;

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        //
        $array = [];
        $school_id          = Auth::user()->school_id;

        $academic_year      = SiteHelper::getAcademicYear($school_id);

        $standardLink       =   StandardLink::with('standard','section')->where([['school_id',$school_id],['academic_year_id',$academic_year->id]])->get();
        $standardLinklist   =   StandardLinkResource::collection($standardLink);

        $teacherlink        =   Teacherlink::where([['school_id',$school_id],['academic_year_id',$academic_year->id]])->get();

        //dd($teacherlink->teacher->fullname);

        $teacherLinklist    =   TeacherLinkResource::collection($teacherlink)->groupBy('standardLink_id');

        $array['standardLinklist']  = $standardLinklist;
        $array['teacherLinklist']   = $teacherLinklist;

        return $array;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
        //
        $standard = \Request::get('standardLink_id') ? \Request::get('standardLink_id'):'';

        if($_SERVER['HTTP_REFERER'] != null)
        {
            $prev_url = $_SERVER['HTTP_REFERER'];
        }
        else
        {
            $prev_url = url('/admin/standardLink/show/'.$standard);
        }

        return view('/admin/timetable/create' ,['standard' => $standard , 'prev_url' => $prev_url]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TimetableAddRequest $request)
    {
        //
        try
        {

            //dd("jJ");
            $school_id          = Auth::user()->school_id;
            $academic_year      = SiteHelper::getAcademicYear($school_id);
            for($i=0 ; $i < $request->count ; $i++)
            {
                $day        = 'day'.$i;

                $timetable = new Timetable;

                $timetable->school_id           =   $school_id;
                $timetable->academic_year_id    =   $academic_year->id;
                $timetable->standardLink_id     =   $request->standardLink_id;
                $timetable->day                 =   $request->$day;

                $array=[];

                for($j=0 ; $j < $request->periodCount ; $j++)
                {
                    $teacher_name    = 'teacher_name'.$i.$j;
                    $period     = 'period'.$i.$j;
                    $subject    = 'subject_id'.$i.$j;
                    $start_time = 'start_time'.$i.$j;
                    $end_time   = 'end_time'.$i.$j;

                    $array[$i.$j]=array(  'period'      =>  $request->$period ,
                                        'subject_id'    =>  $request->$subject,
                                        'teacher_name'  =>  $request->$teacher_name,
                                        'start_time'    =>  date('h:i A',strtotime($request->$start_time)),
                                        'end_time'      =>  date('h:i A',strtotime($request->$end_time)),
                                    );

                    $timetable->schedule    =   $array;
                }
                $timetable->status = 1;

                $timetable->save();
            }

            $message = trans('messages.add_success_msg',['module' => 'Timetable']);

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $timetable,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_ADD_TIMETABLE,
                $message
            );

            $res['success'] = $message;

            return $res;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($standardLink_id)
    {
        //
        $timetable = Timetable::where('standardLink_id',$standardLink_id)->get();
        $timetable = TimetableEditResource::collection($timetable);
        $array = [];
        $school_id          = Auth::user()->school_id;

        $academic_year      = SiteHelper::getAcademicYear($school_id);

        $teacherlink        =   Teacherlink::where([['school_id',$school_id],['academic_year_id',$academic_year->id]])->get();
        $teacherLinklist    =   TeacherLinkResource::collection($teacherlink)->groupBy('standardLink_id');

        $array['teacherLinklist']   = $teacherLinklist;
        $array['timetable']         = $timetable;

        return $array;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($standardLink_id)
    {
        //
        $timetable = Timetable::where('standardLink_id',$standardLink_id)->get();

        if($_SERVER['HTTP_REFERER'] != null)
        {
            $prev_url = $_SERVER['HTTP_REFERER'];
        }
        else
        {
            $prev_url = url('/admin/standardLink/show/'.$standardLink_id);
        }


        return view('/admin/timetable/edit' ,['standardLink_id' => $standardLink_id , 'prev_url' => $prev_url]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TimetableUpdateRequest $request, $standardLink_id)
    {
        //
        try
        {
            $school_id          = Auth::user()->school_id;
            $academic_year      = SiteHelper::getAcademicYear($school_id);

            $timetables = Timetable::where('standardLink_id',$standardLink_id)->get();
            foreach ($timetables as $timetable) 
            {
                $timetable->status  =   0;
                $timetable->save();
                $timetable->delete();
            }
            for($i=0 ; $i < $request->count ; $i++)
            {
                $day        = 'day'.$i;

                $timetable = new Timetable;

                $timetable->school_id           =   $school_id;
                $timetable->academic_year_id    =   $academic_year->id;
                $timetable->standardLink_id     =   $request->standardLink_id;
                $timetable->day                 =   $request->$day;

                $array=[];

                for($j=0 ; $j < $request->periodCount ; $j++)
                {
                    $teacher_name    = 'teacher_name'.$i.$j;
                    $period     = 'period'.$i.$j;
                    $subject    = 'subject_id'.$i.$j;
                    $start_time = 'start_time'.$i.$j;
                    $end_time   = 'end_time'.$i.$j;

                    $array[$i.$j]=array(  'period'      =>  $request->$period ,
                                        'subject_id'    =>  $request->$subject,
                                        'teacher_name'  =>  $request->$teacher_name,
                                        'start_time'    =>  date('h:i A',strtotime($request->$start_time)),
                                        'end_time'      =>  date('h:i A',strtotime($request->$end_time)),
                                    );

                    $timetable->schedule    =   $array;
                }
                $timetable->status = 1;

                $timetable->save();
            }

            $message = trans('messages.update_status_success_msg',['module' => 'Timetable']);

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $timetable,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_EDIT_TIMETABLE,
                $message
            );

            $res['success'] = $message;

            return $res;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }
}
