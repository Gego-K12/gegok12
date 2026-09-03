<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Livewire\Admin\CustomFields;

use App\Models\CustomField;
use Auth;
use Livewire\Component;
use Livewire\WithPagination;

class CustomFieldManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';

    public array $entityTypes = [
        'student' => 'Student',
        'teacher' => 'Teacher',
        'staff' => 'Staff',
        'parent' => 'Parent',
        'admission' => 'Admission',
        'event' => 'Event',
    ];

    public array $fieldTypes = [
        'text' => 'Text',
        'textarea' => 'Textarea',
        'number' => 'Number',
        'email' => 'Email',
        'date' => 'Date',
        'select' => 'Select',
        'radio' => 'Radio',
        'checkbox' => 'Checkbox',
        'file' => 'File',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function toggleStatus($id)
    {
        $field = CustomField::findOrFail($id);
        $field->update(['status' => ! $field->status]);
    }

    public function render()
    {
        $schoolId = Auth::user()->school_id;

        $fieldsQuery = CustomField::where('school_id', $schoolId)
            ->with(['entityConfigs' => fn($query) => $query->where('is_enabled', true)])
            ->orderBy('id', 'desc');

        if ($this->search) {
            $fieldsQuery = $fieldsQuery->where('label', 'like', '%' . $this->search . '%');
        }

        $fields = $fieldsQuery->paginate(10);

        return view('livewire.admin.custom-fields.custom-field-manager', [
            'fields' => $fields,
        ]);
    }
}
