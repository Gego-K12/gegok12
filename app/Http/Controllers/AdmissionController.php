<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\SchoolDetail;
use Illuminate\Http\Response;

class AdmissionController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($slug)
    {
        $school = School::where('slug', $slug)->first();

        $admission_open = SchoolDetail::where('school_id', $school->id)->where('meta_key', 'admission_open')->first();

        $logo = SchoolDetail::where('school_id', $school->id)->where('meta_key', 'school_logo')->first();
        $logo = $logo->LogoPath;

        $closedetails = SchoolDetail::where('school_id', $school->id)->where('meta_key', 'admission_close_message')->first();

        return view('/pages/admission/admission', ['admission_open' => $admission_open, 'closedetails' => $closedetails, 'slug' => $slug, 'logo' => $logo]);
    }
}
