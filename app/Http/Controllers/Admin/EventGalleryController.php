<?php

/**
 * SPDX-License-Identifier: MIT
 *
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use App\Events\Notification\SchoolNotificationEvent;
use App\Events\PushEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\EventGalleryRequest;
use App\Models\EventGallery;
use App\Traits\Common;
use App\Traits\LogActivity;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

/**
 * Class EventGalleryController
 *
 * Handles event gallery photo management for admin panel.
 * Includes photo upload, retrieval, notifications, and activity logging.
 */
class EventGalleryController extends Controller
{
    use Common, LogActivity;

    /**
     * Display a listing of the resource.
     *
     * Currently unused.
     *
     * @return Response|null
     */
    public function index()
    {
        // return view('admin.albums.create');
    }

    /**
     * Get all photos for a specific event.
     *
     * @param  int  $event_id
     * @return Collection
     */
    public function getPhoto($event_id)
    {
        return EventGallery::where([
            ['event_id', $event_id],
            ['school_id', Auth::user()->school_id],
        ])->get();
    }

    /**
     * Store newly uploaded event gallery photos.
     *
     * Accepts base64 encoded images, saves them to storage,
     * creates database records, fires notifications,
     * and logs activity.
     *
     * @param  int  $event_id
     * @return array
     */
    public function store(EventGalleryRequest $request, $event_id)
    {
        try {

            foreach ($request->file('photos') as $uploadedFile) {

                $school_id = Auth::user()->school_id;
                $location = Auth::user()->school->slug.'/photos/events/';
                $fileName = uniqid().'.'.$uploadedFile->getClientOriginalExtension();
                $db_path = $location.$fileName;
                $fileContent = file_get_contents($uploadedFile->getRealPath());

                // Save image using common storage helper
                $this->putContents($db_path, $fileContent);

                $photo = EventGallery::create([
                    'event_id' => $event_id,
                    'school_id' => $school_id,
                    'path' => $db_path,
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]);
            }

            // Push notification
            event(new PushEvent([
                'school_id' => $school_id,
                'message' => 'New Event Gallery Created',
                'type' => 'event_gallery',
            ]));

            // School notification
            event(new SchoolNotificationEvent([
                'school_id' => $school_id,
                'details' => trans('notification.event_gallery_add_success_msg'),
            ]));

            // Activity log
            $this->doActivityLog(
                $photo,
                Auth::user(),
                [
                    'ip' => $this->getRequestIP(),
                    'details' => $_SERVER['HTTP_USER_AGENT'] ?? null,
                ],
                LOGNAME_EVENT_PHOTO,
                'Events photos added Successfully'
            );

            return ['message' => 'Uploaded Successfully'];

            return ['count' => 'Select Atleast One'];
        } catch (Exception $e) {
            // You may log the exception here if needed
            // report($e);
        }
    }

    /**
     * Display all photos for a specific event.
     *
     * @param  int  $event_id
     * @return Collection
     */
    public function show($event_id)
    {
        return EventGallery::where([
            ['event_id', $event_id],
            ['school_id', Auth::user()->school_id],
        ])->get();
    }
}
