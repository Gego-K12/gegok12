<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Livewire\Admin\Admission;

use App\Helpers\CustomFieldHelper;
use App\Models\Admission;
use App\Models\Qualification;
use App\Models\Section;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

/**
 * Read-only "view details" page for a single admission application --
 * mirrors the 6-step public AdmissionForm wizard's data, decoding the
 * JSON/CSV-encoded columns (half_yearly_mark_details, medical_details,
 * activities, transport_details, custom_fields) the same way
 * AdmissionForm::submit() writes them.
 */
class AdmissionDetails extends Component
{
    public Admission $admission;

    public array $marks = [];

    public array $medicalDetails = [];

    public array $activities = [];

    public ?array $transportDetails = null;

    public array $customFieldValues = [];

    public ?string $fatherQualification = null;

    public ?string $motherQualification = null;

    public ?string $sectionName = null;

    public ?string $feeGroupName = null;

    public function mount(int $admissionId)
    {
        $this->admission = Admission::findOrFail($admissionId);

        abort_unless($this->admission->school_id === Auth::user()->school_id, 403);

        $this->marks = json_decode($this->admission->half_yearly_mark_details ?? '', true) ?? [];
        $this->medicalDetails = $this->admission->medical_details !== null && $this->admission->medical_details !== ''
            ? explode(',', $this->admission->medical_details)
            : [];
        $this->activities = $this->admission->activities !== null && $this->admission->activities !== ''
            ? explode(',', $this->admission->activities)
            : [];
        $this->transportDetails = json_decode($this->admission->transport_details ?? '', true) ?: null;

        $this->fatherQualification = Qualification::find($this->admission->father_qualification_id)?->display_name;
        $this->motherQualification = Qualification::find($this->admission->mother_qualification_id)?->display_name;

        if ($this->admission->section_id) {
            $this->sectionName = Section::find($this->admission->section_id)?->name;
        }

        if ($this->admission->fee_group_id && class_exists('Gegok12\Fee\Models\FeeGroup')) {
            $this->feeGroupName = \Gegok12\Fee\Models\FeeGroup::find($this->admission->fee_group_id)?->name;
        }

        $customFieldRaw = json_decode($this->admission->custom_fields ?? '', true) ?? [];
        $fields = CustomFieldHelper::getFieldsForEntity('admission', $this->admission->school_id);

        // Show every field currently configured for the school's Admission
        // form, not just the ones present in this record's stored JSON --
        // older applications submitted before a field existed (or before
        // the Additional Info step existed at all) would otherwise make the
        // whole section vanish instead of showing it blank.
        foreach ($fields as $field) {
            $this->customFieldValues[] = [
                'label' => $field->label,
                'value' => $customFieldRaw[$field->id] ?? null,
                'is_file' => $field->field_type === 'file',
            ];
        }
    }

    public function render()
    {
        return view('livewire.admin.admission.admission-details');
    }
}
