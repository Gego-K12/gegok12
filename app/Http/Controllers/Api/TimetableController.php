<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers\Api;

use App\Http\Resources\API\Timetable as TimetableResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\SiteHelper;
use App\Models\Timetable;
use App\Models\User;
use App\Models\TempTimetable;
use App\Models\StandardLink;
//use App\Http\Resources\API\TempTimetable as TempTimetableResource;

class TimetableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($student_id)
    {
        //
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
        $student = User::where('id',$student_id)->first();


        // $timetable = TempTimetable::where([['school_id',Auth::user()->school_id],['academic_year_id',$academic_year->id],['standardLink_id',$student->studentAcademicLatest->standardLink_id]])->get();

        $timetable=StandardLink::with('teacher','teacherlink','standard','section','temp_timetable')->where([['id',$student->studentAcademicLatest->standardLink_id],['school_id',Auth::user()->school_id],['academic_year_id',$academic_year->id]])->first();

         $weekdays=['Monday','Tuesday','Wednesday','Thursday','Friday'];

         foreach($weekdays as $weekday){

                // if($weekday == 'Monday'){

        $Mondays= $timetable->temp_timetable->where('day',$weekday)->take(8);


         if(count($Mondays)>0){

            $i=0;

         foreach($Mondays as $Monday){



            if($Monday->subject_name!=''){




  if($Monday->teacher->userprofile->gender=='female' && $Monday->teacher->userprofile->marital_status=='married'){

         $status='Mrs.';
  }
  else if($Monday->teacher->userprofile->gender=='female' && $Monday->teacher->userprofile->marital_status!='married'){

         $status='Ms.';
  }
  else{
      $status='Mr.';
  }

                                
          $timetables[$weekday][$i]['subject_name']=$Monday->subject_name;

          $timetables[$weekday][$i]['teacher_name']=$status.''.$Monday->user->FullNAme;
            }else{
                $timetables[$weekday][$i]['subject_name']='Free';
            }

            $i++;


            }


           }

     }

  


        //$timetable = TempTimetableResource::collection($timetable);
        
        return response()->json([
            'success'   =>  true,
            'message'   =>  'Timetable',
            'data'      =>  $timetables
        ],200);
    }
}