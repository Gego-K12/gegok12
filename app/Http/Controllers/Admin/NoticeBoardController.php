<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use App\Events\Notification\ClassNotificationEvent;
use App\Events\Notification\SchoolNotificationEvent;
use App\Events\Notification\TeacherNotificationEvent;
use App\Events\PushEvent;
use App\Events\StandardPushEvent;
use App\Events\TeacherPushEvent;
use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\BackgroundImageRequest;
use App\Http\Requests\NoticeRequest;
use App\Http\Requests\NoticeUpdateRequest;
use App\Http\Resources\backgroundImagesResource;
use App\Http\Resources\Notice as NoticeResource;
use App\Http\Resources\StandardLink as StandardLinkResource;
use App\Models\BackgroundImage;
use App\Models\NoticeBoard;
use App\Models\StandardLink;
use App\Services\NoticeBoardReaderService;
use App\Traits\Common;
use App\Traits\LogActivity;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

/**
 * Class NoticeBoardController
 *
 * Manages notice board operations including listing, creating,
 * updating, deleting notices and handling notifications,
 * background images, and activity logging.
 */
class NoticeBoardController extends Controller
{
    use Common;
    use LogActivity;

    public function __construct(protected NoticeBoardReaderService $noticeBoardReader) {}

    /**
     * Get notice list based on filters.
     *
     * Supports filtering by expiry, standard link, and search keyword.
     *
     * @return AnonymousResourceCollection
     */
    public function showList(Request $request)
    {
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);

        $notice = $this->noticeBoardReader->paginatedList(
            schoolId: $school_id,
            academicYearId: $academic_year->id,
            standardLinkIds: null,
            includeNullScope: false,
            excludeTeacherType: false,
            includeExpired: $request->showExpired == 'true',
            standardLinkFilter: $request->standardLink_id ?: null,
            search: $request->search ?: null,
        );

        return NoticeResource::collection($notice);
    }

    /**
     * Display notice board index page.
     *
     * @return View
     */
    public function index()
    {
        $query = \Request::getQueryString();

        return view('/admin/noticeboard/index', ['query' => $query]);
    }

    /**
     * Get standard links and background images.
     *
     * @return array
     */
    public function list()
    {
        return $this->noticeBoardReader->filterOptions(Auth::user()->school_id);
    }

    /**
     * Show notice creation form.
     *
     * @return View
     */
    public function create()
    {
        $e_date = Carbon::now()->addWeek(1)->format('d-m-Y H:i:s');

        return view('/admin/noticeboard/create', ['e_date' => $e_date]);
    }

    /**
     * Store a newly created notice.
     *
     * Handles notice creation, file upload, push notifications,
     * and activity logging.
     *
     * @return array|null
     */
    public function store(NoticeRequest $request)
    {
        try {
            $date = Carbon::now();
            $school_id = Auth::user()->school_id;
            $academic_year = SiteHelper::getAcademicYear($school_id);

            $notice = new NoticeBoard;

            $notice->school_id = $school_id;
            $notice->academic_year_id = $academic_year->id;
            $notice->background_id = $request->bg_id;
            if ($request->type == 'class') {
                $notice->standardLink_id = $request->standardLink_id;
            }
            $notice->type = $request->type;
            $notice->title = $request->title;
            $notice->publish_date = date('Y-m-d H:i:s', strtotime($request->publish_date));
            $notice->expire_date = date('Y-m-d H:i:s', strtotime($request->expire_date));
            $notice->description = $request->description;
            $notice->status = 1;

            if ($date > $notice->expire_date) {
                $notice->status = 0;
            }

            $file = $request->file('attachment_file');
            if ($file) {
                $folder = Auth::user()->school->slug.'/notice';
                $path = $this->uploadFile($folder, $file);
                $notice->attachment_file = $path;
            }

            $notice->save();

            if ($request->type == 'class') {
                $data = [];

                $data['school_id'] = $school_id;
                $data['standard_id'] = $notice->standardLink_id;
                $data['message'] = 'New Notice Added';
                $data['type'] = 'notice';
                // $data['notice_type']    =   'class';

                event(new StandardPushEvent($data));

                $array = [];

                $array['school_id'] = Auth::user()->school_id;
                $array['standardLink_id'] = $notice->standardLink_id;
                $array['details'] = trans('notification.notice_add_success_msg');

                event(new ClassNotificationEvent($array));
            } elseif ($request->type == 'school') {
                $data = [];

                $data['school_id'] = $school_id;
                $data['message'] = 'New Notice Added';
                $data['type'] = 'notice';
                // $data['notice_type']=   'school';

                event(new PushEvent($data));

                $array = [];

                $array['school_id'] = Auth::user()->school_id;
                $array['details'] = trans('notification.notice_add_success_msg');

                event(new SchoolNotificationEvent($array));
            } elseif ($request->type == 'teacher') {
                $data = [];

                $data['school_id'] = $school_id;
                $data['message'] = 'New Notice Added';
                $data['type'] = 'notice';

                event(new TeacherPushEvent($data));

                $array = [];

                $array['school_id'] = Auth::user()->school_id;
                $array['details'] = trans('notification.notice_add_success_msg');

                event(new TeacherNotificationEvent($array));
            }

            $message = trans('messages.add_success_msg', ['module' => 'Notice']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $notice,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_ADD_NOTICE,
                $message
            );

            $res['success'] = trans('messages.add_success_msg', ['module' => 'Notice']);

            return $res;
        } catch (Exception $e) {
        }
    }

    /**
     * Upload and store background image.
     *
     * @return array
     */
    public function addimage(BackgroundImageRequest $request)
    {
        $backgroundimages = new BackgroundImage;
        $backgroundimages->school_id = Auth::user()->school_id;
        $backgroundimages->type = 'event';
        $file = $request->file('bg_image');
        if ($file) {
            $folder = Auth::user()->school->slug.'/bgimage';
            $path = $this->uploadFile($folder, $file);
            $backgroundimages->background_image = $path;
        }

        $backgroundimages->save();

        $message['success'] = 'New backgroundimages Added';

        return $message;

    }

    /**
     * Display a specific notice.
     *
     * @param  int  $id
     * @return array
     */
    public function show($id)
    {
        $notice = NoticeBoard::where([['id', $id], ['school_id', Auth::user()->school_id]])->first();

        $backgroundimages = BackgroundImage::where('school_id', Auth::user()->school_id)->latest()->get();
        $backgroundimages = backgroundImagesResource::collection($backgroundimages);

        $standardLink = StandardLink::with('standard', 'section')->where('school_id', Auth::user()->school_id)->get();
        $standardLink = StandardLinkResource::collection($standardLink);

        $array = [];

        $array['id'] = $notice->id;
        $array['school_id'] = $notice->school_id;
        $array['standardLink_id'] = $notice->standardLink_id;
        $array['type'] = $notice->type;
        $array['title'] = $notice->title;
        $array['publish_date'] = date('d-m-Y h:i:s a', strtotime($notice->publish_date));
        $array['expire_date'] = date('d-m-Y h:i:s a', strtotime($notice->expire_date));
        $array['description'] = $notice->description;
        $array['attachment_file'] = $notice->attachment_file == null ? '' : $notice->AttachmentPath;
        $array['standardLinklist'] = $standardLink;
        $array['backgroundimages'] = $backgroundimages;
        $array['bg_id'] = $notice->background_id == null ? '' : $notice->background_id;
        $array['bg_image'] = $notice->background_id == null ? '' : $notice->backgroundimage->AttachmentPath;

        return $array;
    }

    /**
     * Show edit notice form.
     *
     * @param  int  $id
     * @return View
     */
    public function edit($id)
    {
        $notice = NoticeBoard::where([['id', $id], ['school_id', Auth::user()->school_id]])->first();

        if (Gate::allows('notice', $notice)) {
            return view('/admin/noticeboard/edit', ['notice' => $notice]);
        } else {
            abort(403);
        }
    }

    /**
     * Update the specified notice.
     *
     * @param  int  $id
     * @return array|null
     */
    public function update(NoticeUpdateRequest $request, $id)
    {
        try {
            $date = Carbon::now();
            $school_id = Auth::user()->school_id;
            $notice = NoticeBoard::where('id', $id)->first();

            if ($request->type == 'class') {
                $notice->standardLink_id = $request->standardLink_id;
            }
            $notice->background_id = $request->bg_id;
            $notice->type = $request->type;
            $notice->title = $request->title;
            $notice->publish_date = date('Y-m-d H:i:s', strtotime($request->publish_date));
            $notice->expire_date = date('Y-m-d H:i:s', strtotime($request->expire_date));
            $notice->description = $request->description;

            $file = $request->file('attachment_file');
            if ($file) {
                $folder = Auth::user()->school->slug.'/notice';
                $path = $this->uploadFile($folder, $file);
                $notice->attachment_file = $path;
            } else {
                $notice->attachment_file = $notice->attachment_file;
            }

            if ($date > $notice->expire_date) {
                $notice->status = 0;
            }
            $notice->save();

            if ($request->type == 'class') {
                $data = [];

                $data['school_id'] = $school_id;
                $data['standard_id'] = $notice->standardLink_id;
                $data['message'] = 'Notice Updated';
                $data['type'] = 'notice';

                event(new StandardPushEvent($data));

                $array = [];

                $array['school_id'] = Auth::user()->school_id;
                $array['standardLink_id'] = $notice->standardLink_id;
                $array['details'] = trans('notification.notice_update_success_msg');

                event(new ClassNotificationEvent($array));
            } elseif ($request->type == 'school') {
                $data = [];

                $data['school_id'] = $school_id;
                $data['message'] = 'Notice Updated';
                $data['type'] = 'notice';

                event(new PushEvent($data));

                $array = [];

                $array['school_id'] = Auth::user()->school_id;
                $array['details'] = trans('notification.notice_update_success_msg');

                event(new SchoolNotificationEvent($array));
            } elseif ($request->type == 'teacher') {
                $data = [];

                $data['school_id'] = $school_id;
                $data['message'] = 'Notice Updated';
                $data['type'] = 'notice';

                event(new TeacherPushEvent($data));

                $array = [];

                $array['school_id'] = Auth::user()->school_id;
                $array['details'] = trans('notification.notice_update_success_msg');

                event(new TeacherNotificationEvent($array));
            }

            $message = trans('messages.update_success_msg', ['module' => 'Notice']);
            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $notice,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_EDIT_NOTICE,
                $message
            );

            $res['success'] = trans('messages.update_success_msg', ['module' => 'Notice']);

            return $res;
        } catch (Exception $e) {
        }
    }

    /**
     * Delete a notice.
     *
     * @param  int  $id
     * @return array|null
     */
    public function destroy($id)
    {
        try {
            $notice = NoticeBoard::where('id', $id)->first();
            if (Gate::allows('notice', $notice)) {
                $notice->delete();

                $message = trans('messages.delete_success_msg', ['module' => 'Notice']);

                $ip = $this->getRequestIP();
                $this->doActivityLog(
                    $notice,
                    Auth::user(),
                    ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                    LOGNAME_DELETE_NOTICE,
                    $message
                );
                $res['success'] = $message;

                return $res;
                // return redirect()->back()->with('successmessage',$message);
            } else {
                abort(403);
            }
        } catch (Exception $e) {
        }
    }
}
