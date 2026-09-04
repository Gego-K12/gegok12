<?php

// SPDX-License-Identifier: MIT
// (c) 2026 GegoSoft Technologies and GegoK12 Contributors

namespace App\Models;

use App\Traits\Common;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class AdmissionPaymentCode
 *
 * A prepaid code an applicant can redeem on the public Admission form.
 *
 * @property int $id
 * @property int $school_id
 * @property string $code
 * @property float $amount
 * @property string $status
 * @property string|null $entity_type
 * @property int|null $entity_id
 * @property \DateTime|null $used_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @mixin \Eloquent
 */
class AdmissionPaymentCode extends Model
{
    use Common;

    protected $fillable = [
        'school_id',
        'code',
        'amount',
        'status',
        'entity_type',
        'entity_id',
        'used_at',
        'created_by',
        'updated_by',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class, 'school_id');
    }
}
