<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class ApkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function parentApp()
    {
        //
        $file = file_get_contents(public_path('/android-apk/parent_apk_detail.json'));

        return $file;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function teacherApp()
    {
        //
        $file = file_get_contents(public_path('/android-apk/teacher_apk_detail.json'));

        return $file;
    }
}
