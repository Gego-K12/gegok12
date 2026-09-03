<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Livewire\Admin\Student\Profile;

use App\Models\Notes as NotesModel;
use App\Models\User;
use App\Traits\Common;
use App\Traits\LogActivity;
use App\Traits\NotesProcess;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class Notes extends Component
{
    use Common, LogActivity, NotesProcess;

    public string $name;

    public $entityId;

    public $schoolId;

    public string $notes = '';

    public $editingId = null;

    public function mount(string $name)
    {
        $this->name = $name;

        $student = User::where('name', $name)->first();
        $this->entityId = $student->id;
        $this->schoolId = $student->school_id;
    }

    protected function rules(): array
    {
        return [
            'notes' => ['required', 'regex:/^\p{L}[\p{L} A-Za-z0-9_~\-!,@#\$%\^&*.:(\)((?:\'|").*(?:\'|"))\s]+$/u'],
        ];
    }

    protected function messages(): array
    {
        return [
            'notes.required' => __('notes.notes_required'),
            'notes.regex' => __('notes.notes_checknotes'),
        ];
    }

    public function edit(int $id): void
    {
        $note = NotesModel::where('id', $id)->first();

        abort_unless(Gate::allows('note', $note), 403);

        $this->notes = $note->notes;
        $this->editingId = $id;
    }

    public function cancelEdit(): void
    {
        $this->reset('notes', 'editingId');
    }

    public function save(): void
    {
        $this->validate();

        $userId = Auth::id();

        if ($this->editingId === null) {
            $note = $this->createNotes($this->notes, $this->schoolId, $this->entityId, User::class, $userId, $userId);
        } else {
            $note = NotesModel::where('id', $this->editingId)->first();
            $note->notes = $this->notes;
            $note->save();
        }

        $this->doActivityLog(
            $note,
            Auth::user(),
            ['ip' => $this->getRequestIP(), 'details' => request()->userAgent()],
            LOGNAME_ADD_NOTE,
            __('notes.notes_message')
        );

        $this->reset('notes', 'editingId');
    }

    public function delete(int $id): void
    {
        $note = NotesModel::where('id', $id)->first();
        $note->delete();

        $this->doActivityLog(
            $note,
            Auth::user(),
            ['ip' => $this->getRequestIP(), 'details' => request()->userAgent()],
            LOGNAME_DELETE_NOTE,
            'Notes Deleted Successfully'
        );

        if ($this->editingId === $id) {
            $this->reset('notes', 'editingId');
        }
    }

    public function render()
    {
        $tasks = NotesModel::where([
            ['entity_id', $this->entityId],
            ['entity_name', User::class],
        ])->orderByDesc('id')->get();

        return view('livewire.admin.student.profile.notes', ['tasks' => $tasks]);
    }
}
