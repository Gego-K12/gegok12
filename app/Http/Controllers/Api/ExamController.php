<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Api;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\ExamScheduleAPI as ExamScheduleResource;
use App\Models\Exam;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function upcomingExam($student_id)
    {
        //
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
        $student = User::where('id', $student_id)->first();
        $exam = Exam::with('schedule')->where([['school_id', Auth::user()->school_id], ['academic_year_id', $academic_year->id], ['standard_id', $student->studentAcademicLatest->standardLink_id]])->whereHas('schedule', function ($query) {
            $query->where('start_time', '>=', date('Y-m-d'));
        })->get();
        $exam = ExamScheduleResource::collection($exam);

        return response()->json([
            'success' => true,
            'message' => 'Upcoming Exam',
            'data' => $exam,
        ], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function pastExam($student_id)
    {
        //
        try {
            $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
            $student = User::where('id', $student_id)->first();
            $exam = Exam::with('schedule')->where([['school_id', Auth::user()->school_id], ['academic_year_id', $academic_year->id], ['standard_id', $student->studentAcademicLatest->standardLink_id]])->whereHas('schedule', function ($query) {
                $query->where('start_time', '<', date('Y-m-d'));
            })->get();
            $exam = ExamScheduleResource::collection($exam);

            return response()->json([
                'success' => true,
                'message' => 'Past Exam',
                'data' => $exam,
            ], 200);

        } catch (Exception $e) {
            \Log::error($e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
