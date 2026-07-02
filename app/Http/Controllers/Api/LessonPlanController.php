<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers\Api;

use App\Http\Resources\API\LessonPlan as LessonPlanResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Helpers\SiteHelper;
use App\Models\Teacherlink;
use App\Models\LessonPlan;
use App\Traits\Common;
use App\Models\User;
use PDF;

class LessonPlanController extends Controller
{
    //
    use Common;

	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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

            $path = $this->getFilePath($folder.'/'.$filename);

            return response()->json([
                'success'   =>  true,
                'message'   =>  'View Lesson Plan',
                'data'      =>  $path
            ],200);
        }
        catch(Exception $e)
        {
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($student_id)
    {
        //
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);
        $student = User::where('id',$student_id)->first();

        $teacherlink = Teacherlink::where([
        		['school_id',$school_id],
        		['academic_year_id',$academic_year->id],
        		['standardLink_id',$student->studentAcademicLatest->standardLink_id]
        	])->pluck('id')->toArray();

        $lessonplan = LessonPlan::whereIn('teacher_link_id',$teacherlink)->where('status','approved')->get();

        $lessonplan = LessonPlanResource::collection($lessonplan);

        return response()->json([
            'success'   =>  true,
            'message'   =>  'Lesson Plan List',
            'data'      =>  $lessonplan
        ],200);
    }

    public function subjectIndex($student_id,$subject_id)
    {
        //
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);
        $student = User::where('id',$student_id)->first();

        $teacherlink = Teacherlink::where([
                ['school_id',$school_id],
                ['academic_year_id',$academic_year->id],
                ['standardLink_id',$student->studentAcademicLatest->standardLink_id],
                ['subject_id',$subject_id]
            ])->pluck('id')->toArray();

        $lessonplan = LessonPlan::whereIn('teacher_link_id',$teacherlink)->where('status','approved')->get();

        $lessonplan = LessonPlanResource::collection($lessonplan);

        return response()->json([
            'success'   =>  true,
            'message'   =>  'Lesson Plan List',
            'data'      =>  $lessonplan
        ],200);
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
        $lessonplan = LessonPlan::where('id',$id)->get();

        //$lessonplan = LessonPlanResource::collection($lessonplan);
        
        return response()->json([
            'success'   =>  true,
            'message'   =>  'Show Lesson Plan',
            'data'      =>  $lessonplan
        ],200);
    }
}