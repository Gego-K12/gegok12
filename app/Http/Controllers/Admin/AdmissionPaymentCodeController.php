<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2026 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdmissionPaymentCodeController extends Controller
{
    public function index()
    {
        return view('admin.admission-payment-codes.index');
    }

    public function create()
    {
        return view('admin.admission-payment-codes.form');
    }
}
