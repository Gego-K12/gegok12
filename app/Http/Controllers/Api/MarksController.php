<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Api;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\MarksAPI as MarkResource;
use App\Http\Resources\API\ShowMark as ShowMarkResource;
use App\Models\Mark;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class MarksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($student_id, $exam_id)
    {
        //
        $data = [];
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
        $student = User::where('id', $student_id)->first();
        $mark = Mark::where([
            ['school_id', Auth::user()->school_id],
            ['academic_year_id', $academic_year->id],
            ['standard_id', $student->studentAcademicLatest->standardLink_id],
            ['user_id', $student_id],
            ['exam_id', $exam_id],
        ])->get();
        $total = $mark->sum('obtained_marks');
        $average = $mark->avg('obtained_marks');
        $mark = MarkResource::collection($mark);
        $data['total'] = "$total";
        $data['average'] = "$average";
        $data['marks'] = $mark;

        return response()->json([
            'success' => true,
            'message' => 'Mark List',
            'data' => $data,
        ], 200);
    }

    public function getmarks($student_id, $exam_id)
    {
        //
        $data = [];
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
        $student = User::where('id', $student_id)->first();
        $mark = Mark::where([
            ['school_id', Auth::user()->school_id],
            ['academic_year_id', $academic_year->id],
            ['standard_id', $student->studentAcademicLatest->standardLink_id],
            ['user_id', $student_id],
            ['exam_id', $exam_id],
        ])->get();
        $total = $mark->sum('obtained_marks');
        $average = $mark->avg('obtained_marks');
        $mark = ShowMarkResource::collection($mark);
        /* $data['total']="$total";
         $data['average']="$average";
         $data['subject']=$mark->subject->name;*/
        $data['marks'] = $mark;

        return response()->json([
            'success' => true,
            'message' => 'Mark List',
            'data' => $data,
        ], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function show($id)
    {
        //
        $mark = Mark::where('id', $id)->get();
        $mark = ShowMarkResource::collection($mark);

        return response()->json([
            'success' => true,
            'message' => 'Show Mark',
            'data' => $mark,
        ], 200);
    }
}
