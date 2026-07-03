<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Models;

use App\Traits\Common;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class StudentCertificate
 *
 * Model for managing student certificates and achievements.
 *
 * @property int $id
 * @property int $school_id
 * @property int $student_id
 * @property string $program_name
 * @property string $event_name
 * @property string $certificate_for
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime $deleted_at
 *
 * @mixin \Eloquent
 */
class StudentCertificate extends Model
{
    use Common;
    use SoftDeletes;

    protected $table = 'student_certificate';

    protected $fillable = [

        'school_id', 'student_id', 'program_name', 'event_name', 'certificate_for',
    ];
}
