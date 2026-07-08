<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserDocument as UserDocumentResource;
use App\Models\Document;
use App\Models\User;
use App\Traits\Common;
use App\Traits\LogActivity;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

/**
 * Class DocumentsController
 *
 * Controller for managing user documents: listing, uploading,
 * updating (versioning) and deleting documents.
 */
class DocumentsController extends Controller
{
    use Common;
    use LogActivity;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($name)
    {
        //
        $user = User::where('name', $name)->first();
        $documents = Document::where('user_id', $user->id)
            ->where('status', 1)
            ->orderby('id', 'desc')
            ->get();

        $documents = UserDocumentResource::collection($documents);

        return $documents;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request, $name)
    {
        //
        try {
            $user = User::where('name', $name)->first();
            $file = $request->file('attachment');

            if ($file) {
                $bigfolder = Auth::user()->school->slug.'/files/large';
                $path = $this->uploadFile($bigfolder, $file);
            }

            $document = new Document;

            $document->school_id = $user->school_id;
            $document->user_id = $user->id;
            $document->type = $request->type;
            $document->name = $request->title;
            $document->file_path = $path;

            $document->save();

            $user->addMedia($file)->toMediaCollection('documents', env('FILESYSTEM_DISK'));

            $message = trans('messages.add_success_msg', ['module' => 'Document']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $document,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_ADD_DOCUMENT,
                $message
            );
            $res['success'] = $message;

            return $res;
        } catch (Exception $e) {
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
        $document = Document::where('id', $id)->first();

        $array = [];

        $array['type'] = $document->type;
        $array['title'] = $document->name;

        return $array;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $name, $id)
    {
        //
        try {
            $user = User::where('name', $name)->first();
            $file = $request->file('attachment');
            if ($file) {
                $bigfolder = Auth::user()->school->slug.'/files/large';
                $path = $this->uploadFile($bigfolder, $file);
            }
            $old_doc = Document::where('id', $id)->first();

            $old_doc->status = 0;

            $old_doc->delete();

            $document = new Document;

            $document->school_id = $user->school_id;
            $document->user_id = $user->id;
            $document->version = $old_doc->version + 1;
            $document->type = $request->type;
            $document->name = $request->title;
            $document->file_path = $path;

            $document->save();

            $user->addMedia($file)->toMediaCollection('documents', env('FILESYSTEM_DISK'));

            $message = trans('messages.update_success_msg', ['module' => 'Document']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $document,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_EDIT_DOCUMENT,
                $message
            );
            $res['success'] = $message;

            return $res;
        } catch (Exception $e) {
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
        try {
            $document = Document::where('id', $id)->first();
            if (Gate::allows('document', $document)) {
                $document->delete();

                $message = trans('messages.delete_success_msg', ['module' => 'Document']);

                $ip = $this->getRequestIP();
                $this->doActivityLog(
                    $document,
                    Auth::user(),
                    ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                    LOGNAME_DELETE_DOCUMENT,
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
