<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers\Api\Teacher;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\SchoolDetail;
use Illuminate\Http\Request;
use App\Helpers\SiteHelper;
use App\Models\School;
use App\Models\Setting;
use App\Traits\Common;

class SchoolController extends Controller
{
    use Common;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $array = [];

        $school   = School::where('id',Auth::user()->school_id)->first();
        $details  = SchoolDetail::select('meta_key','meta_value')->where('school_id',Auth::user()->school_id)->pluck('meta_value','meta_key');

        $array['schoolName']            = $school->name;
        $array['schoolLogo']            = $details['school_logo']=='-' ? null:$this->getFilePath($details['school_logo']);
        $array['moto']                  = $details['moto']=='-' ? null:$details['moto'];
        $array['affiliatedBy']          = $details['affiliated_by']=='-' ? null:$details['affiliated_by'];
        $array['affiliationNo']         = $details['affiliation_no']=='-' ? null:$details['affiliation_no'];
        $array['dateOfEstablishment']   = $details['date_of_establishment']=='-' ? null:$details['date_of_establishment'];
        $array['board']                 = $details['board']=='-' ? null:$details['board'];
        $array['landlineNo']            = $details['landline_no']=='-' ? null:$details['landline_no'];
        $array['aboutUs']               = $details['about_us']=='-' ? null:$details['about_us']; 
        $array['website']               = $details['website']=='-' ? null:$details['website'];
        $array['address']               = $school->address;
        $array['country']               = $school->country->name;
        $array['state']                 = $school->state->name;
        $array['city']                  = $school->city->name;
        $array['pincode']               = $school->pincode; 

        $settings = Setting::whereIn('key', [
            'assignment_status',
            'homework_status'
        ])
        ->pluck('value', 'key');

        $array['settings'] = [
        'assignmentStatus' => (int)($settings['assignment_status'] ?? 1),
        'homeworkStatus'   => (int)($settings['homework_status'] ?? 1),
    ];
        
        return response()->json([
            'success'   =>  true,
            'message'   =>  'School Details',
            'data'      =>  $array
        ],200);
    }
}