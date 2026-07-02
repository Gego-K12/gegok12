<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers\Api\Teacher;

use App\Http\Resources\API\Teacher\ShowHoliday as ShowHolidayResource;
use App\Http\Resources\API\Teacher\ShowEvent as ShowEventResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\SiteHelper;
use App\Models\Events;
use App\Models\User;
use Carbon\Carbon;
use Exception;

class EventsController extends Controller
{
    public function show($id)
    {
        $school_id      =   Auth::user()->school_id;
        $academic_year  =   SiteHelper::getAcademicYear($school_id);
        $event = Events::where([['school_id',$school_id],['id',$id],['academic_year_id',$academic_year->id],['category','!=','holidays']])->get();
        $event= ShowEventResource::collection($event);
        return $event; 
    }

    public function index()
    {
        $school_id      =   Auth::user()->school_id;
        $academic_year  =   SiteHelper::getAcademicYear($school_id);
        $event = Events::where([['school_id',$school_id],['academic_year_id',$academic_year->id],['category','!=','holidays']])->get();
        $event= ShowEventResource::collection($event);
        return $event; 
    }

    public  function upcoming(Request $request)
    {
        $school_id      =   Auth::user()->school_id;
        $academic_year  =   SiteHelper::getAcademicYear($school_id);
        $end_date = Carbon::now();
        $event = Events::where([['school_id',$school_id],['end_date','>=',$end_date],['academic_year_id',$academic_year->id],['category','!=','holidays']])->get();
        $upcomingevent= ShowEventResource::collection($event);
        return $upcomingevent;
    }

    public function showpast()
    {
        try
        {
        $school_id      =   Auth::user()->school_id;
        $academic_year  =   SiteHelper::getAcademicYear($school_id);
        $end_date = Carbon::now();
        $event = Events::has('eventgallery', '>' , 0)->where([['school_id',$school_id],['end_date','<',$end_date],['academic_year_id',$academic_year->id],['category','!=','holidays'],['category','!=','exam']])->get();
        $pastevent= ShowEventResource::collection($event);
        //return $pastevent;
        return response()->json([
            'success'   =>  true,
            'message'   =>  'Past Event List',
            'data'      =>  $pastevent,
        ],200);
        
       }
       catch(Exception $e)
        {
        }
    }

    public function school()
    {
        $school_id      =   Auth::user()->school_id;
        $academic_year  =   SiteHelper::getAcademicYear($school_id);
        $event = Events::where([['school_id',$school_id],['academic_year_id',$academic_year->id],['category','!=','holidays']])->where('select_type','school')->get();
        $schoolevent= ShowEventResource::collection($event);
        return $schoolevent;
    }

    public function class($teacher_id)
    {
        $standards=[];
        $school_id      =   Auth::user()->school_id;
        $academic_year  =   SiteHelper::getAcademicYear($school_id);
        $teacher = User::where('id',$teacher_id)->first();
        if(count($teacher->teacherlink)>0){
        $standards=$teacher->teacherlink->pluck('standardLink.standard_id')->toArray();
        }
        
        $event = Events::whereIn('standard_id',$standards)->where([['school_id',$school_id],['select_type','class'],['academic_year_id',$academic_year->id],['category','!=','holidays']])->get();
        $classevent= ShowEventResource::collection($event);

        return response()->json([
            'success'   =>  true,
            'message'   =>  'Class Event List',
            'data'      =>  $classevent,
        ],200);
    }

    public function holidaylist()
    {
        $school_id      =   Auth::user()->school_id;
        $academic_year  =   SiteHelper::getAcademicYear($school_id);
        $holiday = Events::where([['school_id',$school_id],['academic_year_id',$academic_year->id],['category','=','holidays']])->get();
        $holiday= ShowHolidayResource::collection($holiday);
        $count=count($holiday);

        return response()->json([
            'success'   =>  true,
            'message'   =>  'Holiday list',
            'data'      =>  $holiday,
            'count'     =>  $count
        ],200);
    }
}