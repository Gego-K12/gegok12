<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Livewire\CustomFields;

use App\Helpers\CustomFieldHelper;
use Illuminate\Support\Collection;
use Livewire\Component;

class ShowAllCustomFields extends Component
{
    public string $entityType;

    public int $entityId;

    public int $schoolId;

    public Collection $fields;

    public function mount(string $entityType, int $entityId, int $schoolId)
    {
        $this->entityType = $entityType;
        $this->entityId = $entityId;
        $this->schoolId = $schoolId;

        $this->fields = CustomFieldHelper::getFieldsForEntity($entityType, $schoolId);
    }

    public function render()
    {
        return view('livewire.custom-fields.show-all-custom-fields');
    }
}
