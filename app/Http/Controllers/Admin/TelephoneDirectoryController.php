<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers\Admin;

use App\Http\Resources\TelephoneDirectory as TelephoneDirectoryResource;
use App\Http\Resources\UserPhoneResource;
use App\Http\Requests\TelephoneDirectoryRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\TelephoneDirectory;
use Illuminate\Http\Request;
use App\Traits\LogActivity;
use App\Traits\Common;
use App\Models\User;
use Exception;
use Log;

class TelephoneDirectoryController extends Controller
{
    use LogActivity;
    use Common;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('/admin/telephonedirectory/index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        //
        $numberlist = TelephoneDirectory::where('school_id',Auth::user()->school_id)->orderby('id','desc')->get();

        $users = User::where('school_id', Auth::user()->school_id)
        ->whereNotIn('usergroup_id', [1, 2, 3, 4])
        ->whereHas('userprofile', function($query) {
            $query->where('status', 'active');
        })
        ->get();

    // $merged = $numberlist->merge($users);
    // dd($merged);
        $numberlist = TelephoneDirectoryResource::collection($numberlist);
        $userData = UserPhoneResource::collection($users);
        $merged = collect($numberlist)->merge($userData);

        return response()->json([
            'data' => $merged
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('/admin/telephonedirectory/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TelephoneDirectoryRequest $request)
    {
        //
        try
        {
            $telephonedirectory = new TelephoneDirectory;

            $telephonedirectory->school_id         =   Auth::user()->school_id;
            $telephonedirectory->name              =   $request->name;
            $telephonedirectory->designation       =   $request->designation;
            $telephonedirectory->phone_number      =   $request->phone_number;

            $telephonedirectory->save();

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $telephonedirectory,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_ADD_telephonedirectory,
                trans('messages.add_success_msg',['module' =>' Phone Number'])
            ); 

            $res['success'] = trans('messages.add_success_msg',['module' => 'Phone Number']);

            return $res;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $telephonedirectory = TelephoneDirectory::where('id',$id)->first();

        return view('/admin/telephonedirectory/show',['telephonedirectory' => $telephonedirectory]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function editlist($id)
    {
        $numberlist = TelephoneDirectory::where('id',$id)->get();
        $numberlist = TelephoneDirectoryResource::collection($numberlist);

        return $numberlist;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $telephonedirectory = TelephoneDirectory::where('id',$id)->first();

        return view('/admin/telephonedirectory/edit',['telephonedirectory' => $telephonedirectory]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TelephoneDirectoryRequest $request, $id)
    {
        //
        try
        {
            $telephonedirectory = TelephoneDirectory::where('id',$id)->first();

            $telephonedirectory->name              =   $request->name;
            $telephonedirectory->designation       =   $request->designation;
            $telephonedirectory->phone_number      =   $request->phone_number;

            $telephonedirectory->save();

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $telephonedirectory,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_EDIT_telephonedirectory,
                trans('messages.update_success_msg',['module' => 'Phone Number'])
            ); 

            $res['success'] = trans('messages.update_success_msg',['module' => 'Phone Number']);

            return $res;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
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
        //
        try
        {
            $telephonedirectory = TelephoneDirectory::where('id',$id)->first();
            $telephonedirectory->delete();

            $message= trans('messages.delete_success_msg',['module' => 'Telephone Directory']);

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $telephonedirectory,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_DELETE_telephonedirectory,
                $message
            );

            $res['success'] = $message;
            return $res;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }
}