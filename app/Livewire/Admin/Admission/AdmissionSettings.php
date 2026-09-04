<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Livewire\Admin\Admission;

use App\Models\SchoolDetail;
use Auth;
use Carbon\Carbon;
use Livewire\Component;

class AdmissionSettings extends Component
{
    public bool $admissionOpen = false;

    public string $closeMessage = '';

    public ?string $closeOn = null;

    public string $schoolSlug = '';

    public $feeAmount = '';

    public function mount()
    {
        $schoolId = Auth::user()->school_id;

        $this->schoolSlug = Auth::user()->school->slug;

        $this->admissionOpen = (bool) $this->metaValue($schoolId, 'admission_open');
        $this->closeMessage = (string) ($this->metaValue($schoolId, 'admission_close_message') ?: '');

        $closeOn = $this->metaValue($schoolId, 'admission_close_on');
        $this->closeOn = $this->toDatetimeLocal($closeOn);

        $this->feeAmount = (string) ($this->metaValue($schoolId, 'admission_fee_amount') ?: '');
    }

    protected function toDatetimeLocal(?string $value): ?string
    {
        if (! $value || $value === '-') {
            return null;
        }

        try {
            return Carbon::parse($value)->format('Y-m-d\TH:i');
        } catch (\Exception $e) {
            return null;
        }
    }

    protected function metaValue($schoolId, $key)
    {
        return SchoolDetail::where('school_id', $schoolId)->where('meta_key', $key)->value('meta_value');
    }

    protected function rules()
    {
        return [
            'admissionOpen' => 'boolean',
            'closeMessage' => 'nullable|string|max:1000',
            'closeOn' => 'nullable|date',
            'feeAmount' => 'nullable|numeric|min:0',
        ];
    }

    public function save()
    {
        $this->validate();

        $schoolId = Auth::user()->school_id;

        SchoolDetail::updateOrCreate(
            ['school_id' => $schoolId, 'meta_key' => 'admission_open'],
            ['meta_value' => $this->admissionOpen ? 1 : 0]
        );

        SchoolDetail::updateOrCreate(
            ['school_id' => $schoolId, 'meta_key' => 'admission_close_message'],
            ['meta_value' => $this->closeMessage]
        );

        SchoolDetail::updateOrCreate(
            ['school_id' => $schoolId, 'meta_key' => 'admission_close_on'],
            ['meta_value' => $this->closeOn ? Carbon::parse($this->closeOn)->format('Y-m-d H:i:s') : null]
        );

        SchoolDetail::updateOrCreate(
            ['school_id' => $schoolId, 'meta_key' => 'admission_fee_amount'],
            ['meta_value' => $this->feeAmount !== '' ? $this->feeAmount : null]
        );

        session()->flash('admission-settings-success', 'Admission settings updated successfully.');
    }

    public function render()
    {
        return view('livewire.admin.admission.admission-settings');
    }
}
