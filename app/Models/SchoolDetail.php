<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Models;

use App\Traits\Common;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class SchoolDetail
 *
 * Model for managing school metadata (logo, board, etc).
 *
 * @property int $id
 * @property int $school_id
 * @property string $meta_key
 * @property string $meta_value
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property string $logoPath
 * @property-read School $school
 *
 * @mixin \Eloquent
 */
class SchoolDetail extends Model
{
    use Common;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'school_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_id', 'meta_key', 'meta_value',
    ];

    /**
     * Get the school for this detail.
     *
     * @return BelongsTo
     */
    public function school()
    {
        return $this->belongsTo('App\Models\School', 'school_id');
    }

    /**
     * Get the logo file path if this is a logo meta key.
     *
     * @return string
     */
    public function getLogoPathAttribute()
    {
        if ($this->meta_key == 'school_logo') {
            return $this->getFilePath($this->meta_value);
        }
    }
}
