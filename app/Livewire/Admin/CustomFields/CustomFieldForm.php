<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Livewire\Admin\CustomFields;

use App\Models\CustomField;
use App\Models\CustomFieldEntityConfig;
use Auth;
use Illuminate\Support\Str;
use Livewire\Component;

class CustomFieldForm extends Component
{
    public ?int $field_id = null;

    public $label = '';

    public $placeholder = '';

    public $instructions = '';

    public $validationPattern = '';

    public $field_type = 'text';

    public $status = true;

    public $options = [];

    public array $entityEnabled = [];

    public array $entityRequired = [];

    public array $entityTypes = [
        'student' => 'Student',
        'teacher' => 'Teacher',
        'staff' => 'Staff',
        'parent' => 'Parent',
        'admission' => 'Admission',
        'event' => 'Event'

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

    public function mount($id = null)
    {
        $this->entityEnabled = array_fill_keys(array_keys($this->entityTypes), false);
        $this->entityRequired = array_fill_keys(array_keys($this->entityTypes), false);

        if (! $id) {
            return;
        }

        $field = CustomField::with(['options', 'entityConfigs'])->findOrFail($id);

        $this->field_id = $field->id;
        $this->label = $field->label;
        $this->placeholder = $field->placeholder;
        $this->instructions = $field->instructions;
        $this->validationPattern = $field->validation_pattern;
        $this->field_type = $field->field_type;
        $this->status = $field->status;
        $this->options = $field->options->map(fn($option) => [
            'option_label' => $option->option_label,
            'option_value' => $option->option_value,
        ])->values()->all();

        $configsByType = $field->entityConfigs->keyBy('entity_type');

        foreach (array_keys($this->entityTypes) as $entityType) {
            $this->entityEnabled[$entityType] = (bool) $configsByType->get($entityType)?->is_enabled;
            $this->entityRequired[$entityType] = (bool) $configsByType->get($entityType)?->is_required;
        }
    }

    protected function rules()
    {
        $rules = [
            'label' => 'required|string|max:255',
            'placeholder' => 'nullable|string|max:255',
            'instructions' => 'nullable|string|max:1000',
            'validationPattern' => ['nullable', 'string', 'max:255', function ($attribute, $value, $fail) {
                if ($value && @preg_match(CustomField::normalizePattern($value), '') === false) {
                    $fail('Enter a valid regular expression, e.g. /^[a-zA-Z0-9_]*$/');
                }
            }],
            'field_type' => 'required|in:text,textarea,number,email,date,select,radio,checkbox,file',
        ];

        if (in_array($this->field_type, ['select', 'radio', 'checkbox'])) {
            $rules['options'] = 'required|array|min:1';
            $rules['options.*.option_label'] = 'required|string|max:255';
        }

        return $rules;
    }

    public function addOption()
    {
        $this->options[] = ['option_label' => '', 'option_value' => ''];
    }

    public function removeOption($index)
    {
        unset($this->options[$index]);
        $this->options = array_values($this->options);
    }

    public function save()
    {
        $this->validate();

        $schoolId = Auth::user()->school_id;

        $fieldName = Str::slug($this->label, '_');
        $baseFieldName = $fieldName;
        $suffix = 1;

        while (
            CustomField::where('school_id', $schoolId)
            ->where('field_name', $fieldName)
            ->when($this->field_id, fn($query) => $query->where('id', '!=', $this->field_id))
            ->exists()
        ) {
            $fieldName = $baseFieldName . '_' . (++$suffix);
        }

        $field = CustomField::updateOrCreate(
            ['id' => $this->field_id],
            [
                'school_id' => $schoolId,
                'field_name' => $fieldName,
                'label' => $this->label,
                'placeholder' => $this->placeholder ?: null,
                'instructions' => $this->instructions ?: null,
                'validation_pattern' => CustomField::normalizePattern($this->validationPattern),
                'field_type' => $this->field_type,
                'status' => $this->status,
            ]
        );

        $field->options()->delete();

        if (in_array($this->field_type, ['select', 'radio', 'checkbox'])) {
            foreach ($this->options as $index => $option) {
                $field->options()->create([
                    'option_label' => $option['option_label'],
                    'option_value' => $option['option_value'] ?: Str::slug($option['option_label'], '_'),
                    'order_no' => $index,
                ]);
            }
        }

        foreach (array_keys($this->entityTypes) as $entityType) {
            CustomFieldEntityConfig::updateOrCreate(
                ['custom_field_id' => $field->id, 'entity_type' => $entityType],
                [
                    'is_enabled' => (bool) ($this->entityEnabled[$entityType] ?? false),
                    'is_required' => (bool) ($this->entityRequired[$entityType] ?? false),
                ]
            );
        }

        session()->flash('success', $this->field_id ? 'Custom field updated successfully.' : 'Custom field added successfully.');

        $this->redirect(url('admin/custom-fields'), navigate: false);
    }

    public function render()
    {
        return view('livewire.admin.custom-fields.custom-field-form');
    }
}
