<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Api;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\Discipline as DisciplineResource;
use App\Models\Discipline;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class DisciplineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($student_id)
    {
        //
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);

        $discipline = Discipline::where([['user_id', $student_id], ['academic_year_id', $academic_year->id], ['notify_parents', 1], ['type', 'discipline']])->get();

        $discipline = DisciplineResource::collection($discipline);

        return response()->json([
            'success' => true,
            'message' => 'Discipline List',
            'data' => $discipline,
        ], 200);
    }

    public function performance($student_id)
    {
        //
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);

        $discipline = Discipline::where([['user_id', $student_id], ['academic_year_id', $academic_year->id], ['notify_parents', 1], ['type', '!=', 'discipline']])->get();

        $discipline = DisciplineResource::collection($discipline);

        return response()->json([
            'success' => true,
            'message' => 'Discipline List',
            'data' => $discipline,
        ], 200);
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
        $discipline = Discipline::where('id', $id)->get();

        $discipline = DisciplineResource::collection($discipline);

        return response()->json([
            'success' => true,
            'message' => 'Show Discipline',
            'data' => $discipline,
        ], 200);
    }
}
