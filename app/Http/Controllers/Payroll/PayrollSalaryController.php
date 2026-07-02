<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Payroll;

use App\Http\Requests\Payroll\PayrollSalaryRequest;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use App\Http\Requests\Payroll\SalaryUpdateRequest;
use App\Http\Resources\OwnershipMemberResource;
use App\Http\Resources\Payroll\SalaryResource;
use App\Http\Resources\Payroll\SalaryListResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\PayrollTemplate;
use App\Models\SalaryItem;
use App\Models\Salary;
use App\Models\User;
use Exception;
use Log;

class PayrollSalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('accountant/payroll/salary/index');
    }

    public function showlist()
    {
        $salary = Salary::where('school_id', Auth::user()->school_id)->with('user')->orderby('id', 'desc')->paginate(20);
        $salary = SalaryListResource::collection($salary);
        return $salary;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('accountant/payroll/salary/create');
    }

    public function list()
    {
        $array = [];
        $staff = User::whereIn('usergroup_id', [5, 8, 10, 11, 12, 13])->where([['status', 'active'], ['school_id', Auth::user()->school_id]])->get();
        $template = PayrollTemplate::where([['status', 1], ['school_id', Auth::user()->school_id]])->get();
        $array['staff'] = OwnershipMemberResource::collection($staff);
        $array['template'] = $template;
        return $array;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PayrollSalaryRequest $request)
    {
        \DB::beginTransaction();
        try {

            $salary = new Salary;
            $salary->school_id = Auth::user()->school_id;
            $salary->staff_id = $request->staff_id;
            $salary->template_id = $request->template_id;
            $salary->gross_salary = $request->gross_salary;
            $salary->effective_date = $request->effective_date;
            $salary->save();
            for ($i = 0; $i < $request->payrollscount; $i++) {
                $amount = 'amount' . $i;
                $template_item = 'template_item' . $i;
                $category_id = 'category_id' . $i;
                $category_value = 'category_value' . $i;
                $salaryitem = new SalaryItem;
                $salaryitem->salary_id = $salary->id;
                $salaryitem->template_item_id = $request->$template_item;
                if ($request->$category_id == 4) {
                    $salaryitem->amount = $this->calculation($request, $category_value);
                } else {
                    $salaryitem->amount = $request->$amount;
                }
                $salaryitem->save();
            }


            $res['success'] = 'Salary added successfully';

            \DB::commit();
            return $res;
        } catch (Exception $e) {
            \DB::rollBack();
            Log::info($e->getMessage());
        }
    }

    // public function calculation($request, $category_value)
    // {
    //     $newArray = [];
    //     foreach (json_decode($request->payrollskey, true) as $k => $v) {
    //         foreach ($v as $key => $value) {
    //             $newArray[0][$key] = $value;
    //         }
    //     }
    //     $total_amount = str_replace(array_keys($newArray[0]), $newArray[0], $request->$category_value);

    //     eval('$amount_tot =' . $total_amount . ";");
    //     return "$amount_tot";
    // }
    public function calculation($request, $category_value)
    {
        $variables = [];

        foreach (json_decode($request->payrollskey, true) as $v) {
            foreach ($v as $key => $value) {
                $variables[$key] = $value;
            }
        }

        $expression = str_replace(
            array_keys($variables),
            array_values($variables),
            $request->$category_value
        );

        $el = new ExpressionLanguage();

        return $el->evaluate($expression);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('accountant/payroll/salary/edit', ['salaryid' => $id]);
    }

    public function editshow($id)
    {
        return new SalaryResource(Salary::with('user')->find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SalaryUpdateRequest $request, $id)
    {
        \DB::beginTransaction();
        try {
            $salary = Salary::find($id);
            $salary->school_id = Auth::user()->school_id;
            $salary->staff_id = $request->staff_id;
            $salary->template_id = $request->template_id;
            $salary->gross_salary = $request->gross_salary;
            $salary->effective_date = $request->effective_date;
            $salary->save();
            $salary->salaryitems()->delete();
            for ($i = 0; $i < $request->payrollscount; $i++) {
                $amount = 'amount' . $i;
                $template_item = 'template_item' . $i;
                $category_id = 'category_id' . $i;
                $category_value = 'category_value' . $i;
                $salaryitem = new SalaryItem;
                $salaryitem->salary_id = $salary->id;
                $salaryitem->template_item_id = $request->$template_item;
                if ($request->$category_id == 4) {
                    $salaryitem->amount = $this->calculation($request, $category_value);
                } else {
                    $salaryitem->amount = $request->$amount;
                }
                $salaryitem->save();
            }
            $res['success'] = 'Salary updated successfully';

            \DB::commit();
            return $res;
        } catch (Exception $e) {
            \DB::rollBack();
            Log::info($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $salary = Salary::find($id);
        /* if(count($salary->payrolls())>0)
         {
           $res['message'] = 'Salary structure  used at many payrolls';
         }else{*/
        $salary->salaryitems()->delete();
        $salary->delete();
        $res['message'] = 'Salary deleted successfully';
        // }
        return $res;
    }
}
