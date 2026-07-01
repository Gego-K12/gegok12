<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Users\ParentUser;
use App\Models\User;
use Validator;
use Exception;
//use Auth;
use Log;
use Illuminate\Contracts\Queue\ShouldQueue;

class LoginController extends Controller implements ShouldQueue
{
    public $successStatus = 200;

    public $failStatus = 302;

    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {
        try
        {
            if (Auth::attempt(['mobile_no' => request('email'), 'password' => request('password')]) )
            {
                $auth_user = Auth::user();

                $token = $auth_user->createToken("gego")->plainTextToken;

                //$token = $auth_user->createToken($request->device_name)->plainTextToken;

                $user = ParentUser::where([['id',$auth_user->id],['school_id',$auth_user->school_id]])->first();
                     
                $user->platform_token = $request->device_name;

                $user->save();

                return response()->json([
                    'status'        => 'success',
                    'token'         =>  $token,
                    'id'            =>  $user->id,
                    'user_email'    =>  $user->email == null ? '':$user->email ,
                    'user_name'     =>  $user->name,
                ], $this->successStatus);
            }
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        try
        {
            if (Auth::check()) 
            {
                Auth()->user()->tokens()->delete();
            }

            $user = User::where([['id',Auth::id()],['school_id',Auth::user()->school_id]])->first();
            
            $user->platform_token  = NULL;

            $user->device_id       = NULL;

            $user->save();

            return response()->json([
                'success'   =>  true,
                'message'   =>  'Logged out successfully'
            ],200);
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }

    public function logoutDevices(Request $request)
    {


        try
        {
             $user = User::where([['mobile_no',$request->email],['device_id','!=',null],['usergroup_id',7]])->first();

            if ($user!=null) 
               {

                $user->tokens()->delete();

                $user->platform_token  = NULL;

                $user->device_id       = NULL;

                $user->save();

                 return response()->json([
                'success'   =>  true,
                'message'   =>  'Logged out from all devices'
                 ],200);
             }
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }

    }
}