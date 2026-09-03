<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Livewire\Admin\Events;

use App\Models\EventGallery;
use App\Models\Events;
use App\Models\Notes as NotesModel;
use App\Traits\Common;
use App\Traits\LogActivity;
use App\Traits\NotesProcess;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithFileUploads;

/**
 * Event Details tabs (Description/Photos/Notes/Additional Info) -- ports
 * resources/assets/js/components/event/details/EventTab.vue and its
 * children (Description.vue, Gallery.vue, ShowImage.vue, PhotosSlider.vue,
 * the generic notes.vue). That Vue component tree is shared by 5 other
 * portals (Teacher/Student/Reception/Alumni/Accountant), so it stays
 * untouched -- this is a standalone replacement for the admin page only.
 */
class EventDetails extends Component
{
    use Common, LogActivity, NotesProcess, WithFileUploads;

    public Events $event;

    public string $activeTab = 'description';

    /** @var \Livewire\Features\SupportFileUploads\TemporaryUploadedFile[] */
    public array $newPhotos = [];

    public bool $showLightbox = false;

    public int $lightboxIndex = 0;

    public string $noteText = '';

    public $editingNoteId = null;

    public function mount(int $eventId)
    {
        $this->event = Events::where('id', $eventId)->where('school_id', Auth::user()->school_id)->firstOrFail();

        abort_unless(Gate::allows('event', $this->event), 403);
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    protected function rules(): array
    {
        return [
            'newPhotos' => ['required', 'array', 'min:1'],
            'newPhotos.*' => ['image', 'max:2048'],
        ];
    }

    protected function messages(): array
    {
        return [
            'newPhotos.required' => 'Select at least one photo',
            'newPhotos.*.image' => 'Only image files are allowed',
            'newPhotos.*.max' => 'Each image must be under 2MB',
        ];
    }

    public function uploadPhotos(): void
    {
        $this->validate();

        $folder = Auth::user()->school->slug.'/photos/events';

        foreach ($this->newPhotos as $photo) {
            EventGallery::create([
                'school_id' => $this->event->school_id,
                'event_id' => $this->event->id,
                'path' => $this->uploadFile($folder, $photo),
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);
        }

        $this->doActivityLog(
            $this->event,
            Auth::user(),
            ['ip' => $this->getRequestIP(), 'details' => request()->userAgent()],
            LOGNAME_EVENT_PHOTO,
            'Events photos added Successfully'
        );

        $this->reset('newPhotos');
    }

    public function openLightbox(int $index): void
    {
        $this->lightboxIndex = $index;
        $this->showLightbox = true;
    }

    public function closeLightbox(): void
    {
        $this->showLightbox = false;
    }

    public function editNote(int $id): void
    {
        $note = NotesModel::where('id', $id)->first();

        abort_unless(Gate::allows('note', $note), 403);

        $this->noteText = $note->notes;
        $this->editingNoteId = $id;
    }

    public function cancelEditNote(): void
    {
        $this->reset('noteText', 'editingNoteId');
    }

    public function saveNote(): void
    {
        $this->validate([
            'noteText' => ['required', 'regex:/^\p{L}[\p{L} A-Za-z0-9_~\-!,@#\$%\^&*.:(\)((?:\'|").*(?:\'|"))\s]+$/u'],
        ], [
            'noteText.required' => __('notes.notes_required'),
            'noteText.regex' => __('notes.notes_checknotes'),
        ]);

        $userId = Auth::id();

        if ($this->editingNoteId === null) {
            $note = $this->createNotes($this->noteText, $this->event->school_id, $this->event->id, Events::class, $userId, $userId);
        } else {
            $note = NotesModel::where('id', $this->editingNoteId)->first();
            $note->notes = $this->noteText;
            $note->save();
        }

        $this->doActivityLog(
            $note,
            Auth::user(),
            ['ip' => $this->getRequestIP(), 'details' => request()->userAgent()],
            LOGNAME_ADD_NOTE,
            __('notes.notes_message')
        );

        $this->reset('noteText', 'editingNoteId');
    }

    public function deleteNote(int $id): void
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

        if ($this->editingNoteId === $id) {
            $this->reset('noteText', 'editingNoteId');
        }
    }

    public function render()
    {
        $photos = EventGallery::where('event_id', $this->event->id)
            ->where('school_id', $this->event->school_id)
            ->orderByDesc('id')
            ->get();

        $notes = NotesModel::where([
            ['entity_id', $this->event->id],
            ['entity_name', Events::class],
        ])->orderByDesc('id')->get();

        return view('livewire.admin.events.event-details', [
            'photos' => $photos,
            'notes' => $notes,
        ]);
    }
}
