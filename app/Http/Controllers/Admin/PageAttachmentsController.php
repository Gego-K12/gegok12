<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassRoomPage;
use App\Models\ClassRoomPageAttachment;
use App\Traits\Common;
use App\Traits\LogActivity;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class PageAttachmentsController
 *
 * Handles uploading and deletion of classroom page attachments.
 * Includes file upload handling, media library integration,
 * authorization checks, and activity logging.
 */
class PageAttachmentsController extends Controller
{
    use Common;
    use LogActivity;

    /**
     * Store newly uploaded page attachments.
     *
     * Handles multiple attachment uploads for a classroom page,
     * stores files, associates them with the page, and logs activity.
     *
     * @param  int  $page_id
     * @return array|null
     */
    public function store(Request $request, $page_id)
    {
        //
        try {
            $page = ClassRoomPage::where('id', $page_id)->first();

            for ($i = 0; $i < $request->attachment_count; $i++) {
                $page_attachment = new ClassRoomPageAttachment;

                $page_attachment->page_id = $page_id;
                $page_attachment->status = 1;

                $attachment = 'attachment_file'.$i;

                $file = $request->file($attachment);
                if ($file) {
                    $folder = Auth::user()->school->slug.'/pages/'.$page_id.'/attachments';
                    $path = $this->uploadFile($folder, $file);
                    $page_attachment->attachment_file = $path;
                }

                $page_attachment->save();

                $page->addMedia($file)
                    ->toMediaCollection('page_attachments', env('FILESYSTEM_DISK'));
            }

            $message = trans('messages.add_success_msg', [':module' => 'Page Attachment']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $page,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_ADD_PAGE_ATTACHMENT,
                $message
            );

            $res['success'] = $message;

            return $res;
        } catch (Exception $e) {
        }
    }

    /**
     * Delete a page attachment.
     *
     * Removes the specified page attachment after authorization
     * and logs the delete activity.
     *
     * @param  int  $id
     * @return array|null
     */
    public function destroy($id)
    {
        //
        try {
            $page_attachment = ClassRoomPageAttachment::where('id', $id)->first();

            if (Gate::allows('page_attachment', $page_attachment)) {
                $page_attachment->delete();

                $message = trans('messages.delete_success_msg', ['module' => 'Page Attachment']);

                $ip = $this->getRequestIP();
                $this->doActivityLog(
                    $page_attachment,
                    Auth::user(),
                    ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                    LOGNAME_DELETE_PAGE_ATTACHMENT,
                    $message
                );

                $res['success'] = $message;

                return $res;
            } else {
                abort(403);
            }
        } catch (Exception $e) {
        }
    }
}
