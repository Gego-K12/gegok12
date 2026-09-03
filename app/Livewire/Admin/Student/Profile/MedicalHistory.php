<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Livewire\Admin\Student\Profile;

use App\Models\StudentAcademic;
use App\Models\User;
use Livewire\Component;

class MedicalHistory extends Component
{
    public string $name;

    public array $medical = [];

    public function mount(string $name)
    {
        $this->name = $name;

        $student = User::where('name', $name)->first();
        $studentAcademic = StudentAcademic::where('user_id', $student->id)->latest()->first();

        $this->medical = [
            'height' => number_format($studentAcademic->height ?? 0, 2),
            'weight' => number_format($studentAcademic->weight ?? 0, 2),
            'medication_problems' => $studentAcademic->medication_problems === 'null' ? null : $studentAcademic->medication_problems,
            'medication_needs' => $studentAcademic->medication_needs === 'null' ? null : $studentAcademic->medication_needs,
            'medication_allergies' => $studentAcademic->medication_allergies === 'null' ? null : $studentAcademic->medication_allergies,
            'food_allergies' => $studentAcademic->food_allergies === 'null' ? null : $studentAcademic->food_allergies,
            'other_allergies' => $studentAcademic->other_allergies === 'null' ? null : $studentAcademic->other_allergies,
            'other_medical_information' => $studentAcademic->other_medical_information === 'null' ? null : $studentAcademic->other_medical_information,
        ];
    }

    public function render()
    {
        return view('livewire.admin.student.profile.medical-history');
    }
}
