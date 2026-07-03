<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Api;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\ClassTeacher as ClassTeacherResource;
use App\Models\Teacherlink;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
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
        $student = User::where('id', $student_id)->first();
        $teacherlink = Teacherlink::where([
            ['school_id', Auth::user()->school_id],
            ['academic_year_id', $academic_year->id],
            ['standardLink_id', $student->studentAcademicLatest->standardLink_id],
        ])->get();
        $data['teachers'] = ClassTeacherResource::collection($teacherlink);
        $data['classTeacher']['Fullname'] = $teacherlink[0]['standardLink']['teacher']['FullName'];
        $data['classTeacher']['Avatar'] = $teacherlink[0]['standardLink']['teacher']['userprofile']['AvatarPath'];

        return response()->json([
            'success' => true,
            'message' => 'Teachers List',
            'data' => $data,
        ], 200);
    }
}
