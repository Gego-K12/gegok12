<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\OTPRequest;
use App\Models\Authentication;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Log;

class OTPController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('/admin/otp/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OTPRequest $request)
    {
        //
        \DB::beginTransaction();
        try
        {
            $authentication = Authentication::where([
                ['user_id',Auth::id()],
                ['status',0],
                ['type','register'],
            ])->orderBy('id','DESC')->get();

            if($authentication[0]['token'] == $request->otp)
            {
                $authentication_update = Authentication::where('id',$authentication[0]['id'])->first();

                $authentication_update->status = 1;

                $authentication_update->save();

                $user = User::where('id',Auth::id())->first();

                $user->mobile_verification_code = $authentication_update['token'];
                $user->mobile_verified          = 1;
                $user->mobile_verified_at       = date('Y-m-d H:i:s');
                
                $user->save();

                \DB::commit();
                return redirect('/admin/dashboard')->with('successmessage',trans('messages.mobile_verify_success_msg'));
            }
            else
            {
                return redirect()->back()->with('failmessage',trans('messages.otp_failure_msg'));
            }
        }
        catch(Exception $e)
        {
            \DB::rollBack();
            Log::info($e->getMessage());
        }
    }
}