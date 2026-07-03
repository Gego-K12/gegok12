<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\DetailRequest;
use App\Models\School;
use App\Models\SchoolDetail;
use App\Traits\Common;
use App\Traits\LogActivity;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Log;

/**
 * Class SchoolDetailsController
 *
 * Handles CRUD operations related to school details
 * for admin users.
 */
class SchoolDetailsController extends Controller
{
    use Common;
    use LogActivity;

    /**
     * Display school details listing page.
     *
     * Fetches school meta details and basic school
     * information for the authenticated user's school.
     *
     * @return Response
     */
    public function index()
    {
        $details = SchoolDetail::where('school_id', Auth::user()->school_id)->get()->keyby('meta_key');

        $school = School::where('id', Auth::user()->school_id)->first();

        return view('admin/schooldetails/index', ['details' => $details, 'school' => $school]);
    }

    /**
     * Get base data required for school detail creation.
     *
     * Returns school name, country list, state list,
     * and city list.
     *
     * @return array
     */
    public function list()
    {
        $array = [];

        $array['school_name'] = Auth::user()->school->name;
        $array['countrylist'] = SiteHelper::getCountries();
        $array['statelist'] = SiteHelper::getStates();
        $array['citylist'] = SiteHelper::getCities();

        return $array;
    }

    /**
     * Show the form for creating school details.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin/schooldetails/create');
    }

    /**
     * Validate store request (unused).
     *
     * @return void
     */
    public function validationStore(DetailRequest $request)
    {
        //
    }

    /**
     * Store newly created school details.
     *
     * Saves school basic information and meta
     * details including logo upload.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        try {
            $school = School::where('id', Auth::user()->school_id)->first();

            $school->name = $request->name;
            $school->address = $request->address;
            $school->country_id = $request->country_id;
            $school->state_id = $request->state_id;
            $school->city_id = $request->city_id;
            $school->pincode = $request->pincode;

            $school->save();

            $file = $request->file('school_logo');
            if ($file > 0) {
                $folder = Auth::user()->school->slug.'/school_logo';
                $path = $this->uploadFile($folder, $file);

                $details = new SchoolDetail;

                $details->school_id = $school->id;
                $details->meta_key = 'school_logo';
                $details->meta_value = $path;

                $details->save();
            }

            foreach ($request->request as $key => $value) {
                $arrays = [
                    'about_us', 'admission_open', 'admission_close_message',
                    'admission_close_on', 'affiliation_no', 'affiliated_by',
                    'board', 'date_of_establishment', 'landline_no',
                    'moto', 'school_logo', 'website',
                ];

                foreach ($arrays as $array) {
                    if ($key == $array) {
                        $details = new SchoolDetail;

                        $details->school_id = $school->id;
                        $details->meta_key = $key;
                        $details->meta_value = $value;

                        $details->save();
                    }
                }
            }

            $message = trans('messages.add_success_msg', ['module' => 'School Details']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $details,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_ADD_SCHOOL_DETAIL,
                $message
            );

            return redirect()->back()->with('successmessage', $message);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Fetch school details for edit API.
     *
     * @param  int  $school_id
     * @return array
     */
    public function edit($school_id)
    {
        $array = [];

        $school = School::where('id', $school_id)->first();
        $details = SchoolDetail::select('meta_key', 'meta_value')->where('school_id', $school_id)->get();
        $plucked = $details->pluck('meta_value', 'meta_key');

        $admission_open = ($plucked['admission_open'] == 0) ? false : true;

        $array['details'] = $plucked;
        $array['details']['admission_open'] = $admission_open;
        $array['details']['school_logo_display'] = $plucked['school_logo'] == '-' ? null : $this->getFilePath($plucked['school_logo']);
        $array['details']['name'] = $school->name;
        $array['details']['address'] = $school->address;
        $array['details']['country_id'] = $school->country_id;
        $array['details']['state_id'] = $school->state_id;
        $array['details']['city_id'] = $school->city_id;
        $array['details']['pincode'] = $school->pincode;
        $array['details']['countrylist'] = SiteHelper::getCountries();
        $array['details']['statelist'] = SiteHelper::getStates();
        $array['details']['citylist'] = SiteHelper::getCities();

        return $array;
    }

    /**
     * Show edit view for school details.
     *
     * @param  int  $school_id
     * @return Response
     */
    public function editdetail($school_id)
    {
        $school = School::where('id', $school_id)->first();

        return view('/admin/schooldetails/edit', ['school_id' => $school_id, 'school' => $school]);
    }

    /**
     * Validate update request (unused).
     *
     * @return void
     */
    public function validationUpdate(DetailRequest $request)
    {
        //
    }

    /**
     * Update existing school details.
     *
     * Handles school profile update, logo replacement,
     * and meta detail updates.
     *
     * @param  int  $school_id
     * @return Response
     */
    public function update(Request $request, $school_id)
    {
        try {
            $details = SchoolDetail::where('school_id', $school_id)->first();
            $school_id = Auth::user()->school_id;

            $school = School::where('id', $school_id)->first();

            $school->name = $request->name;
            $school->address = $request->address;
            $school->country_id = $request->country_id;
            $school->state_id = $request->state_id;
            $school->city_id = $request->city_id;
            $school->pincode = $request->pincode;

            $school->save();

            $file = $request->file('school_logo');
            if ($file != null) {
                $folder = Auth::user()->school->slug.'/school_logo';
                $path = $this->uploadFile($folder, $file, 'public');

                $details = SchoolDetail::where([['school_id', $school_id], ['meta_key', 'school_logo']])->first();
                $details->meta_value = $path;
                $details->save();
            } else {
                $details = SchoolDetail::where([['school_id', $school_id], ['meta_key', 'school_logo']])->first();
                $details->meta_value = $details->meta_value;
                $details->save();
            }

            foreach ($request->request as $key => $value) {
                $arrays = [
                    'about_us', 'admission_open', 'admission_close_message',
                    'admission_close_on', 'affiliation_no', 'affiliated_by',
                    'board', 'date_of_establishment', 'landline_no',
                    'moto', 'website',
                ];

                foreach ($arrays as $array) {
                    if ($key == 'admission_open') {
                        $details = SchoolDetail::where([['school_id', $school_id], ['meta_key', $key]])->first();
                        if ($details) {
                            $details->meta_value = ($value == 'true') ? 1 : 0;
                            $details->save();
                        }
                    } elseif ($key == $array) {
                        $details = SchoolDetail::where([['school_id', $school_id], ['meta_key', $key]])->first();
                        if ($details) {
                            $details->meta_value = $value;
                            $details->save();
                        }
                    }
                }
            }

            $message = trans('messages.update_success_msg', ['module' => 'School Details']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $school,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_EDIT_SCHOOL_DETAIL,
                $message
            );

            return redirect()->back()->with('successmessage', $message);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
