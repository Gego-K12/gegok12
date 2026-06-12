<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers\Teacher;

use App\Http\Resources\ClassUpcomingExam as ClassUpcomingExamResource;
use App\Http\Resources\Classwall\ClassPost as ClassPostResource;
use App\Http\Resources\Attendance as AttendanceResource;
use App\Http\Resources\Homework as HomeworkResource;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExamSchedule;
use App\Models\StandardLink;
use App\Helpers\SiteHelper;
use App\Models\Attendance;
use App\Models\Homework;
use App\Models\User;
use App\Models\Post;
use Carbon\Carbon;

class StandardsLinkDetailsController extends Controller
{
    //

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showHomework(Request $request)
    {
        //
        $homework = Homework::where('school_id',Auth::user()->school_id)->where('date','>=',date('Y-m-d'))->orderBy('date','DESC');
        if(count((array)\Request::getQueryString())>0)
        {
            if($request->showPast == 'true')
            { 
                $homework = $homework->orWhere('date','<',date('Y-m-d'));
            }

            if($request->standardLink_id != '')
            { 
                $homework = $homework->where('standardLink_id',$request->standardLink_id);
            }
        }
        $homework=$homework->get();
        $homeworklist = HomeworkResource::collection($homework);
        
        return $homeworklist;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getStudentAttendance($id)
    {
        //
        $standardLink = StandardLink::where('id',$id)->first();

        if(Gate::allows('standardlink',$standardLink))
        {
            $array = [];
            $academic_year  = SiteHelper::getAcademicYear(Auth::user()->school_id);
            $startDate  = date('Y-m-d',strtotime($academic_year->start_date));  
            $endDate    = date('Y-m-d',strtotime($academic_year->end_date));
            
            $attendances    = Attendance::with('user')->where([
                ['school_id',Auth::user()->school_id],
                ['academic_year_id',$academic_year->id],
                ['standardLink_id',$id],
                ['status',0],
                ['date','>=',$startDate],
                ['date','<=',$endDate]
            ])->orderBy('date','DESC')->get()->groupBy([function($attendance) {
                    return Carbon::parse($attendance->date)->format('M Y'); 
                },'user_id','session']);
            $i = 0;
            
            foreach ($attendances as $key => $attendance) 
            {
                $array['months'][$i] = $key;
                foreach ($attendance as $user_id => $sessions) 
                {
                    $user = User::where('id',$user_id)->first();
                    foreach ($sessions as $session => $value) 
                    {
                        $array['students'][$user->name]['FullName'] = $user->FullName;
                        if($attendance[$user_id] != null)
                        {
                            $array['students'][$user->name][$key] = count($value)*0.5;
                        }
                        else
                        {
                            $array['students'][$user->name][$key] = 0;
                        }
                    }
                }
                $i++;
            }
            return $array;
        }
        else
        {
            abort(403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getAttendance($id)
    {
        $standardLink = StandardLink::findOrFail($id);

        if (Gate::allows('standardlink', $standardLink))
        {
            $array = [];

            $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);

            $array['select_month'] = Carbon::now()->format('m-Y');

            // Months
            $months = [];
            $start = strtotime('last month', strtotime($academic_year->start_date));
            $now   = strtotime($academic_year->end_date);

            while (($start = strtotime('next month', $start)) <= $now) 
            {
                $months[] = [
                    'id'   => date('m-Y', $start),
                    'name' => date('M Y', $start),
                ];
            }

            $array['months'] = $months;

            // Current Month Range
            $startDate = Carbon::now()->firstOfMonth()->format('Y-m-d');  
            $endDate   = Carbon::now()->lastOfMonth()->format('Y-m-d');

            // Attendance list
            $attendances = Attendance::where([
                ['school_id', Auth::user()->school_id],
                ['academic_year_id', $academic_year->id],
                ['standardLink_id', $id],
                ['status', 0],
                ['date', '>=', $startDate],
                ['date', '<=', $endDate]
            ])
            ->orderBy('date', 'DESC')
            ->get();

            $array['attendances'] = AttendanceResource::collection($attendances)
                ->groupBy([
                    fn($a) => Carbon::parse($a->date)->format('d M Y'),
                    'session'
                ]);

            // Chart Data
            $attendancechart = Attendance::where([
                ['school_id', Auth::user()->school_id],
                ['academic_year_id', $academic_year->id],
                ['standardLink_id', $id],
                ['date', '>=', $startDate],
                ['date', '<=', $endDate]
            ])
            ->orderBy('date', 'ASC') // better for chart
            ->get()
            ->groupBy([
                fn($a) => Carbon::parse($a->date)->format('d M Y'),
                'session',
                'status'
            ]);

            $i = 0;

            foreach ($attendancechart as $date => $attendance)
            {
                $array['dates'][$i] = $date;

                // default values (important)
                $forenoonPresent  = 0;
                $forenoonAbsent   = 0;
                $afternoonPresent = 0;
                $afternoonAbsent  = 0;

                foreach ($attendance as $session => $student)
                {
                    if ($session === 'forenoon')
                    {
                        $forenoonPresent = count($student[1] ?? []);
                        $forenoonAbsent  = count($student[0] ?? []);
                    }
                    else
                    {
                        $afternoonPresent = count($student[1] ?? []);
                        $afternoonAbsent  = count($student[0] ?? []);
                    }
                }

                // assign directly
                $array['forenoon_present'][]  = $forenoonPresent;
                $array['forenoon_absent'][]   = $forenoonAbsent;
                $array['afternoon_present'][] = $afternoonPresent;
                $array['afternoon_absent'][]  = $afternoonAbsent;

                $i++;
            }

            return $array;
        }

        abort(403);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showAttendance(Request $request,$id)
    {
        //
        $standardLink = StandardLink::where('id',$id)->first();

        if(Gate::allows('standardlink',$standardLink))
        {
            $date = Carbon::createFromFormat('m-Y', $request->select_month);
            $array = [];
            $array['select_month']  = $request->select_month;
            $academic_year  = SiteHelper::getAcademicYear(Auth::user()->school_id);
            $months = [];
            $start = strtotime('last month', strtotime($academic_year->start_date));
            $now = strtotime($academic_year->end_date);
            $i = 0;
            // while(($start = strtotime('next month', $start)) <= $now) 
            // {
            //     $array['months']->$i->id = date('m-Y', $start);
            //     $array['months']->$i->name = date('M Y', $start);
            //     $i++;
            // }
            
            // new
            while (($start = strtotime('next month', $start)) <= $now) 
            {
                $months[] = [
                    'id' => date('m-Y', $start),
                    'name' => date('M Y', $start),
                ];
            }

            $array['months'] = $months;

            $startDate      = Carbon::parse($date)->firstOfMonth()->format('Y-m-d');  
            $endDate        = Carbon::parse($date)->lastOfMonth()->format('Y-m-d'); 
            
            $attendances    = Attendance::where([
                ['school_id',Auth::user()->school_id],
                ['academic_year_id',$academic_year->id],
                ['standardLink_id',$id],
                ['status',0],
                ['date','>=',$startDate],
                ['date','<=',$endDate]
            ])->orderBy('date','DESC')->get();
            $array['attendances'] = AttendanceResource::collection($attendances)->groupBy([function($attendance) {
                    return Carbon::parse($attendance->date)->format('d M Y'); 
                },'session']);

            $attendancechart  = Attendance::where([
                ['school_id',Auth::user()->school_id],
                ['academic_year_id',$academic_year->id],
                ['standardLink_id',$id],
                ['date','>=',$startDate],
                ['date','<=',$endDate]
            ])->orderBy('date','DESC')->get()->groupBy([function($attendancechart) {
                    return Carbon::parse($attendancechart->date)->format('d M Y'); 
                },'session','status']);

            $i = 0;
            foreach ($attendancechart as $date => $attendance) 
            {
                $array['dates'][$i]  =   $date;
                foreach ($attendance as $session => $student) 
                { 
                    if($session == 'forenoon')
                    {
                        $forenoonpresent[$i]    = count($student[1]);
                        $forenoonabsent[$i]     = count($student[0]);
                    }
                    else
                    {
                        $afternoonpresent[$i]   = count($student[1]);
                        $afternoonabsent[$i]    = count($student[0]);
                    }
                }
                for ($j = 0 ;$j < count($attendancechart) ; $j++) 
                {
                   $array['forenoon_present'][$j]    = $forenoonpresent[$j];
                   $array['forenoon_absent'][$j]     = $forenoonabsent[$j];
                   $array['afternoon_present'][$j]   = $afternoonpresent[$j];
                   $array['afternoon_absent'][$j]    = $afternoonabsent[$j];
                }
                $i++;
            }
            
            return $array;
        }
        else
        {
            abort(403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showUpcomingExams($id)
    {
        //
        $standardLink = StandardLink::where('id',$id)->first();

        if(Gate::allows('standardlink',$standardLink))
        {
            $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
            if(class_exists('Gegok12\Exam\Models\ExamSchedule'))
            {
                $upcomingExams  = \Gegok12\Exam\Models\ExamSchedule::whereHas('exam',function($query) use($academic_year)
                    { 
                        $query->where('academic_year_id',$academic_year->id);
                    })->where('standard_id',$standardLink->id)->where('start_time','>=',date('Y-m-d H:i:s'))->orderBy('start_time','ASC')->get(); 
            }
            else
            {
                $upcomingExams  = ExamSchedule::whereHas('exam',function($query) use($academic_year)
                    { 
                        $query->where('academic_year_id',$academic_year->id);
                    })->where('standard_id',$standardLink->id)->where('start_time','>=',date('Y-m-d H:i:s'))->orderBy('start_time','ASC')->get();
            }
            $upcomingExams = ClassUpcomingExamResource::collection($upcomingExams)->groupBy('exam_id');

            return $upcomingExams;
        }
        else
        {
            abort(403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showClassWall($id)
    {
        //
        $standardLink = StandardLink::where('id',$id)->first();

        if(Gate::allows('standardlink',$standardLink))
        {
            $school_id = Auth::user()->school_id;
            $academic_year = SiteHelper::getAcademicYear($school_id);
            
            $posts = Post::where([['school_id',$school_id],['academic_year_id',$academic_year->id],['is_posted',1],['visible_for',$id]])->orderBy('post_created_at','DESC')->get(); 

            $posts = ClassPostResource::collection($posts);

            return $posts;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showComments($post_id)
    {
        //
        $post = Post::where('id',$post_id)->first();
        
        $array = [];

        $array['comment_list']['comments_count']    = count($post->PostComments);
        $array['comment_list']['comments']          = $post->PostComments;

        return $array;
    }
}