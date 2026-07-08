<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\Payroll;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use League\Csv\Writer;

class ReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function exportUnpaidPayrolls()
    {
        $payrolls = Payroll::with('user')->where([['school_id', Auth::user()->school_id], ['status', 'unpaid']])->get();
        $csv = Writer::createFromFileObject(new \SplTempFileObject);

        if (count($payrolls) > 0) {
            $csv->insertOne(['payroll_no', 'salary_period', 'name', 'doj', 'designation', 'gross_salary', 'total_days', 'working_days', 'leave_taken', 'perday_salary', 'loss_of_pay', 'salary_percentage', 'Total_salary', 'earning', 'deduction', 'net_salary']);

            foreach ($payrolls as $payroll) {
                $csv->insertOne([
                    $payroll->payrollno,
                    date('F-Y', strtotime($payroll->start_date)),
                    $payroll->user->FullName,
                    date('d-m-Y', strtotime($payroll->user->userprofile->joining_date)),
                    $payroll->user->getTeacherDetails()['designation'],
                    $payroll->salary->gross_salary,
                    $payroll->getTotalDays(),
                    $payroll->getTotalDays() - $payroll->leave,
                    $payroll->leave,
                    $payroll->getDaySalary(),
                    $payroll->leave_deduction,
                    $payroll->percentage,
                    $payroll->salarypercentage(),
                    $payroll->totalearnings(),
                    $payroll->totaldeductions(),
                    $payroll->totalsalary(),

                ]);
            }
        } else {
            $csv->insertOne(['No Records Found']);
            $csv->output('Staff Export UnpaidPayrolls'.date('_d-m-Y_H:i').'.csv');
        }

        $csv->output('Staff Export UnpaidPayrolls'.date('_d-m-Y_H:i').'.csv');
    }

    public function show()
    {
        $payrolls = Payroll::with('user')->where([['school_id', Auth::user()->school_id], ['status', 'unpaid']])->get();

        return view('accountant/payroll/payslip/report', ['payrolls' => $payrolls]);
    }

    public function showbank()
    {
        $payrolls = Payroll::with('user')->where([['school_id', Auth::user()->school_id], ['status', 'unpaid']])->get();

        return view('accountant/payroll/payslip/bank', ['payrolls' => $payrolls]);
    }
}
