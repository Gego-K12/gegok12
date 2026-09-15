<?php

// SPDX-License-Identifier: MIT
// (c) 2026 GegoSoft Technologies and GegoK12 Contributors

namespace App\Livewire\Admin\Admission;

use App\Helpers\SiteHelper;
use App\Models\Admission;
use App\Models\AdmissionPaymentCode;
use App\Models\Standard;
use App\Traits\Common;
use App\Traits\LogActivity;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Admission list page -- ports resources/assets/js/components/admission/List.vue.
 */
class AdmissionList extends Component
{
    use Common;
    use LogActivity;
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public string $fromDate = '';

    public string $toDate = '';

    public string $statusFilter = '';

    public string $modeFilter = '';

    public string $classFilter = '';

    public string $applicationStatusFilter = '';

    public function updatingFromDate(): void
    {
        $this->resetPage();
    }

    public function updatingToDate(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatingModeFilter(): void
    {
        $this->resetPage();
    }

    public function updatingClassFilter(): void
    {
        $this->resetPage();
    }

    public function updatingApplicationStatusFilter(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset('fromDate', 'toDate', 'statusFilter', 'modeFilter', 'classFilter', 'applicationStatusFilter');
        $this->resetPage();
    }

    public ?int $applyCodeAdmissionId = null;

    public string $applyCode = '';

    public ?string $applyCodeError = null;

    public function openApplyCodeModal(int $id): void
    {
        $this->applyCodeAdmissionId = $id;
        $this->applyCode = '';
        $this->applyCodeError = null;
    }

    public function closeApplyCodeModal(): void
    {
        $this->reset('applyCodeAdmissionId', 'applyCode', 'applyCodeError');
    }

    public function applyPaymentCode(): void
    {
        $this->validate(['applyCode' => ['required', 'string', 'max:50']], [], ['applyCode' => 'Payment Code']);

        $schoolId = Auth::user()->school_id;

        $code = AdmissionPaymentCode::where('school_id', $schoolId)
            ->where('code', strtoupper($this->applyCode))
            ->where('status', 'unused')
            ->first();

        if (! $code) {
            $this->applyCodeError = 'Invalid or already used payment code';

            return;
        }

        $admission = Admission::where('school_id', $schoolId)->where('id', $this->applyCodeAdmissionId)->firstOrFail();

        $code->update([
            'status' => 'used',
            'entity_type' => 'admission',
            'entity_id' => $admission->id,
            'used_at' => now(),
        ]);

        $admission->payment_mode = 'application_code';
        $admission->application_payment_status = 'paid';
        $admission->amount_paid = $code->amount;
        $admission->save();

        $message = 'Payment code applied successfully. Application marked as Paid.';

        $this->doActivityLog(
            $admission,
            Auth::user(),
            ['ip' => $this->getRequestIP(), 'details' => request()->userAgent()],
            LOGNAME_UPDATE_ADMISSION_FORM,
            $message
        );

        session()->flash('success', $message);

        $this->closeApplyCodeModal();
    }

    public function delete(int $id): void
    {
        $admission = Admission::where('school_id', Auth::user()->school_id)->where('id', $id)->firstOrFail();

        $admission->delete();

        $message = trans('messages.delete_success_msg', ['module' => 'Admission']);

        $this->doActivityLog(
            $admission,
            Auth::user(),
            ['ip' => $this->getRequestIP(), 'details' => request()->userAgent()],
            LOGNAME_DELETE_ADMISSION_FORM,
            $message
        );

        session()->flash('success', $message);
    }

    protected function applyFilters($query)
    {
        if ($this->fromDate) {
            $query->whereDate('created_at', '>=', $this->fromDate);
        }

        if ($this->toDate) {
            $query->whereDate('created_at', '<=', $this->toDate);
        }

        if ($this->statusFilter) {
            $query->where('application_payment_status', $this->statusFilter);
        }

        if ($this->modeFilter) {
            $query->where('payment_mode', $this->modeFilter);
        }

        if ($this->classFilter) {
            $query->where('standard_id', $this->classFilter);
        }

        return $query;
    }

    /**
     * Lists every application status (Draft, Pending, Rejected, Approved); narrowed
     * to one status only when applicationStatusFilter is set.
     */
    protected function baseQuery()
    {
        $query = $this->overviewQuery();

        if ($this->applicationStatusFilter) {
            $query->where('application_status', $this->applicationStatusFilter);
        }

        return $query;
    }

    /**
     * Same scope as baseQuery() but ignores applicationStatusFilter, so the overview
     * stat cards can always report a full breakdown across every application status.
     */
    protected function overviewQuery()
    {
        $schoolId = Auth::user()->school_id;
        $academicYear = SiteHelper::getAcademicYear($schoolId);

        $query = Admission::where('school_id', $schoolId)
            ->where('academic_year_id', $academicYear->id);

        return $this->applyFilters($query);
    }

    public function render()
    {
        $admissions = $this->baseQuery()->orderByDesc('id')->paginate(10);

        $totalApplications = $this->overviewQuery()->count();
        $draftCount = $this->overviewQuery()->where('application_status', 'Draft')->count();
        $approvedCount = $this->overviewQuery()->where('application_status', 'Approved')->count();

        $paidQuery = $this->overviewQuery()->where('application_payment_status', 'paid');

        $applicationCodeTotal = (clone $paidQuery)->where('payment_mode', 'application_code')->sum('amount_paid');
        $razorpayTotal = (clone $paidQuery)->where('payment_mode', 'razorpay')->sum('amount_paid');

        $standards = Standard::where('school_id', Auth::user()->school_id)->active()->orderBy('order')->get();

        return view('livewire.admin.admission.admission-list', [
            'admissions' => $admissions,
            'applicationCodeTotal' => $applicationCodeTotal,
            'razorpayTotal' => $razorpayTotal,
            'totalFees' => $applicationCodeTotal + $razorpayTotal,
            'totalApplications' => $totalApplications,
            'draftCount' => $draftCount,
            'approvedCount' => $approvedCount,
            'standards' => $standards,
        ]);
    }
}
