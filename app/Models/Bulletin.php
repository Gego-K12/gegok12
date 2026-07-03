<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Models;

use App\Traits\Common;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Bulletin
 *
 * Model for managing school bulletins and magazines.
 *
 * @property int $id
 * @property int $school_id
 * @property int $academic_year_id
 * @property string $name
 * @property int $year
 * @property string $bulletin_file
 * @property string $cover_image
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime $deleted_at
 * @property string $file_path
 * @property string $image_path
 * @property-read School $school
 * @property-read AcademicYear $academicYear
 *
 * @mixin \Eloquent
 */
class Bulletin extends Model
{
    use Common;
    use SoftDeletes;

    protected $table = 'magazines';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_id', 'academic_year_id', 'name', 'year', 'bulletin_file',
    ];

    /**
     * Get the school for this bulletin.
     *
     * @return BelongsTo
     */
    public function school()
    {
        return $this->belongsTo('App\Models\School', 'school_id');
    }

    /**
     * Get the academic year for this bulletin.
     *
     * @return BelongsTo
     */
    public function academicYear()
    {
        return $this->belongsTo('\App\Models\AcademicYear', 'academic_year_id');
    }

    /**
     * Get the full file path for the bulletin document.
     *
     * @return string
     */
    public function getFilePathAttribute()
    {
        return $this->getFilePath($this->bulletin_file);
    }

    /**
     * Get the full file path for the bulletin cover image.
     *
     * @return string
     */
    public function getImagePathAttribute()
    {
        return $this->getFilePath($this->cover_image);
    }
}
