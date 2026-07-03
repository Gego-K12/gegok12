<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Teacher\Payroll;

use App\Http\Controllers\Controller;
use App\Http\Resources\Payroll\PayrollListResource;
use App\Models\Payroll;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use PDF;

class PayrollController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('/teacher/payroll/payslip/index');
    }

    public function list()
    {
        $payroll = Payroll::with('user')->where([['school_id', Auth::user()->school_id], ['staff_id', Auth::id()], ['status', 'paid']])->get();
        $payroll = PayrollListResource::collection($payroll);

        return $payroll;
    }

    public function downloadpayroll($id)
    {
        $logo = null;
        $payroll = Payroll::where('id', $id)->first();
        if (Auth::user()->SchoolLogo['meta_value'] != '-') {
            $logo = Auth::user()->SchoolLogoPath;
        }
        $filename = 'payroll-'.$payroll->user->userprofile->firstname.'-'.date('Y-m-dH-i-s');
        /*  $pass_path='uploads/payroll/'.$filename.'.pdf';
          $folder=Auth::user()->school_id.'/visitorpass';*/
        $pdf = PDF::loadView('accountant.payroll.payslip.payslip', [
            'payroll' => $payroll,
            'logo' => $logo,
        ]);

        return $pdf->stream($filename.'.pdf', ['Attachment' => 0]);
    }

    public function show($id)
    {
        $payroll = Payroll::find($id);

        return view('teacher/payroll/payslip/show', ['payroll' => $payroll]);
    }
}
