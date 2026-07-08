<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PayslipItem
 *
 * Model for managing payslip line items.
 *
 * @property int $id
 * @property int $payroll_id
 * @property int $salary_item_id
 * @property float $amount
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime $deleted_at
 * @property-read Payroll $payroll
 * @property-read SalaryItem $salaryitem
 *
 * @mixin \Eloquent
 */
class PayslipItem extends Model
{
    //
    use SoftDeletes;

    protected $with = ['salaryitem'];

    protected $fillable = ['payroll_id', 'salary_item_id', 'amount'];

    /**
     * Get the payroll for this payslip item.
     *
     * @return BelongsTo
     */
    public function payroll()
    {
        return $this->belongsTo(Payroll::class, 'payroll_id');
    }

    /**
     * Get the salary item for this payslip item.
     *
     * @return BelongsTo
     */
    public function salaryitem()
    {
        return $this->belongsTo(SalaryItem::class, 'salary_item_id');
    }
}
