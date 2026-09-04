<?php

// SPDX-License-Identifier: MIT
// (c) 2026 GegoSoft Technologies and GegoK12 Contributors

namespace App\Livewire\Admin\AdmissionPaymentCode;

use App\Models\AdmissionPaymentCode;
use Auth;
use Illuminate\Support\Str;
use Livewire\Component;

class PaymentCodeForm extends Component
{
    public $quantity = 1;

    public $amount = '';

    protected function rules(): array
    {
        return [
            'quantity' => 'required|integer|min:1|max:500',
            'amount' => 'required|numeric|min:0.01',
        ];
    }

    public function save()
    {
        $this->validate();

        $schoolId = Auth::user()->school_id;
        $userId = Auth::id();

        for ($i = 0; $i < $this->quantity; $i++) {
            do {
                $code = Str::upper(Str::random(10));
            } while (AdmissionPaymentCode::where('code', $code)->exists());

            AdmissionPaymentCode::create([
                'school_id' => $schoolId,
                'code' => $code,
                'amount' => $this->amount,
                'status' => 'unused',
                'created_by' => $userId,
                'updated_by' => $userId,
            ]);
        }

        session()->flash('success', $this->quantity . ' payment code(s) generated successfully.');

        $this->redirect(url('admin/admission-payment-codes'), navigate: false);
    }

    public function render()
    {
        return view('livewire.admin.admission-payment-code.payment-code-form');
    }
}
