<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers\Admin;

use App\Events\VerificationMailEvent;
use App\Traits\ResetPasswordProcess;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerification;
use Illuminate\Http\Request;
use App\Helpers\SiteHelper;
use App\Traits\LogActivity;
use App\Models\Userprofile;
use App\Traits\Common;
use App\Models\User;
use Exception;
use Hash;


class UserController extends Controller
{
    use ResetPasswordProcess;
    use LogActivity;
    use Common;

    public function updateStatus(Request $request,$name)
    {
      try
      {
        $user = User::where('name',$name)->first();

        $this->reset_token($user);

        $user->status = $request->status;
        
        $user->save();
        
        $userprofile = Userprofile::where('user_id',$user->id)->first();

        $userprofile->status = $request->status;
        
        $userprofile->save();

        $message=trans('messages.update_status_success_msg',['module' => 'Student']);

        $ip= $this->getRequestIP();
        $this->doActivityLog(
          $userprofile,
          Auth::user(),
          ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
          LOGNAME_MEMBER_STATUS,
          $message
        ); 
        \Session::put('successmessage',$message);
        return redirect()->back();
      }
      catch(Exception $e)
      {
      }
    }

    public function resetPassword($id)
    {
      try
      {
        $user = User::where('id', $id)->first();
        if(Gate::allows('member',$user))
        {
          $this->resetPasswordToUser($user);

          $message= trans('messages.password_reset_success_msg');

          $ip= $this->getRequestIP();
          $this->doActivityLog(
            $user,
            Auth::user(),
            ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
            LOGNAME_RESET_PASSWORD,
            $message
          );
          return redirect()->back(); 
        }
        else
        {
          abort(403);
        } 
      }
      catch(Exception $e)
      {
      }
        
    }

    public function emailVerification($id)
    {
      try
      {
        $user = User::where('id', $id)->first();
        if(Gate::allows('member',$user))
        {
          if(env('MAIL_STATUS') == 'on')
          {
            event(new VerificationMailEvent($user));
            \Session::put('successmessage',trans('messages.verification_success_msg'));
          }
            
          $message=('Email Verification code'); 

          $ip= $this->getRequestIP();
          $this->doActivityLog(
            $user,
            Auth::user(),
            ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
            LOGNAME_EMAIL_VERIFICATION,
            $message
          );

          \Session::put('failmessage',trans('messages.verification_failure_msg')); 
          return redirect()->back();
        }
        else
        {
          abort(403);
        } 
      }
      catch(Exception $e)
      {
      }
    }

    public function reset_token(User $user)
    {
      try
      {
        $user->tokens()->delete();

        $user->platform_token  = NULL;

        $user->device_id = NULL;

        $user->save();

        return $user;
      }
      catch(Exception $e)
      {
      }
    }
}