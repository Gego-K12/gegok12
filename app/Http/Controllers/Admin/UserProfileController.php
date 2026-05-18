<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers\Admin;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ChangeAvatarRequest;
use App\Http\Requests\CredentialsUpdateRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Userprofile;
use App\Traits\LogActivity;
use App\Models\Country;
use App\Traits\Common;
use App\Models\State;
use App\Models\User;
use App\Models\City;
use Exception;
use Hash;

class UserProfileController extends Controller
{
    use Common;
    use LogActivity;

    public function ChangePassword()
    {
        return view('/admin/changepassword');
    }
      
    /**
     * Updates the password of the specified user.
     * 
     * @param \Illuminate\Http\Request $request 
     * 
     * @return \Illuminate\Http\Response
     */
    public function updateChangePassword(ChangePasswordRequest $request)
    {
        $user = User::find(Auth::id());
        $hashedPassword = $user->password;

        if($hashedPassword!='')
        { 
            $user->password = Hash::make($request->newpassword);
            $user->save();

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $user,
                Auth::user(),
                ['ip' => $ip,'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_CHANGE_PASSWORD,
                'Changed Profile Password.'                        
            );       
        } 
           
        $res['message']=__('admin_userprofile.password_update');
        return $res;
    }

    public function changeavatar(Request $request)
    {   
        return view('/admin/changeavatar');
    }

     public function getavatar()
    {
        $userprofile = Userprofile::where('user_id', Auth::id())->first();
        $array=[];

        if(Auth::user())
        {
            $array['avatar']=$this->getFilePath($userprofile->avatar);
            $array['id']=$userprofile->id;
        }

        return $array;
    }
 
    /**
     * Updates the avatar image for specified user.
     * 
     * @param \Illuminate\Http\Request $request 
     * 
     * @return \Illuminate\Http\Response
     */
    public function updatechangeavatar(ChangeAvatarRequest $request)
    {
        try
        {
            $userprofile = Userprofile::where('user_id', Auth::id())->first();
           // $path=$this->uploadFile(Auth::user()->school->slug.'/uploads/avatars', $request->avatar);

            $path = \Storage::disk('uploads')->putFile(Auth::user()->school->slug.'/uploads/avatars', $request->avatar,'public');

            if($path!='')
            {
                $userprofile->avatar = $path ;
                $userprofile->save();  

                $res['message']=__('admin_userprofile.update_avatar');
                $res['avatar']=$this->getFilePath($path);     
            }

            $ip= $this->getRequestIP();
                $this->doActivityLog(
                $userprofile,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_CHANGE_AVATAR,
                'Changed Avatar'
            );  
            return $res; 
        }
        catch(Exception $e)
        {
            //dd($e->getMessage());
        }
    }

    public function edit(Request $request)
    {  
        return view('/admin/editprofile');
    }

    public function create()
    {
        $country = Country::where('status','1')->get(); 
        $state = State::get();
        $city = City::get();
        $userprofile = Userprofile::with('country','state','city')->where('user_id', Auth::id())->first();

        $array=[];
       
        $array['firstname']=$userprofile->firstname;
        $array['lastname']=$userprofile->lastname;
        $array['birth_firstname']=$userprofile->birth_firstname;
        $array['birth_lastname']=$userprofile->birth_lastname;
        $array['gender']=$userprofile->gender;
        $array['date_of_birth']=date('Y-m-d',strtotime($userprofile->date_of_birth));
        $array['address']=$userprofile->address;
        // $array['country_id']=$userprofile->country->name;
        // $array['state_id']=$userprofile->state->name;
        // $array['city_id']=$userprofile->city->name;
        $array['country_id']=$userprofile->country->id;
        $array['state_id']=$userprofile->state->id;
        $array['city_id']=$userprofile->city->id;
        $array['pincode']=$userprofile->pincode;
        $array['countrylist']=$country;
        $array['statelist']=$state;
        $array['citylist']=$city;

        return $array;      
    }
    
    public function update(Request $request)
    {//dump($request);
        try
        {
            $userprofile = Userprofile::where('user_id', Auth::id())->first();
            
            $userprofile->firstname = $request->firstname;
            $userprofile->lastname = $request->lastname;
            /*$userprofile->birth_firstname = $request->birth_firstname;
            $userprofile->birth_lastname = $request->birth_lastname;*/
            $userprofile->gender = $request->gender;
            $userprofile->date_of_birth = $request->date_of_birth;
            $userprofile->address = $request->address;
            $userprofile->city_id = $request->city_id;
            $userprofile->state_id = $request->state_id;
            $userprofile->country_id = $request->country_id;
            $userprofile->pincode = $request->pincode;
            
            $userprofile->save();

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $userprofile,
                Auth::user(),
                ['ip' => $ip,'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_EDIT_USERPROFILE,
                'Changed Profile'                        
            );

            $res['message']=__('admin_userprofile.profile_update');
            return $res; 
        }
        catch(Exception $e)
        {
           //dd($e->getMessage());
        } 
    } 


    public function getCredentials($name)
    {
      try
        {  
        $user = User::where([['name',$name],['school_id',Auth::user()->school_id]])->first();
        $array=[];

        if(Auth::user())
        {
            $array['email'] = $user->email==null ? '':$user->email;
            $array['mobile_no']=$user->mobile_no==null ?'':$user->mobile_no;
            $array['usergroup_id']=$user->usergroup_id;
        }

        return $array;
         }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }

    public function updateCredentials(CredentialsUpdateRequest $request,$name)
    {
        //dd($name);
        try
        {
        $user = User::where([['name',$name],['usergroup_id',$request->user_group],['school_id',Auth::user()->school_id]])->first();
         $user->tokens()->delete();
        //dd($user);
        $user->email=$request->email;
        $user->mobile_no=$request->mobile_no;
        $user->platform_token  = NULL;
        $user->device_id = NULL;
        $user->save();
    

         $res['message']=__('admin_userprofile.credentials_update');

          $ip= $this->getRequestIP();
            $this->doActivityLog(
                $user,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_CHANGE_CREDENTIALS,
                $res['message']
            );  
            return $res; 

         }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            dd($e->getMessage());
        }
    }  
}