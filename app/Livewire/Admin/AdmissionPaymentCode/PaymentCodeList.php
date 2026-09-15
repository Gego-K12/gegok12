<?php

// SPDX-License-Identifier: MIT
// (c) 2026 GegoSoft Technologies and GegoK12 Contributors

namespace App\Livewire\Admin\AdmissionPaymentCode;

use App\Models\Admission;
use App\Models\AdmissionPaymentCode;
use Auth;
use Livewire\Component;
use Livewire\WithPagination;

class PaymentCodeList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';

    public $status = '';

    public $fromDate = '';

    public $toDate = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingFromDate()
    {
        $this->resetPage();
    }

    public function updatingToDate()
    {
        $this->resetPage();
    }

    public function render()
    {
        $schoolId = Auth::user()->school_id;

        $codesQuery = AdmissionPaymentCode::where('school_id', $schoolId)
            ->orderBy('id', 'desc');

        if ($this->search) {
            $codesQuery->where('code', 'like', '%' . $this->search . '%');
        }

        if ($this->status) {
            $codesQuery->where('status', $this->status);
        }

        if ($this->fromDate) {
            $codesQuery->whereDate('created_at', '>=', $this->fromDate);
        }

        if ($this->toDate) {
            $codesQuery->whereDate('created_at', '<=', $this->toDate);
        }

        $codes = $codesQuery->paginate(15);

        $admissionIds = $codes->where('entity_type', 'admission')->pluck('entity_id')->filter()->all();
        $admissionDetails = Admission::with('standard')
            ->whereIn('id', $admissionIds)
            ->get()
            ->mapWithKeys(fn($admission) => [
                $admission->id => ucwords(trim($admission->name . ' ' . $admission->lastname))
                    . (optional($admission->standard)->name ? ' - ' . strtoupper($admission->standard->name) : ''),
            ]);

        return view('livewire.admin.admission-payment-code.payment-code-list', [
            'codes' => $codes,
            'admissionNames' => $admissionDetails,
        ]);
    }
}
