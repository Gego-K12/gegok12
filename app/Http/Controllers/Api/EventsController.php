<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers\Api;

use App\Http\Resources\API\ShowEvent as ShowEventResource;
use App\Http\Resources\API\ShowHoliday as ShowHolidayResource;
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
        $event = Events::where([['school_id',$school_id],['end_date','>=',$end_date],['academic_year_id',$academic_year->id],['category','!=','holidays'],['status','active']])->get();
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
            \Log::error($e->getMessage());
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

    public function class($student_id)
    {
        $school_id      =   Auth::user()->school_id;
        $academic_year  =   SiteHelper::getAcademicYear($school_id);
        $student = User::where('id',$student_id)->first();
        $event = Events::where([['school_id',$school_id],['select_type','class'],['standard_id',$student->studentAcademicLatest->standardLink_id],['academic_year_id',$academic_year->id],['category','!=','holidays']])->get();
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
        //return $holiday; 
    }


}