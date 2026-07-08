<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use App\Events\Notification\SchoolNotificationEvent;
use App\Events\PushEvent;
use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\BulletinRequest;
use App\Models\Bulletin;
use App\Models\Subscription;
use App\Traits\Common;
use App\Traits\LogActivity;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Log;

/**
 * Class BulletinsController
 *
 * Controller for managing school bulletins/magazines: listing,
 * creating, downloading and deleting bulletin records.
 */
class BulletinsController extends Controller
{
    use Common;
    use LogActivity;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
    }

    public function index(Request $request)
    {
        //
        $academic_year = $this->academic_year;
        $bulletins = Bulletin::where([['school_id', Auth::user()->school_id], ['academic_year_id', $academic_year->id]])->orderBy('year', 'desc');
        if (count((array) \Request::getQueryString()) > 0) {
            if ($request->search != '') {
                $bulletins = $bulletins->where('name', 'LIKE', '%'.$request->search.'%');
            }
        }
        $bulletins = $bulletins->get();
        $count = $bulletins->count();

        return view('/admin/bulletins/index', ['bulletins' => $bulletins, 'count' => $count]);
    }

    public function getDate()
    {
        $array = [];

        $academic_year = $this->academic_year;
        $start = Carbon::now()->format('Y');
        $end = Carbon::now()->subYears(25)->format('Y');

        $array['academic_year_id'] = $academic_year->id;
        $array['start'] = $start;
        $array['end'] = $end;

        return $array;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $academic_year = $this->academic_year;
        $count = Bulletin::where([['school_id', Auth::user()->school_id], ['academic_year_id', $academic_year->id]])->count();
        $subscription = Subscription::where('school_id', Auth::user()->school_id)->first();

        return view('/admin/bulletins/create', ['count' => $count, 'subscription' => $subscription]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BulletinRequest $request)
    {
        try {
            $school_id = Auth::user()->school_id;
            $academic_year = $this->academic_year;
            $bulletin = new Bulletin;

            $bulletin->school_id = $school_id;
            $bulletin->academic_year_id = $academic_year->id;
            $bulletin->name = $request->name;
            $bulletin->year = $request->year;
            $bulletin->cover_image = $request->cover_image;

            $cover_image = $request->file('cover_image');
            if ($cover_image) {
                $folder = Auth::user()->school->slug.'/magazine/coverimage';
                // $folder=Auth::user()->school_id.'/magazine/coverimage';
                $cover_image_path = $this->uploadFile($folder, $cover_image);
                $bulletin->cover_image = $cover_image_path;
            }

            $file = $request->file('bulletin_file');
            if ($file) {
                $folder = Auth::user()->school->slug.'/magazine';
                // $folder=Auth::user()->school_id.'/magazine';
                $bulletin_file_path = $this->uploadFile($folder, $file);
                $bulletin->bulletin_file = $bulletin_file_path;
            }
            $bulletin->save();

            $message = trans('messages.add_success_msg', ['module' => 'Magazine']);

            $data = [];

            $data['school_id'] = Auth::user()->school_id;
            $data['message'] = 'New Magazine created';
            $data['type'] = 'bulletin';

            event(new PushEvent($data));

            $array = [];

            $array['school_id'] = Auth::user()->school_id;
            $array['details'] = trans('notification.magazine_add_success_msg');

            event(new SchoolNotificationEvent($array));

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $bulletin,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_ADD_MAGAZINE,
                $message
            );

            $res['success'] = trans('messages.add_success_msg', ['module' => 'Magazine']);

            return $res;
        } catch (Exception $e) {
            Log::info($e->getMessage());
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
        $bulletin = Bulletin::where('id', $id)->first();
        $bulletin->delete();

        $message = trans('messages.delete_success_msg', ['module' => 'Magazine']);

        $ip = $this->getRequestIP();
        $this->doActivityLog(
            $bulletin,
            Auth::user(),
            ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
            LOGNAME_DELETE_MAGAZINE,
            $message
        );

        return redirect()->back()->with('successmessage', $message);
    }

    public function downloadattachments(Request $request, $id)
    {
        $bulletin = Bulletin::where('id', $id)->first();
        if (Gate::allows('bulletin', $bulletin)) {
            $file = $bulletin->bulletin_file;
            $path = $this->getFilePathforDownload(env('FILESYSTEM_DISK'), $file);
            $name = 'bulletin_year'.'_'.$bulletin->year.'.pdf';
            $headers = [
                'Content-Disposition' => 'attachment; filename="'.$name.'"',
            ];

            $message = trans('messages.download_success_msg', ['module' => 'Magazine']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $bulletin,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_DOWNLOAD_MAGAZINE,
                $message
            );

            // return response()->download($path,$file,$headers);
            return \Response::make($path, 200, $headers);
        } else {
            abort(403);
        }
    }
}
