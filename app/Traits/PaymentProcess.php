<?php
/**
 * Trait for processing common
 */
namespace App\Traits;

use Illuminate\Support\Facades\DB;
use App\Models\Subscription;
use App\Models\Userprofile;
use App\Models\Plan;
use Carbon\Carbon;
use Exception;

/**
 *
 * @class trait
 * Trait for PaymentProcess Processes
 */
trait PaymentProcess
{
  /**
   * Handle subscription payment response and update membership.
   *
   * @param object $data Payment gateway callback data
   * @param int $user_id User identifier
   * @param int $school_id School identifier
   * @param \App\Models\Subscription $payment Existing subscription/payment record
   * @return void
   */
  public function PaymentProcess($data , $user_id , $school_id , $payment)
   {
        \DB::beginTransaction();
        try
        {
            if(($data->status == 'success') && ($payment->status == 'pending'))
            {
                $plan = Plan::where('id', $data->udf1)->first();
                $payment->plan_id = $plan->id;
                $payment->status = "approve";
                $payment->payment_details = array(  'merchant_key'  =>  $data->key , 
                                                    'txnid'         =>  $data->txnid , 
                                                    'amount'        =>  $data->amount , 
                                                    'firstname'     =>  $data->firstname , 
                                                    'email'         =>  $data->email , 
                                                    'hash'          =>  $data->hash , 
                                                    'productinfo'   =>  $data->productinfo , 
                                                    'status'        =>  $data->status , 
                                                    'mode'          =>  $data->mode , 
                                                    'error_Message' =>  $data->error_Message , 
                                                    'addedon'       =>  $data->addedon , 
                                                    'phone'         =>  $data->phone
                                                );
                $payment->plan_details = array( 'no_of_members'     =>  $plan->no_of_members , 
                                                'no_of_events'      =>  $plan->no_of_events , 
                                                'no_of_folders'     =>  $plan->no_of_folders , 
                                                'no_of_files'       =>  $plan->no_of_files , 
                                                'no_of_videos'      =>  $plan->no_of_videos , 
                                                'no_of_bulletins'   =>  $plan->no_of_bulletins , 
                                                'no_of_groups'      =>  $plan->no_of_groups 
                                            );
                $payment->end_date = Carbon::parse($data->addedon)->addDays($plan->cycle);

                $userprofile = Userprofile::where([['user_id',$user_id],['school_id',$school_id]])->first();
                $now=date('Y-m-d');

                $userprofile->membership_type = "member";
                $userprofile->membership_start_date = date('Y-m-d',strtotime($data->addedon));
                //$userprofile->membership_end_date = date('Y-m-d',strtotime($now.'+'.$plan->cycle.' days'));

                $userprofile->save();
            }
            elseif( ($data->status == 'success') && ($payment->status == 'expired') )
            {
                $payment = new Subscription;

                $plan = Plan::where('id', $data->udf1)->first();

                $payment->school_id = $school_id;
                $payment->user_id = $user_id;
                $payment->plan_id = $plan->id;
                $payment->status = "approve";
                $payment->payment_details = array(  'merchant_key'  =>  $data->key , 
                                                    'txnid'         =>  $data->txnid , 
                                                    'amount'        =>  $data->amount , 
                                                    'firstname'     =>  $data->firstname , 
                                                    'email'         =>  $data->email , 
                                                    'hash'          =>  $data->hash , 
                                                    'productinfo'   =>  $data->productinfo , 
                                                    'status'        =>  $data->status , 
                                                    'mode'          =>  $data->mode , 
                                                    'error_Message' =>  $data->error_Message , 
                                                    'addedon'       =>  $data->addedon , 
                                                    'phone'         =>  $data->phone
                                                );
                $payment->plan_details = array( 'no_of_members'     =>  $plan->no_of_members , 
                                                'no_of_events'      =>  $plan->no_of_events , 
                                                'no_of_folders'     =>  $plan->no_of_folders , 
                                                'no_of_files'       =>  $plan->no_of_files , 
                                                'no_of_videos'      =>  $plan->no_of_videos , 
                                                'no_of_bulletins'   =>  $plan->no_of_bulletins , 
                                                'no_of_groups'      =>  $plan->no_of_groups 
                                            );
                $payment->end_date = Carbon::parse($data->addedon)->addDays($plan->cycle);

                $userprofile = Userprofile::where([['user_id',$user_id],['school_id',$school_id]])->first();
                $now=date('Y-m-d');

                $userprofile->membership_type = "member";
                $userprofile->membership_start_date = date('Y-m-d',strtotime($data->addedon));
                //$userprofile->membership_end_date = date('Y-m-d',strtotime($now.'+'.$plan->cycle.' days'));

                $userprofile->save();
            }

            $payment->save();
            
            \DB::commit();
            return $payment;
        }
        catch(Exception $e)
        {
            \DB::rollBack();
        } 
   }
   
}
