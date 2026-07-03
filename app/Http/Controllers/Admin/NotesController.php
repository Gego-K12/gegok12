<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\NotesRequest;
use App\Models\Notes;
use App\Models\User;
use App\Traits\Common;
use App\Traits\LogActivity;
use App\Traits\NotesProcess;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

/**
 * Class NotesController
 *
 * Handles CRUD operations for notes related to different entities
 * within the admin panel. Includes permission checks, activity logging,
 * and note processing through reusable traits.
 */
class NotesController extends Controller
{
    use Common;
    use LogActivity;
    use NotesProcess;

    /**
     * Display a list of notes for a given entity.
     *
     * Fetches all notes related to the provided entity ID and entity name,
     * ordered by latest first.
     *
     * @return Collection
     */
    public function index(Request $request)
    {
        $note = Notes::where([
            ['entity_id', $request->entity_id],
            ['entity_name', $request->entity_name],
        ])->orderby('id', 'desc')->get();

        return $note;
    }

    /**
     * Show the form for creating a new note.
     *
     * @return View
     */
    public function create()
    {
        return view('admin.notes.add_notes');
    }

    /**
     * Store or update a note.
     *
     * Creates a new note if the ID is empty, otherwise updates
     * the existing note. Logs the activity after completion.
     *
     * @return array
     */
    public function store(NotesRequest $request)
    {
        $userid = Auth::id();

        if ($request->id == '') {
            $note = $this->createNotes(
                $request->notes,
                $request->school_id,
                $request->entity_id,
                $request->entity_name,
                $userid,
                $userid
            );
        } else {
            $note = Notes::where('id', $request->id)->first();
            $note->notes = $request->notes;
            $note->save();
        }

        $message = __('notes.notes_message');
        $ip = $this->getRequestIP();

        $this->doActivityLog(
            $note,
            Auth::user(),
            ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
            LOGNAME_ADD_NOTE,
            $message
        );

        $res['message'] = __('notes.notes_message');

        return $res;
    }

    /**
     * Edit an existing note.
     *
     * Returns the note content if the user has permission,
     * otherwise throws a 403 unauthorized error.
     *
     * @param  int  $id
     * @return string
     */
    public function edit($id)
    {
        $notes = Notes::where('id', $id)->first();

        if (Gate::allows('note', $notes)) {
            return $notes->notes;
        } else {
            abort(403);
        }
    }

    /**
     * Delete a note.
     *
     * Removes the specified note and logs the delete activity.
     *
     * @param  int  $id
     * @return array|null
     */
    public function delete($id)
    {
        try {
            $notes = Notes::where('id', $id)->first();
            $notes->delete();

            $message = 'Notes Deleted Successfully';
            $ip = $this->getRequestIP();

            $this->doActivityLog(
                $notes,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_DELETE_NOTE,
                $message
            );

            $res['message'] = 'Notes Deleted Successfully';

            return $res;
        } catch (Exception $e) {
        }
    }
}
