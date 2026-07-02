<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers\Teacher;

use App\Http\Resources\LessonPlan as LessonPlanResource;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\LogActivity;
use App\Helpers\SiteHelper;
use App\Models\LessonPlan;
use App\Traits\Common;
use Exception;
use PDF;

class LessonPlanController extends Controller
{
    use LogActivity;
    use Common;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request,$status)
    {
        //
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
        if(Auth::user()->hasRole('principal'))
        {
            $lessonplan = LessonPlan::with('teacherlink')->whereHas('teacherlink' , function ($query) use($academic_year)
            { 
                $query->where([
                    ['school_id',Auth::user()->school_id],
                    ['academic_year_id',$academic_year->id]
                ]);
            })->where('status',$status);
            if($request->searches != null)
            {
                $lessonplan = LessonPlan::search($request->searches);
            }
        }
        else
        {
            $lessonplan = LessonPlan::with('teacherlink')->whereHas('teacherlink' , function ($query) use($academic_year)
            { 
                $query->where([
                    ['school_id',Auth::user()->school_id],
                    ['academic_year_id',$academic_year->id],
                    ['teacher_id',Auth::id()]
                ]);
            })->where('status',$status);
            if($request->searches != null)
            {
                $lessonplan = LessonPlan::search($request->searches);
            }
        }
        $lessonplan = $lessonplan->get();
        $lessonplan = LessonPlanResource::collection($lessonplan);
        
        return $lessonplan;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        if(Auth::user()->hasRole('principal'))
        {
            $role = 'principal';
        }
        $query = \Request::getQueryString();
        return view('/teacher/lessonplan/index', ['role' => $role , 'query' => $query]);
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
        $lessonplan = LessonPlan::where('id',$id)->first();
        $query = \Request::getQueryString();

        return view('/teacher/lessonplan/show', ['lessonplan' => $lessonplan , 'query' => $query]);
    }
    
    public function print($id)
    {
        try
        {
            $lessonplan = LessonPlan::where('id',$id)->first();

            $hour = date('H',strtotime($lessonplan->duration));
            $minutes = date('i',strtotime($lessonplan->duration));
            if($hour == '00')
            {
                $duration = $minutes.' minutes';
            }
            elseif($minutes == '00')
            {
                $duration = $hour.' hours';
            }
            else
            {
                $duration = $hour.' hours '.$minutes.' minutes';
            }

            $array['class']                 =   $lessonplan->teacherlink->standardLink->StandardSection;
            $array['subject']               =   $lessonplan->teacherlink->subject->name;
            $array['unit_no']               =   $lessonplan->unit_no;
            $array['unit_name']             =   $lessonplan->unit_name;
            $array['description']           =   $lessonplan->description;
            $array['title']                 =   $lessonplan->title;
            $array['duration']              =   $duration;
            $array['objective']             =   $lessonplan->objective;
            $array['materials_required']    =   $lessonplan->materials_required;
            $array['introduction']          =   $lessonplan->introduction;
            $array['procedure']             =   $lessonplan->procedure;
            $array['conclusion']            =   $lessonplan->conclusion;
            $array['notes']                 =   $lessonplan->notes;
            $array['assessment']            =   $lessonplan->assessment;
            $array['modification']          =   $lessonplan->modification;

            $pdf = PDF::loadView('/teacher/lessonplan/print', $array);

            
            $folder = Auth::user()->school->slug.'/lessonplan';
            $filename = str_replace(' ', '_', $array['title']).'_'.$array['class'].'.pdf';

            $this->putContents($folder.'/'.$filename, $pdf->output());

            $message=trans('messages.print_success_msg',['module' => 'Lesson Plan']);

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $lessonplan,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_PRINT_LESSON_PLAN,
                $message
            );

            return $pdf->download($filename);
        }
        catch(Exception $e)
        {
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
        //
        try
        {
            $lessonplan = LessonPlan::where('id',$id)->first();
            if(Gate::allows('lessonplan',$lessonplan))
            {
                $lessonplan->status     =   'cancel';
                $lessonplan->save();

                $lessonplan->delete();

                $message=trans('messages.delete_success_msg',['module' => 'Lesson Plan']);

                $ip= $this->getRequestIP();
                $this->doActivityLog(
                    $lessonplan,
                    Auth::user(),
                    ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                    LOGNAME_DELETE_LESSON_PLAN,
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
        }
    }
}