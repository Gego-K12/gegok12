<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Livewire\Admin\Student\Profile;

use App\Events\Notification\SingleNotificationEvent;
use App\Events\SinglePushEvent;
use App\Models\User;
use App\Traits\Common;
use App\Traits\LogActivity;
use Gegok12\Fee\Models\Fee;
use Gegok12\Fee\Models\FeePayment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Fees Record tab on the Student Profile page.
 *
 * Ported from the `gegok12/fee` addon's Vue widget + StudentFeesController.
 * The addon's own controller's payment `store()` also attempted an
 * IncomeExpense ledger integration (recording a transaction on payment) --
 * that block referenced an undefined `$partyId` variable (a pre-existing bug
 * in the addon) and is a separate financial-ledger concern in another addon
 * package, so it is intentionally not reproduced here; this component only
 * owns the fee/payment records themselves, matching what this tab displays.
 */
class Fees extends Component
{
    use Common, LogActivity, WithPagination;

    protected $paginationTheme = 'tailwind';

    public string $name;

    public $studentId;

    public $schoolId;

    public bool $showModal = false;

    public ?string $modalType = null;

    public $editingFeeId = null;

    public $editingFeePaymentId = null;

    public $displayFeeGroup = '';

    public $displayTitle = '';

    public $displayTerm = '';

    public $displayStartDate = '';

    public $displayEndDate = '';

    public $displayPaidAmount = '';

    public string $amount = '';

    public string $comments = '';

    public string $paid_on = '';

    public string $payment_type = '';

    public bool $notify_parent = false;

    public function mount(string $name)
    {
        $this->name = $name;

        $student = User::where('name', $name)->first();
        $this->studentId = $student->id;
        $this->schoolId = $student->school_id;
    }

    protected function feePaymentFor($feeId)
    {
        return FeePayment::where([['fee_id', $feeId], ['user_id', $this->studentId]])->first();
    }

    public function openEditAmount(int $feeId): void
    {
        $fee = Fee::find($feeId);
        $feepayment = $this->feePaymentFor($feeId);

        $this->displayFeeGroup = $fee->feeGroup->name;
        $this->displayTitle = $fee->name;
        $this->displayTerm = $fee->term ?? '--';
        $this->displayStartDate = $fee->start_date === null ? '--' : date('d-m-Y', strtotime($fee->start_date));
        $this->displayEndDate = $fee->end_date === null ? '--' : date('d-m-Y', strtotime($fee->end_date));
        $this->amount = (string) ($feepayment?->paid_amount ?? $fee->amount ?? '');
        $this->comments = (string) $feepayment?->comments;

        $this->editingFeeId = $feeId;
        $this->editingFeePaymentId = $feepayment->id;
        $this->modalType = 'edit_amount';
        $this->resetErrorBag();
        $this->showModal = true;
    }

    public function openPaymentDetail(int $feeId, int $feePaymentId): void
    {
        $feepayment = FeePayment::find($feePaymentId);
        $fee = $feepayment->fee;

        $this->displayFeeGroup = $fee->feeGroup->name;
        $this->displayTitle = $fee->name;
        $this->displayTerm = $fee->term ?? '--';
        $this->displayStartDate = $fee->start_date === null ? '--' : date('d-m-Y', strtotime($fee->start_date));
        $this->displayEndDate = $fee->end_date === null ? '--' : date('d-m-Y', strtotime($fee->end_date));
        $this->displayPaidAmount = $feepayment->paid_amount ?? $fee->amount ?? '';

        $this->paid_on = $feepayment->paid_on === null ? '' : date('Y-m-d', strtotime($feepayment->paid_on));
        $this->payment_type = $feepayment->payment_type ?? '';
        $this->notify_parent = (bool) $feepayment->notify_parent;

        $this->editingFeeId = $feeId;
        $this->editingFeePaymentId = $feePaymentId;
        $this->modalType = 'edit_payment';
        $this->resetErrorBag();
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
    }

    protected function rules(): array
    {
        if ($this->modalType === 'edit_payment') {
            return [
                'paid_on' => ['required', 'date', 'before_or_equal:today'],
                'payment_type' => ['required'],
            ];
        }

        return [
            'comments' => ['nullable', 'regex:/^[A-Za-z_~\-!@#\$%\^&*.,:(\)\s]+$/'],
        ];
    }

    protected function messages(): array
    {
        return [
            'payment_type.required' => 'Please select payment',
            'paid_on.required' => 'Paid On is required',
            'paid_on.before_or_equal' => 'Enter Valid Paid On',
            'comments.regex' => 'Enter Valid Comments',
        ];
    }

    public function updateFee(): void
    {
        $this->validate();

        $fee = Fee::find($this->editingFeeId);
        $feepayment = FeePayment::find($this->editingFeePaymentId);

        $feepayment->paid_amount = $this->amount;
        $feepayment->comments = $this->comments;
        $feepayment->paid_on = now();
        $feepayment->status = 1;

        if ($fee->fee_type === 'structural' && $fee->amount != $feepayment->paid_amount) {
            $feepayment->concession_applied = 1;
        }

        $feepayment->save();

        $isConcession = $fee->fee_type === 'structural' && $fee->amount != $feepayment->paid_amount;
        $message = $isConcession
            ? 'Fee Payment Detail Amount Updated Successfully'
            : 'Fee Payment Detail Amount Added Successfully';

        $this->doActivityLog(
            $feepayment,
            Auth::user(),
            ['ip' => $this->getRequestIP(), 'details' => request()->userAgent()],
            $isConcession ? LOGNAME_UPDATE_FEEPAYMENT_AMOUNT : LOGNAME_ADD_FEEPAYMENT_AMOUNT,
            $message
        );

        $this->closeModal();
    }

    public function assignFee(int $feeId): void
    {
        $existing = $this->feePaymentFor($feeId);

        if ($existing) {
            return;
        }

        $feepayment = new FeePayment;
        $feepayment->fee_id = $feeId;
        $feepayment->user_id = $this->studentId;
        $feepayment->status = 0;
        $feepayment->created_by = Auth::id();
        $feepayment->updated_by = Auth::id();
        $feepayment->save();

        $this->doActivityLog(
            $feepayment,
            Auth::user(),
            ['ip' => $this->getRequestIP(), 'details' => request()->userAgent()],
            LOGNAME_ASSIGN_FEEPAYMENT,
            'Fee Payment Detail Assigned Successfully'
        );
    }

    public function resetFee(int $feePaymentId): void
    {
        $feepayment = FeePayment::find($feePaymentId);
        $feepayment->delete();

        $this->doActivityLog(
            $feepayment,
            Auth::user(),
            ['ip' => $this->getRequestIP(), 'details' => request()->userAgent()],
            LOGNAME_RESET_FEEPAYMENT,
            'Fee Payment Detail Reset Successfully'
        );
    }

    public function addPaymentDetail(): void
    {
        $this->validate();

        $student = User::find($this->studentId);
        $feepayment = FeePayment::find($this->editingFeePaymentId);

        $feepayment->fee_id = $this->editingFeeId;
        $feepayment->user_id = $this->studentId;
        $feepayment->paid_on = $this->paid_on;
        $feepayment->status = 1;
        $feepayment->created_by = Auth::id();
        $feepayment->updated_by = Auth::id();
        $feepayment->payment_type = $this->payment_type;
        $feepayment->notify_parent = $this->notify_parent ? 1 : 0;
        $feepayment->save();

        if ($this->notify_parent) {
            foreach ($student->parents as $parent) {
                event(new SinglePushEvent([
                    'school_id' => $this->schoolId,
                    'user_id' => $parent->userParent->id,
                    'message' => 'Fee Payment Detail Added',
                    'type' => 'fee details',
                ]));
            }

            event(new SingleNotificationEvent([
                'user' => $student,
                'details' => trans('notification.fee_add_success_msg'),
            ]));
        }

        $this->doActivityLog(
            $feepayment,
            Auth::user(),
            ['ip' => $this->getRequestIP(), 'details' => request()->userAgent()],
            LOGNAME_ADD_FEEPAYMENTDETAIL,
            trans('messages.add_success_msg', ['module' => 'Fee Payment Detail'])
        );

        $this->closeModal();
    }

    public function render()
    {
        $studentAcademic = User::find($this->studentId)->studentAcademicLatest;

        $fees = Fee::where('school_id', $this->schoolId)
            ->where('academic_year_id', optional(\App\Helpers\SiteHelper::getAcademicYear($this->schoolId))->id)
            ->where(function ($query) use ($studentAcademic) {
                $query->where('standardLink_id', $studentAcademic?->standardLink_id)
                    ->orWhereNull('standardLink_id');
            })
            ->orderByDesc('start_date')
            ->paginate(5)
            ->through(function ($fee) {
                $feepayment = $this->feePaymentFor($fee->id);

                return [
                    'id' => $fee->id,
                    'name' => $fee->name,
                    'term' => $fee->term ?? '--',
                    'amount' => $feepayment?->paid_amount ?? $fee->amount,
                    'fee_type' => $fee->feeGroup->name,
                    'paid_on' => $feepayment?->paid_on === null ? '--' : date('d M Y', strtotime($feepayment->paid_on)),
                    'payment_type' => $feepayment?->payment_type ?? '',
                    'notify_parent' => $feepayment?->notify_parent ? 'Yes' : '--',
                    'feePayment_id' => $feepayment?->id,
                    'status' => $feepayment?->status,
                    'concession_applied' => $feepayment?->concession_applied,
                    'bg_class' => match (true) {
                        $feepayment === null => 'bg-gray-300',
                        (int) $feepayment->status === 0 => 'bg-red-300',
                        default => 'bg-green-300',
                    },
                ];
            });

        return view('livewire.admin.student.profile.fees', ['fees' => $fees]);
    }
}
