<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payroll\PayrollDetailRequest;
use App\Http\Requests\Payroll\PayrollRequest;
use App\Http\Requests\Payroll\PayrollUpdateRequest;
use App\Http\Resources\Payroll\PayrollDetailResource;
use App\Http\Resources\Payroll\PayrollListResource;
use App\Http\Resources\Payroll\SalaryResource;
use App\Models\Payroll;
use App\Models\PayslipItem;
use App\Models\Salary;
use App\Models\TeacherLeaveApplication;
use App\Traits\Common;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Log;
use PDF;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class PayrollController extends Controller
{
    use Common;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('accountant/payroll/payslip/index');
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
        $pdf = PDF::loadView('accountant/payroll/payslip/payslip', [
            'payroll' => $payroll,
            'logo' => $logo,
        ]);

        return $pdf->stream($filename.'.pdf', ['Attachment' => 0]);

    }

    public function showlist(Request $request)
    {
        // $salary=Payroll::where('school_id',Auth::user()->school_id)->with('user')->get();
        $payroll = Payroll::with('user')->where([['school_id', Auth::user()->school_id], ['status', $request->status]])->orderBy('id', 'desc')->paginate(20);
        $payroll = PayrollListResource::collection($payroll);

        return $payroll;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('accountant/payroll/payslip/create');
    }

    public function details(PayrollDetailRequest $request)
    {
        $array = [];
        $start_date = date('Y-m-d', strtotime($request->start_date));
        $end_date = date('Y-m-d', strtotime($request->end_date));
        $salary = Salary::where([['school_id', Auth::user()->school_id], ['staff_id', $request->staff_id], ['effective_date', '>=', date('Y-m-d')]])->with('salaryitems')->first();
        // $leave=TeacherLeaveApplication::where('user_id',$request->staff_id)->where([['from_date','>=',$start_date],['status','approved']])->first();
        // $from_date=date('Y-m-d',strtotime($leave->from_date));
        // $to_date=date('Y-m-d',strtotime($leave->to_date));
        // $leavedays=0;
        $totaldays = $this->dayscount($start_date, $end_date) + 1;
        $array['totaldays'] = $totaldays;
        /*if($leave){
        if($to_date>$end_date){
         $leavedays=$this->dayscount($from_date,$end_date)+1;
        }else
        {
         $leavedays=$this->dayscount($from_date,$to_date)+1;
        }}*/
        // $array['leavecount']=$leavedays;
        $array['salary'] = new SalaryResource($salary);
        $array['perday_salary'] = round($salary->gross_salary / $totaldays);

        return $array;

    }

    public function dayscount($start_date, $end_date)
    {
        $days = Carbon::parse($start_date)->diffInDays(Carbon::parse($end_date));

        return $days;
    }

    protected function getpayrollnumber()
    {

        if (count(Payroll::where('school_id', Auth::user()->school_id)->get()) <= 0) {
            $payrollnonew = '#_001';
        } else {
            $payrollno = Payroll::where('school_id', Auth::user()->school_id)->orderBy('payrollno', 'DESC')->take(1)->first()->payrollno;
            $payrollnonew = ++$payrollno;
        }

        return $payrollnonew;

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(PayrollRequest $request)
    {
        \DB::beginTransaction();
        try {

            $payslip = new Payroll;
            $payslip->school_id = Auth::user()->school_id;
            $payslip->payrollno = $request->payroll_no;
            $payslip->salary_id = $request->salary_id;
            $payslip->staff_id = $request->staff_id;
            $payslip->start_date = $request->start_date;
            $payslip->end_date = $request->end_date;
            $payslip->leave = $request->leave;
            $payslip->late = $request->late;
            $payslip->percentage = $request->percentage;
            $payslip->leave_deduction = $request->loss_of_pay;
            $payslip->comments = $request->comments;
            $payslip->save();

            for ($i = 0; $i < $request->payrollscount; $i++) {
                $salary_item = 'salary_item'.$i;
                $amount = 'amount'.$i;
                $category_id = 'category_id'.$i;
                $category_value = 'category_value'.$i;
                $payslipitems = new PayslipItem;
                $payslipitems->payroll_id = $payslip->id;
                $payslipitems->salary_item_id = $request->$salary_item;
                if ($request->$category_id == 4) {
                    $payslipitems->amount = $this->calculation($request, $category_value);
                } else {
                    $payslipitems->amount = $request->$amount;
                }
                $payslipitems->save();
            }
            $res['success'] = 'Payroll added successfully';

            \DB::commit();

            return $res;
        } catch (Exception $e) {
            \DB::rollBack();
            Log::info($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $payroll = Payroll::find($id);

        return view('accountant/payroll/payslip/show', ['payroll' => $payroll]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        return view('accountant/payroll/payslip/edit', ['payrollid' => $id]);
    }

    public function editshow($id)
    {
        return new PayrollDetailResource(Payroll::find($id));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(PayrollUpdateRequest $request, $id)
    {
        \DB::beginTransaction();
        try {

            $payslip = Payroll::find($id);
            $payslip->comments = $request->comments;
            $payslip->leave = $request->leave;
            $payslip->late = $request->late;
            $payslip->percentage = $request->percentage;
            $payslip->leave_deduction = $request->loss_of_pay;
            $payslip->save();
            $payslip->payslipitems()->delete();
            for ($i = 0; $i < $request->payrollscount; $i++) {
                $salary_item = 'salary_item'.$i;
                $amount = 'amount'.$i;
                $payslipitems = new PayslipItem;
                $payslipitems->payroll_id = $payslip->id;
                $payslipitems->salary_item_id = $request->$salary_item;
                $payslipitems->amount = $request->$amount;
                $payslipitems->save();
            }
            $res['success'] = 'Payroll added successfully';
            \DB::commit();

            return $res;
        } catch (Exception $e) {
            \DB::rollBack();
            Log::info($e->getMessage());
        }
    }

    public function calculation($request, $category_value)
    {
        $newArray = [];
        foreach (json_decode($request->payrollskey, true) as $k => $v) {
            foreach ($v as $key => $value) {
                $newArray[0][$key] = $value;
            }
        }
        $total_amount = str_replace(array_keys($newArray[0]), $newArray[0], $request->$category_value);

        $el = new ExpressionLanguage;

        return $el->evaluate($total_amount);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $template = Payroll::find($id);
        $template->payslipitems()->delete();
        $template->delete();
        $res['message'] = 'Payroll deleted successfully';

        return $res;
    }
}
