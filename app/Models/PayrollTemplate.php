<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PayrollTemplate
 *
 * Model for managing payroll templates.
 *
 * @property int $id
 * @property int $school_id
 * @property string $name
 * @property int $status
 * @property int $created_by
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime $deleted_at
 * @property-read User $user
 * @property-read Collection|TemplateItem[] $payrollitems
 * @property-read Collection|Salary[] $salaries
 *
 * @mixin \Eloquent
 */
class PayrollTemplate extends Model
{
    use HasFactory;
    //
    use SoftDeletes;

    protected $fillable = ['school_id', 'name', 'status', 'created_by'];

    /**
     * Get the user who created this template.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the payroll items in this template.
     *
     * @return HasMany
     */
    public function payrollitems()
    {
        return $this->hasMany(TemplateItem::class, 'template_id', 'id');
    }

    /**
     * Get salaries using this template.
     *
     * @return HasMany
     */
    public function salaries()
    {
        return $this->hasMany(Salary::class, 'template_id', 'id');
    }
}
