<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Models;

use App\Traits\Common;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class NoticeBoard
 *
 * Model for managing notice board postings.
 *
 * @property int $id
 * @property int $school_id
 * @property int $academic_year_id
 * @property int $standardLink_id
 * @property string $type
 * @property string $title
 * @property \DateTime $publish_date
 * @property \DateTime $expire_date
 * @property string $description
 * @property string $attachment_file
 * @property int $status
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime $deleted_at
 * @property string $attachment_path
 * @property-read School $school
 * @property-read BackgroundImage $backgroundimage
 * @property-read AcademicYear $academicYear
 * @property-read StandardLink $standardLink
 *
 * @mixin \Eloquent
 */
class NoticeBoard extends Model
{
    use Common;
    use HasFactory;
    use SoftDeletes;

    protected $table = 'notice_board';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_id', 'academic_year_id', 'standardLink_id', 'type', 'title', 'publish_date', 'expire_date', 'description', 'attachment_file', 'status',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    // protected $dates = ['publish_date' , 'expire_date'];

    protected $casts = [
        'publish_date' => 'datetime',
        'expire_date' => 'datetime',
    ];

    /**
     * Get the school for this notice.
     *
     * @return BelongsTo
     */
    public function school()
    {
        return $this->belongsTo('App\Models\School', 'school_id');
    }

    /**
     * Get the background image for this notice.
     *
     * @return BelongsTo
     */
    public function backgroundimage()
    {
        return $this->belongsTo('App\Models\BackgroundImage', 'background_id');
    }

    /**
     * Get the academic year for this notice.
     *
     * @return BelongsTo
     */
    public function academicYear()
    {
        return $this->belongsTo('\App\Models\AcademicYear', 'academic_year_id');
    }

    /**
     * Get the standard link for this notice.
     *
     * @return BelongsTo
     */
    public function standardLink()
    {
        return $this->belongsTo('\App\Models\StandardLink', 'standardLink_id');
    }

    /**
     * Get the full file path for the attachment.
     *
     * @return string
     */
    public function getAttachmentPathAttribute()
    {
        return $this->getFilePath($this->attachment_file);
    }
}
