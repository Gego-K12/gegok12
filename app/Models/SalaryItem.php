<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SalaryItem
 *
 * Model for managing individual salary items.
 *
 * @property int $id
 * @property int $salary_id
 * @property int $template_item_id
 * @property float $amount
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime $deleted_at
 * @property-read Salary $salary
 * @property-read TemplateItem $templateitem
 *
 * @mixin \Eloquent
 */
class SalaryItem extends Model
{
    use HasFactory;
    //
    use SoftDeletes;

    protected $with = ['templateitem'];

    protected $fillable = ['salary_id', 'template_item_id', 'amount'];

    /**
     * Get the salary for this item.
     *
     * @return BelongsTo
     */
    public function salary()
    {
        return $this->belongsTo(Salary::class, 'salary_id');
    }

    /**
     * Get the template item for this salary item.
     *
     * @return BelongsTo
     */
    public function templateitem()
    {
        return $this->belongsTo(TemplateItem::class, 'template_item_id');
    }
}
