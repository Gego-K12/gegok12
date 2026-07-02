<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers\Payroll;

use App\Http\Requests\Payroll\TransactionRequest;
use App\Http\Resources\OwnershipMemberResource;
use App\Http\Resources\Payroll\TransactionListResource;
use App\Http\Resources\Payroll\PayrollListResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\PayrollTransaction;
use App\Models\TransactionAccount;
use App\Models\Payroll;
use App\Models\TransactionType;
use App\Models\User;
use App\Traits\Common;
use Exception;

class TransactionController extends Controller
{
    use Common;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('accountant/payroll/transaction/index');
    }

    public function showlist()
    {
        $transaction=PayrollTransaction::with('user')->where('school_id',Auth::user()->school_id)->paginate(20);
        $transaction=TransactionListResource::collection($transaction);
        return $transaction;
    }
    public function list()
    {
        $array=[];
        $staff=User::whereIn('usergroup_id',[5,8,10,11,12,13])->where([['status','active'],['school_id',Auth::user()->school_id]])->get();       
         $paytype=TransactionType::get();
         $accounts=TransactionAccount::get();
        $array['staff']=OwnershipMemberResource::collection($staff);
        $array['paytype']=$paytype;
        $array['accounts']=$accounts;
        return $array;
    }
    protected function getvouchernumber()
    {
        $voucherno;
        if(count(PayrollTransaction::where('school_id',Auth::user()->school_id)->get())<=0)
        {
           $voucherno="#v_001";
        }
        else{
          $transaction_no=PayrollTransaction::where('school_id',Auth::user()->school_id)->orderBy("transaction_no","DESC")->take(1)->first()->transaction_no;
          $voucherno=++$transaction_no;
        } 
        return $voucherno;
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('accountant/payroll/transaction/create');
    }

    public function detail($id)
    {
       $array=[];
       $returnamount=0;
       $user=User::find($id);
       $pending=$user->salarydetail()['pending'];
       if($pending!=null && $pending>0)
       {
        $returnamount=$pending;
       }
       $payrolls=$user->payrolls()->where('status','unpaid')->get();
       $array['payroll']=PayrollListResource::collection($payrolls)->keyby('id');
       $array['returnable']=$returnamount;
       $array['account_id']=$user->bankdetail->id;
       return $array;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TransactionRequest $request)
    {
        //
        try{
            \DB::beginTransaction();
        if($request->payment_method=="Cheque")
            {
                $detail = array(
                    "cheque_number" => $request->cheque_number,
                    "cheque_date"   => $request->cheque_date,
                    "cheque_bank"   => $request->cheque_bank,
                    "clearnig_date" => $request->clearnig_date
                );
            }
         $transaction=new PayrollTransaction;
         $transaction->school_id=Auth::user()->school_id;
         $transaction->transaction_no=$request->transaction_no;
         // $transaction->account_id=$request->account_id;
         $request->account_id === "null" ? null : $request->account_id;
         $transaction->paytype_id=$request->paytype;
         if($request->paytype==1)
         {
            $transaction->payroll_id=$request->payroll_id;
            $this->statusupdate($request->payroll_id);
         }
         $transaction->staff_id=$request->staff_id;
         $transaction->amount=$request->amount;
         $transaction->transaction_date=$request->transaction_date;
         $transaction->payment_method=$request->payment_method;
         $transaction->transaction_detail=$detail;
         $transaction->reference_number=$request->reference_number;
         $transaction->remarks=$request->remark;
         $file   =   $request->file('attachment');
            if($file)
            {
                $path = $this->uploadFile('/uploads/admin/transaction',$file);
                $transaction->attachment = $path; 
            }

         $transaction->save();  
          \DB::commit();
          $res['success'] = 'Transaction added successfully';
         return $res; 
       }
      catch(Exception $e)
        {
             \DB::rollBack();
            \Log::info($e->getMessage());
        } 
    }

    public function statusupdate($id)
    {
        $payroll=Payroll::find($id);
        $payroll->status='paid';
        $payroll->save();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
         return view('accountant/payroll/transaction/edit',['transactionid'=>$id]);
    }

    public function editshow($id)
    {
        $transaction=PayrollTransaction::with('payroll')->find($id);
        return $transaction;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         if($request->payment_method=="Cheque")
            {
                $detail = array(
                    "cheque_number" => $request->cheque_number,
                    "cheque_date"   => $request->cheque_date,
                    "cheque_bank"   => $request->cheque_bank,
                    "clearnig_date" => $request->clearnig_date
                );
            }
         $transaction=PayrollTransaction::find($id);
         $transaction->school_id=Auth::user()->school_id;
         // $transaction->account_id=$request->account_id;
         $request->account_id === "null" ? null : $request->account_id;
        /* $transaction->paytype=$request->paytype;
         $transaction->staff_id=$request->staff_id;*/
         $transaction->amount=$request->amount;
         $transaction->transaction_date=$request->transaction_date;
         $transaction->payment_method=$request->payment_method;
         $transaction->transaction_detail=$detail;
         $transaction->reference_number=$request->reference_number;
         $transaction->remarks=$request->remark;
         $file   =   $request->file('attachment');
            if($file)
            {
                $path = $this->uploadFile('/uploads/admin/transaction/',$file);
                $transaction->attachment = $path; 
            }

         $transaction->save();  
          $res['success'] = 'Transaction updated successfully';
         return $res; 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $payroll=PayrollTransaction::find($id);
        $payroll->delete();
        $res['message'] = 'Transaction deleted successfully';
        return $res;
    }
}