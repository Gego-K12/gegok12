<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TemplateItem
 *
 * Model for managing items within payroll templates.
 *
 * @property int $id
 * @property int $template_id
 * @property int $item_id
 * @property int $paycategory_id
 * @property decimal $category_value
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime $deleted_at
 * @property-read PayrollTemplate $payrolltemplate
 * @property-read PayrollItem $payrollitem
 * @property-read PayCategory $paycategory
 *
 * @mixin \Eloquent
 */
class TemplateItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $with = ['payrollitem', 'paycategory'];

    protected $fillable = ['template_id', 'item_id', 'paycategory_id', 'category_value'];

    /**
     * Get the payroll template for this item.
     *
     * @return BelongsTo
     */
    public function payrolltemplate()
    {
        return $this->belongsTo(PayrollTemplate::class, 'template_id');
    }

    /**
     * Get the payroll item for this template item.
     *
     * @return BelongsTo
     */
    public function payrollitem()
    {
        return $this->belongsTo(PayrollItem::class, 'item_id');
    }

    /**
     * Get the pay category for this template item.
     *
     * @return BelongsTo
     */
    public function paycategory()
    {
        return $this->belongsTo(PayCategory::class, 'paycategory_id');
    }
}
