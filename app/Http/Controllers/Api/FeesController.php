<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Api;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\FeePayment as FeePaymentResource;
use App\Models\Fee;
use App\Models\FeePayment;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class FeesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function paid($student_id)
    {
        //
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);
        $student = User::where('id', $student_id)->first();
        $fee = FeePayment::whereHas('fee', function ($query) use ($school_id, $academic_year) {
            $query->where([
                ['school_id', $school_id],
                ['academic_year_id', $academic_year->id],
                ['paid_on', '!=', null],
            ])->orWhere([
                ['standardLink_id', $student->studentAcademicLatest->standardLink_id],
                ['paid_on', '!=', null],
            ]);
        })->where('user_id', $student_id)->get();
        $fee = FeePaymentResource::collection($fee);

        return response()->json([
            'success' => true,
            'message' => 'Fees Payment List',
            'data' => $fee,
        ], 200);
    }

    public function unpaid($student_id)
    {
        //
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);
        $student = User::where('id', $student_id)->first();
        /*$fees = Fee::where([
                ['school_id',$school_id],
                ['academic_year_id',$academic_year->id],
                ['due_date','<',date('Y-m-d')],
                ['standardLink_id',null]
            ])->orWhere([
                ['school_id',$school_id],
                ['academic_year_id',$academic_year->id],
                ['due_date','<',date('Y-m-d')],
                ['standardLink_id',$student->studentAcademicLatest->standardLink_id]
            ])->get();

        $array = [];
        $i = 0;
        foreach ($fees as $fee)
        {
            $payment = FeePayment::where([['fee_id',$fee->id],['user_id',$student_id]])->exists();
            if(!$payment)
            {
                $array[$i]['name']      = $fee->name;
                $array[$i]['term']      = $fee->term;
                $array[$i]['amount']    = $fee->amount;
                $array[$i]['dueDate']   = date('d M Y',strtotime($fee->due_date));
                $i++;
            }
        }*/
        $fee = FeePayment::whereHas('fee', function ($query) use ($school_id, $academic_year) {
            $query->where([
                ['school_id', $school_id],
                ['academic_year_id', $academic_year->id],
                ['paid_on', null],
            ])->orWhere([['standardLink_id', $student->studentAcademicLatest->standardLink_id], ['paid_on', null]]);
        })->where('user_id', $student_id)->get();
        $fee = FeePaymentResource::collection($fee);

        return response()->json([
            'success' => true,
            'message' => 'Unpaid Fees List',
            'data' => $fee,
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
        $fee = FeePayment::where('id', $id)->get();
        $fee = FeePaymentResource::collection($fee);

        return response()->json([
            'success' => true,
            'message' => 'Fees Payment',
            'data' => $fee,
        ], 200);
    }
}
