<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Livewire\CustomFields;

use App\Helpers\CustomFieldHelper;
use App\Models\CustomField;
use App\Models\CustomFieldEntityConfig;
use App\Models\CustomFieldValue;
use App\Traits\Common;
use Livewire\Component;
use Livewire\WithFileUploads;

class CustomFieldInput extends Component
{
    use Common;
    use WithFileUploads;

    public CustomField $customField;

    public string $entityType;

    public int $entityId;

    public int $schoolId;

    public ?CustomFieldEntityConfig $entityConfig = null;

    public string $value = '';

    public array $checkboxValues = [];

    public $file = null;

    public ?string $existingFilePath = null;

    public bool $saved = false;

    public function mount(int $customFieldId, string $entityType, int $entityId, int $schoolId)
    {
        $this->customField = CustomField::with('options')->findOrFail($customFieldId);
        $this->entityType = $entityType;
        $this->entityId = $entityId;
        $this->schoolId = $schoolId;

        $this->entityConfig = CustomFieldEntityConfig::where('custom_field_id', $customFieldId)
            ->where('entity_type', $entityType)
            ->first();

        $existing = CustomFieldValue::where('custom_field_id', $customFieldId)
            ->where('entity_type', $entityType)
            ->where('entity_id', $entityId)
            ->first();

        if ($this->customField->field_type === 'checkbox') {
            $this->checkboxValues = $existing && $existing->value ? explode(',', $existing->value) : [];
        } else {
            $this->value = $existing->value ?? '';
        }

        $this->existingFilePath = $existing->file_path ?? null;
    }

    protected function rules(): array
    {
        $rule = $this->entityConfig?->is_required ? 'required' : 'nullable';

        $rules = match ($this->customField->field_type) {
            'email' => ['value' => "{$rule}|email"],
            'number' => ['value' => "{$rule}|numeric"],
            'date' => ['value' => "{$rule}|date"],
            'file' => ['file' => "{$rule}|file|max:5120"],
            'checkbox' => ['checkboxValues' => str_replace('nullable', 'nullable|array', str_replace('required', 'required|array', $rule))],
            default => ['value' => "{$rule}|string|max:1000"],
        };

        if (! in_array($this->customField->field_type, ['file', 'checkbox', 'date'])) {
            $additional = CustomFieldHelper::resolveAdditionalRule($this->customField, $this->entityConfig);

            if ($additional) {
                $rules['value'] .= '|'.$additional;
            }
        }

        return $rules;
    }

    public function updatedValue()
    {
        $this->validateOnly('value');

        CustomFieldHelper::setValue(
            $this->customField->id,
            $this->entityType,
            $this->entityId,
            $this->schoolId,
            $this->value
        );

        $this->flashSaved();
    }

    public function updatedCheckboxValues()
    {
        $this->validateOnly('checkboxValues');

        CustomFieldHelper::setValue(
            $this->customField->id,
            $this->entityType,
            $this->entityId,
            $this->schoolId,
            implode(',', $this->checkboxValues)
        );

        $this->flashSaved();
    }

    public function updatedFile()
    {
        $this->validateOnly('file');

        $path = $this->uploadFile($this->schoolId.'/custom-fields', $this->file);

        CustomFieldHelper::setValue(
            $this->customField->id,
            $this->entityType,
            $this->entityId,
            $this->schoolId,
            null,
            $path
        );

        $this->existingFilePath = $path;
        $this->file = null;
        $this->flashSaved();
    }

    protected function flashSaved()
    {
        $this->saved = true;
        $this->dispatch('custom-field-saved');
    }

    public function render()
    {
        return view('livewire.custom-fields.custom-field-input');
    }
}
