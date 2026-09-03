<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Livewire\Admin\Student\Profile;

use App\Http\Resources\UserDocument as UserDocumentResource;
use App\Models\Document;
use App\Models\User;
use App\Traits\Common;
use App\Traits\LogActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class Documents extends Component
{
    use Common, LogActivity, WithFileUploads;

    public const TYPES = [
        'certificates' => 'Certificates',
        'id_proof' => 'ID Proof',
        'others' => 'Others',
    ];

    public string $name;

    public $studentId;

    public $schoolId;

    public bool $showModal = false;

    public string $type = '';

    public string $title = '';

    public $attachment = null;

    public ?string $attachmentPath = null;

    public $editingId = null;

    public function mount(string $name)
    {
        $this->name = $name;

        $student = User::where('name', $name)->first();
        $this->studentId = $student->id;
        $this->schoolId = $student->school_id;
    }

    public function updatedAttachment(): void
    {
        if (! $this->attachment instanceof TemporaryUploadedFile) {
            return;
        }

        $folder = Auth::user()->school->slug.'/files/large';
        $this->attachmentPath = $this->uploadFile($folder, $this->attachment);
    }

    public function openAddModal(): void
    {
        $this->reset('type', 'title', 'attachment', 'attachmentPath', 'editingId');
        $this->resetErrorBag();
        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $document = Document::where('id', $id)->first();

        $this->reset('attachment', 'attachmentPath');
        $this->resetErrorBag();
        $this->type = $document->type;
        $this->title = $document->name;
        $this->editingId = $id;
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
    }

    protected function rules(): array
    {
        return [
            'type' => ['required', 'in:'.implode(',', array_keys(self::TYPES))],
            'title' => ['required', 'string'],
            'attachmentPath' => [$this->editingId === null ? 'required' : 'nullable'],
        ];
    }

    protected function validationAttributes(): array
    {
        return ['attachmentPath' => 'attachment'];
    }

    public function save(): void
    {
        $this->validate();

        $oldDocument = $this->editingId !== null ? Document::where('id', $this->editingId)->first() : null;

        $document = new Document;
        $document->school_id = $this->schoolId;
        $document->user_id = $this->studentId;
        $document->type = $this->type;
        $document->name = $this->title;
        $document->file_path = $this->attachmentPath ?? $oldDocument?->file_path;

        if ($oldDocument !== null) {
            $document->version = $oldDocument->version + 1;
        }

        $document->save();

        $oldDocument?->delete();

        $message = $oldDocument !== null
            ? trans('messages.update_success_msg', ['module' => 'Document'])
            : trans('messages.add_success_msg', ['module' => 'Document']);

        $this->doActivityLog(
            $document,
            Auth::user(),
            ['ip' => $this->getRequestIP(), 'details' => request()->userAgent()],
            $oldDocument !== null ? LOGNAME_EDIT_DOCUMENT : LOGNAME_ADD_DOCUMENT,
            $message
        );

        $this->closeModal();
        $this->reset('type', 'title', 'attachment', 'attachmentPath', 'editingId');
    }

    public function delete(int $id): void
    {
        $document = Document::where('id', $id)->first();

        abort_unless(Gate::allows('document', $document), 403);

        $document->delete();

        $this->doActivityLog(
            $document,
            Auth::user(),
            ['ip' => $this->getRequestIP(), 'details' => request()->userAgent()],
            LOGNAME_DELETE_DOCUMENT,
            trans('messages.delete_success_msg', ['module' => 'Document'])
        );
    }

    public function render()
    {
        $documents = Document::where('user_id', $this->studentId)
            ->orderByDesc('id')
            ->get()
            ->map(fn ($document) => (new UserDocumentResource($document))->toArray(request()))
            ->all();

        return view('livewire.admin.student.profile.documents', ['documents' => $documents]);
    }
}
