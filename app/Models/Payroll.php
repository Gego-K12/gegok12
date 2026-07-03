<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Models;

use App\Models\Users\TeacherUser;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Payroll
 *
 * Model for managing staff payroll records.
 *
 * @property int $id
 * @property int $school_id
 * @property string $payrollno
 * @property int $staff_id
 * @property int $salary_id
 * @property \DateTime $start_date
 * @property \DateTime $end_date
 * @property int $status
 * @property string $comments
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime $deleted_at
 * @property-read User $user
 * @property-read Salary $salary
 * @property-read Collection|PayslipItem[] $payslipitems
 * @property-read PayrollTransaction $transaction
 *
 * @mixin \Eloquent
 */
class Payroll extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['school_id', 'payrollno', 'staff_id', 'salary_id', 'start_date', 'end_date', 'status', 'comments'];

    /**
     * Get the staff user associated with this payroll.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(TeacherUser::class, 'staff_id');
    }

    /**
     * Get the salary associated with this payroll.
     *
     * @return BelongsTo
     */
    public function salary()
    {
        return $this->belongsTo(Salary::class, 'salary_id');
    }

    /**
     * Get the payslip items for this payroll.
     *
     * @return HasMany
     */
    public function payslipitems()
    {
        return $this->hasMany(PayslipItem::class, 'payroll_id', 'id');
    }

    /**
     * Get the transaction record for this payroll.
     *
     * @return HasOne
     */
    public function transaction()
    {
        return $this->hasOne(PayrollTransaction::class, 'payroll_id');
    }

    public function totalearnings()
    {
        if (count($this->payslipitems) != 0) {

            $earning = $this->payslipitems()->whereHas('salaryitem', function ($query) {
                $query->whereHas('templateitem', function ($query) {
                    $query->whereHas('payrollitem', function ($query) {
                        $query->where('type', 'earning');
                    });
                });
            })->sum('amount');

            return round($earning);
        }

        return 0;
    }

    public function totaldeductions()
    {

        if (count($this->payslipitems) != 0) {

            $deduction = $this->payslipitems()->whereHas('salaryitem', function ($query) {
                $query->whereHas('templateitem', function ($query) {
                    $query->whereHas('payrollitem', function ($query) {
                        $query->where('type', 'deduction');
                    });
                });
            })->sum('amount');

            return round($deduction);
        }

        return 0;

    }

    public function salarypercentage()
    {
        if (count($this->payslipitems) != 0) {
            $totalsalary = $this->salary->gross_salary - $this->leave_deduction;
            $netsalary = round(($totalsalary * $this->percentage) / 100);

            return $netsalary;
        }

        return 0;

    }

    public function totalsalary()
    {

        if (count($this->payslipitems) != 0) {
            $totalsalary = $this->salary->gross_salary - $this->leave_deduction;
            $netsalary = ($totalsalary * $this->percentage) / 100;
            $earnings = $netsalary + $this->totalearnings();
            $deductions = $this->totaldeductions();

            // $earnings=$this->salary->gross_salary+$this->totalearnings();
            // $deductions=$this->leave_deduction+$this->totaldeductions();
            $total = $earnings - $deductions;

            return round($total);
        }

        return 0;

    }

    public function scopeByDate($query, $start_date, $end_date)
    {
        $query->where(function ($q) use ($start_date, $end_date) {
            $q->where('start_date', '>=', $start_date)
                ->where('start_date', '<', $end_date);

        })->orWhere(function ($q) use ($start_date, $end_date) {
            $q->where('start_date', '<=', $start_date)
                ->where('end_date', '>', $end_date);

        })->orWhere(function ($q) use ($start_date, $end_date) {
            $q->where('end_date', '>', $start_date)
                ->where('end_date', '<=', $end_date);

        })->orWhere(function ($q) use ($start_date, $end_date) {
            $q->where('start_date', '>=', $start_date)
                ->where('end_date', '<=', $end_date);

        });

        return $query;
    }

    public function getLeaveDays($id, $start_date, $end_date)
    {

        /*  $leave=TeacherLeaveApplication::where('user_id',$id)->where('from_date','>=',$start_date)->where('to_date','<=',$end_date)->first(); */
        $days = 0;
        $leave = TeacherLeaveApplication::where('user_id', $id)->where([['from_date', '>=', $start_date], ['status', 'approved']])->first();

        $from_date = date('Y-m-d', strtotime($leave->from_date));
        $to_date = date('Y-m-d', strtotime($leave->to_date));
        if ($leave) {
            if ($to_date >= $end_date) {
                $days = Carbon::parse($from_date)->diffInDays(Carbon::parse($end_date)) + 1;
            } else {
                $days = Carbon::parse($from_date)->diffInDays(Carbon::parse($to_date)) + 1;
            }
        }

        return $days;

    }

    public function getTotalDays()
    {

        $from_date = date('Y-m-d H:i:s', strtotime($this->start_date));
        $to_date = date('Y-m-d H:i:s', strtotime($this->end_date));

        $days = Carbon::parse($from_date)->diffInDays(Carbon::parse($to_date));

        return $days + 1;

    }

    public function getDaySalary()
    {

        return round($this->salary->gross_salary / $this->getTotalDays());

    }
}
