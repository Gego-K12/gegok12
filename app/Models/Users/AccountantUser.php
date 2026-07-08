<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Models\Users;

use App\Models\Payroll;
use App\Models\PayrollTransaction;
use App\Models\TransactionAccount;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class AccountantUser
 *
 * Specialized User model for accountant-specific functionality.
 * Extends the base User model with payroll and finance management features.
 *
 * @property-read TransactionAccount $bankdetail
 * @property-read Collection|Payroll[] $payrolls
 * @property-read Collection|PayrollTransaction[] $payrolltransactions
 *
 * @mixin \Eloquent
 */
class AccountantUser extends User
{
    const PAYROLL_TYPE_SALARY_ADVANCE = 2;

    const PAYROLL_TYPE_RETURN_ADVANCE = 3;

    /**
     * Get salary details for staff member.
     * Calculates salary advance and pending amounts.
     *
     * @return array|int
     */
    public function salarydetail()
    {
        $array = [];
        if (count($this->payrolltransactions) != 0) {
            $array['salaryadvance'] = $this->payrolltransactions()
                ->where('paytype_id', self::PAYROLL_TYPE_SALARY_ADVANCE)
                ->sum('amount');
            $array['returnadvance'] = $this->payrolltransactions()
                ->where('paytype_id', self::PAYROLL_TYPE_RETURN_ADVANCE)
                ->sum('amount');
            $array['pending'] = $array['salaryadvance'] - $array['returnadvance'];

            return $array;
        }

        return 0;
    }

    /**
     * Get all payroll records for school staff.
     *
     * @return Collection
     */
    public function getSchoolPayroll()
    {
        return Payroll::where('school_id', $this->school_id)
            ->with('user')
            ->get();
    }

    /**
     * Get total salary paid in month.
     *
     * @param  int  $month
     * @param  int  $year
     * @return float
     */
    public function getTotalSalaryPaid($month, $year)
    {
        return PayrollTransaction::where('school_id', $this->school_id)
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->sum('amount');
    }

    /**
     * Get payroll summary for accounting report.
     *
     * @return array
     */
    public function getPayrollSummary()
    {
        return [
            'total_staff' => User::where('school_id', $this->school_id)
                ->whereIn('usergroup_id', [
                    User::TEACHER_USERGROUP_ID,
                    User::LIBRARIAN_USERGROUP_ID,
                    User::RECEPTIONIST_USERGROUP_ID,
                    User::STOCK_KEEPER_USERGROUP_ID,
                    User::NON_TEACHING_USERGROUP_ID,
                ])
                ->count(),
            'total_payroll_records' => $this->payrolls()->count(),
            'total_advances' => $this->payrolltransactions()
                ->where('paytype_id', self::PAYROLL_TYPE_SALARY_ADVANCE)
                ->sum('amount'),
            'total_advances_returned' => $this->payrolltransactions()
                ->where('paytype_id', self::PAYROLL_TYPE_RETURN_ADVANCE)
                ->sum('amount'),
        ];
    }
}
