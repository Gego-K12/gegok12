<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Models;

use App\Models\Users\TeacherUser;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Salary
 *
 * Model for managing employee salary records.
 *
 * @property int $id
 * @property int $school_id
 * @property int $staff_id
 * @property int $template_id
 * @property \DateTime $effective_date
 * @property string $comments
 * @property float $gross_salary
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime $deleted_at
 * @property float $totalearnings
 * @property float $totaldeductions
 * @property float $totalsalary
 * @property-read PayrollTemplate $payrolltemplate
 * @property-read User $user
 * @property-read Collection|SalaryItem[] $salaryitems
 * @property-read Collection|Payroll[] $payrolls
 *
 * @mixin \Eloquent
 */
class Salary extends Model
{
    use HasFactory;
    //
    use SoftDeletes;

    protected $fillable = ['school_id', 'staff_id', '', 'effective_date', 'comments'];

    /**
     * Get the payroll template for this salary.
     *
     * @return BelongsTo
     */
    public function payrolltemplate()
    {
        return $this->belongsTo(PayrollTemplate::class, 'template_id');
    }

    /**
     * Get the user (staff) for this salary.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(TeacherUser::class, 'staff_id');
    }

    /**
     * Get salary items for this salary.
     *
     * @return HasMany
     */
    public function salaryitems()
    {
        return $this->hasMany(SalaryItem::class, 'salary_id', 'id');
    }

    /**
     * Get payrolls for this salary.
     *
     * @return HasMany
     */
    public function payrolls()
    {
        return $this->hasMany(Payroll::class, 'salary_id');
    }

    /**
     * Calculate total earnings for this salary.
     *
     * @return float
     */
    public function totalearnings()
    {
        if (count($this->salaryitems) != 0) {
            /*$earning=$this->salaryitems()->whereHas('payrollitem', function($query) {
                  $query->where('type', 'earning');
                })->sum('amount');*/
            $earning = $this->salaryitems()->whereHas('templateitem', function ($query) {
                $query->whereHas('payrollitem', function ($query) {
                    $query->where('type', 'earning');
                });
            })->sum('amount');

            return $earning;
        }

        return 0;
    }

    /**
     * Calculate total deductions for this salary.
     *
     * @return float
     */
    public function totaldeductions()
    {

        if (count($this->salaryitems) != 0) {
            /*          $deduction=$this->salaryitems()->whereHas('payrollitem', function($query) {
                  $query->where('type', 'deduction');
                })->sum('amount');
*/
            $deduction = $this->salaryitems()->whereHas('templateitem', function ($query) {
                $query->whereHas('payrollitem', function ($query) {
                    $query->where('type', 'deduction');
                });
            })->sum('amount');

            return $deduction;
        }

        return 0;
    }

    /**
     * Calculate total salary for this record.
     *
     * @return float
     */
    public function totalsalary()
    {

        if (count($this->salaryitems) != 0) {
            // $total=$this->totalearnings()-$this->totaldeductions();
            $total = ($this->gross_salary + $this->totalearnings()) - $this->totaldeductions();

            return round($total);
        }

        return 0;
    }
}
