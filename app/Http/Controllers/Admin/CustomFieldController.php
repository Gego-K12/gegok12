<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class CustomFieldController extends Controller
{
    public function index()
    {
        return view('admin.custom-fields.index');
    }

    public function create()
    {
        return view('admin.custom-fields.form');
    }

    public function edit($id)
    {
        return view('admin.custom-fields.form', ['id' => $id]);
    }
}
