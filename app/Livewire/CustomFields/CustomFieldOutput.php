<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Livewire\CustomFields;

use App\Models\CustomField;
use App\Models\CustomFieldValue;
use Livewire\Component;

class CustomFieldOutput extends Component
{
    public CustomField $customField;

    public string $entityType;

    public int $entityId;

    public ?CustomFieldValue $fieldValue = null;

    public array $selectedOptionLabels = [];

    public function mount(int $customFieldId, string $entityType, int $entityId)
    {
        $this->customField = CustomField::with('options')->findOrFail($customFieldId);
        $this->entityType = $entityType;
        $this->entityId = $entityId;

        $this->fieldValue = CustomFieldValue::where('custom_field_id', $customFieldId)
            ->where('entity_type', $entityType)
            ->where('entity_id', $entityId)
            ->first();

        if ($this->fieldValue && in_array($this->customField->field_type, ['select', 'radio', 'checkbox'])) {
            $selectedValues = explode(',', $this->fieldValue->value ?? '');

            $this->selectedOptionLabels = $this->customField->options
                ->whereIn('option_value', $selectedValues)
                ->pluck('option_label')
                ->all();
        }
    }

    public function render()
    {
        return view('livewire.custom-fields.custom-field-output');
    }
}
