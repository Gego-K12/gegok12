<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers\Api;

use App\Http\Resources\API\Country as CountryResource;
use App\Http\Resources\API\State as StateResource;
use App\Http\Resources\API\City as CityResource;
use App\Http\Requests\EditUserDetailRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Userprofile;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\User;
use Exception;

class UserprofileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function country()
    {
        //
        $country = Country::where('status','1')->get();
        $country = CountryResource::collection($country);

        return $country;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function state($id)
    {
        //
        $state = State::where('country_id',$id)->get();
        $state = StateResource::collection($state);

        return $state;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function city($id)
    {
        //
        $city = City::where('state_id',$id)->get();
        $city = CityResource::collection($city);

        return $city;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $profession = [];
        $profession['data'] = ['business','central_government_employee','private','home_maker','state_government_employee','others'];

        return $profession;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditUserDetailRequest $request, $id)
    {
        //
        try
        {
            //$user = User::where('id',Auth::user()->id)->get();
            $userprofile = Userprofile::where([['user_id',Auth::user()->id],['school_id',Auth::user()->school_id]])->first();
            
            if(Input::hasFile('avatar'))
            {
              $file = $request->file('avatar');
              $path = \Storage::putFile(Auth::user()->school->slug.'/uploads/admin/member/avatar',$file); 
              $userprofile->avatar = $path;  
            }
            else
            {
                $userprofile->avatar = $userprofile->avatar;
            }
            
            $userprofile->firstname             = $request->firstname;
            $userprofile->lastname              = $request->lastname;
            $userprofile->birth_firstname       = $request->birth_firstname;
            $userprofile->birth_lastname        = $request->birth_lastname;
            $userprofile->gender                = $request->gender;
            $userprofile->aadhar_number         = $request->aadhar_number;
            $userprofile->date_of_birth         = $request->date_of_birth;
            /*$userprofile->was_baptized          = $request->was_baptized;
            $userprofile->baptism_date          = $request->baptism_date;*/
            $userprofile->profession            = $request->profession;
            $userprofile->sub_occupation        = $request->sub_occupation;
            $userprofile->address               = $request->address;
            $userprofile->city_id               = $request->city;
            $userprofile->state_id              = $request->state;
            $userprofile->country_id            = $request->country;
            $userprofile->pincode               = $request->pincode;
            $userprofile->family                = $request->family;
            $userprofile->marriage_status       = $request->marriage_status;
            $userprofile->marriage_start_date   = $request->marriage_start_date;
            $userprofile->notes                 = $request->notes;
            
            $userprofile->save();

            $res['message']='User Details Updated Successfully';
            return $res;
        }

        catch(Exception $e)
        {
        } 
    }
}
