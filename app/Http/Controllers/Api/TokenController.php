<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use App\Models\Userprofile;
use App\Models\User;
use App\Token;
use Log;

class TokenController extends Controller
{
    public $successStatus = 200;

    public $failStatus = 302;

    public function issueToken(LoginRequest $request)
    {

    /*    $validator = Validator::make($request->all(), [
            'device_id' => 'required|check_logoutdevice_id',
        ],[
            'check_logoutdevice_id' => 'Your account is currently logged onto another device. Please log out of the other device or contact your administrator',
        ])->validate();*/

        
         $validator = Validator::make($request->all(), [
            'device_id' => 'required|check_logoutdevice_id',
        ], [
            'check_logoutdevice_id' => 'Your account is currently logged onto another device. Please log out of the other device or contact your administrator',
        ]);

         if ($validator->fails()) {
                        $status_code=422;
                        $res['message']="The given data was invalid.";
                        $res['errors']=$validator->errors();
                      
                      return response()->json($res, $status_code);
         }

     try
        {
            
    if(Auth::attempt(['mobile_no' => request('email'), 'password' => request('password'),'usergroup_id'=>7]) )
            {
                $user = Auth::user();
            
            $userprofile = Userprofile::where('user_id', $user->id)->first();
                if($userprofile->status == 'active')
                {
                   
                    $user = User::where([['id',$user->id],['school_id',$user->school_id]])->first();
                     
                    $user->platform_token = $request->device_name;

                    $user->device_id = $request->device_id;

                    $user->save();

                    $token = $user->createToken("gego")->plainTextToken;

                    //$token = $user->createToken($request->device_name)->plainTextToken;

                    return response()->json([
                        'status'        => 'success',
                        'token'         =>  $token,
                        'parent_id'     =>  $user->id,
                        'user_email'    =>  $user->email == null ? '':$user->email,
                        'user_name'     =>  $user->name,
                    ], $this->successStatus);
                }
            }
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        }


        /*$request->validate([
            'email' => 'required',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);

        } else {
            $output = [
                'id' => $user->id,
                'token' => $user->createToken($request->device_name)->plainTextToken,
                'user_email' => $user->email,
                'user_name' => $user->name,
            ];
        }

        return $output;*/
    }

    public function revokeToken(Request $request)
    {
        $user = $request->user();
        $token = explode(' ', $_SERVER['HTTP_AUTHORIZATION']);
        $usedToken = $user->tokens->each(
            function ($item) use ($token) {
                if ($item->plainTextToken = $token[1]) {
                    return $item;
                }
            }
        );
        Token::find($usedToken[0]->id)->delete();

        return [
            'message' => 'The supplied token is revoked.',
        ];
    }

    public function logout(Request $request)
    {
        if (Auth::check()) 
        {
            Auth()->user()->tokens()->delete();
        }

        $user = User::where([['id',Auth::id()],['school_id',Auth::user()->school_id]])->first();

        $user->platform_token  = NULL;

        $user->device_id = NULL;

        $user->save();

        return response()->json([
            'success'   =>  true,
            'message'   =>  'Logged out successfully'
        ],200);
    }
}
