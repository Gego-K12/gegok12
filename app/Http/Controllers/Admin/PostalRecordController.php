<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostalRecordRequest;
use App\Http\Resources\PostalRecord as PostalRecordResource;
use App\Models\PostalRecord;
use App\Models\School;
use App\Traits\Common;
use App\Traits\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Class PostalRecordController
 *
 * Manages inbound and outbound postal records including
 * creation, listing, viewing, updating, deletion,
 * file uploads, and activity logging.
 */
class PostalRecordController extends Controller
{
    use Common;
    use LogActivity;

    /**
     * Get postal record list for the current academic year.
     *
     * @return AnonymousResourceCollection
     */
    public function showlist(Request $request)
    {
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);

        $postalrecord = PostalRecord::where([
            ['school_id', Auth::user()->school_id],
            ['academic_year_id', $academic_year->id],
        ])->get();

        $postalrecordlist = PostalRecordResource::collection($postalrecord);

        return $postalrecordlist;
    }

    /**
     * Display postal record index page.
     *
     * @return View
     */
    public function index()
    {
        //
        return view('/admin/postalrecord/index');
    }

    /**
     * Show postal record creation form.
     *
     * @return View
     */
    public function create()
    {
        $date = date('Y-m-d');
        $school = School::where('id', Auth::user()->school_id)->first();
        $address = $school->address;

        return view('/admin/postalrecord/create', [
            'date' => $date,
            'address' => $address,
        ]);
    }

    /**
     * Store a newly created postal record.
     *
     * @return array|null
     */
    public function store(PostalRecordRequest $request)
    {
        try {
            $school_id = Auth::user()->school_id;
            $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);

            $postalrecord = new PostalRecord;

            $postalrecord->school_id = $school_id;
            $postalrecord->academic_year_id = $academic_year->id;
            $postalrecord->type = $request->type;
            $postalrecord->post_type = $request->post_type;
            $postalrecord->reference_number = $request->reference_number;
            $postalrecord->confidential = $request->confidential;
            $postalrecord->sender_title = $request->sender_title;
            $postalrecord->sender_address = $request->sender_address;
            $postalrecord->receiver_title = $request->receiver_title;
            $postalrecord->receiver_address = $request->receiver_address;
            $postalrecord->postal_date = $request->postal_date;

            $file = $request->file('attachment');
            if ($file) {
                $folder = Auth::user()->school->slug.'/postalrecord';
                $path = $this->uploadFile($folder, $file);
                $postalrecord->attachment = $path;
            }

            $postalrecord->description = $request->description;
            $postalrecord->entry_by = Auth::user()->name;

            $postalrecord->save();

            $message = trans('messages.add_success_msg', ['module' => 'Postal Record']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $postalrecord,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_ADD_POSTAL_RECORD,
                $message
            );

            $res['success'] = $message;

            return $res;
        } catch (Exception $e) {
        }
    }

    /**
     * Display a specific postal record.
     *
     * @param  int  $id
     * @return AnonymousResourceCollection
     */
    public function show($id)
    {
        $postalrecord = PostalRecord::where('id', $id)->get();
        $postalrecord = PostalRecordResource::collection($postalrecord);

        return $postalrecord;
    }

    /**
     * Show postal record edit form.
     *
     * @param  int  $id
     * @return View
     */
    public function edit($id)
    {
        $postalrecord = PostalRecord::where([
            ['id', $id],
            ['school_id', Auth::user()->school_id],
        ])->first();

        return view('/admin/postalrecord/edit', [
            'postalrecord' => $postalrecord,
        ]);
    }

    /**
     * Update the specified postal record.
     *
     * @param  int  $id
     * @return array|null
     */
    public function update(Request $request, $id)
    {
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);

        try {
            $postalrecord = PostalRecord::find($id);

            $postalrecord->school_id = $school_id;
            $postalrecord->academic_year_id = $academic_year->id;
            $postalrecord->type = $request->type;
            $postalrecord->reference_number = $request->reference_number;
            $postalrecord->confidential = $request->confidential;
            $postalrecord->sender_title = $request->sender_title;
            $postalrecord->sender_address = $request->sender_address;
            $postalrecord->receiver_title = $request->receiver_title;
            $postalrecord->receiver_address = $request->receiver_address;
            $postalrecord->postal_date = $request->postal_date;

            $file = $request->file('attachment');
            if ($file) {
                $folder = Auth::user()->school->slug.'/postalrecord';
                $path = $this->uploadFile($folder, $file);
                $postalrecord->attachment = $path;
            }

            $postalrecord->description = $request->description;
            $postalrecord->entry_by = Auth::user()->name;

            $postalrecord->save();

            $message = trans('messages.update_success_msg', ['module' => 'Postal Record']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $postalrecord,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_EDIT_POSTAL_RECORD,
                $message
            );

            $res['success'] = $message;

            return $res;
        } catch (Exception $e) {
        }
    }

    /**
     * Delete a postal record.
     *
     * @param  int  $id
     * @return array|null
     */
    public function destroy($id)
    {
        \DB::beginTransaction();
        try {
            $postalrecord = PostalRecord::where('id', $id)->first();
            $postalrecord->delete();

            $message = trans('messages.delete_success_msg', ['module' => 'Postal Record']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $postalrecord,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_DELETE_POSTAL_RECORD,
                $message
            );

            $res['message'] = $message;

            \DB::commit();

            return $res;
        } catch (Exception $e) {
            \DB::rollBack();
        }
    }
}
