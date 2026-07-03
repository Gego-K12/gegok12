<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Models;

use App\Traits\Common;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Events
 *
 * Model for managing school events and activities.
 *
 * @property int $id
 * @property int $school_id
 * @property int $academic_year_id
 * @property int $standard_id
 * @property string $select_type
 * @property string $title
 * @property string $description
 * @property string $repeats
 * @property string $freq
 * @property string $freq_term
 * @property string $location
 * @property string $category
 * @property string $organised_by
 * @property string $image
 * @property \DateTime $start_date
 * @property \DateTime $end_date
 * @property int $allDay
 * @property string $url
 * @property int $created_by
 * @property int $updated_by
 * @property int $status
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime $deleted_at
 * @property string $image_path
 * @property-read School $school
 * @property-read AcademicYear $academicYear
 * @property-read StandardLink $standardlink
 * @property-read User $createdBy
 * @property-read User $updatedBy
 * @property-read Collection|Notes[] $notes
 * @property-read Collection|Reminder[] $eventreminder
 * @property-read Collection|EventGallery[] $eventgallery
 *
 * @mixin \Eloquent
 */
class Events extends Model
{
    use Common;
    use SoftDeletes;

    protected $table = 'events';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_id', 'academic_year_id', 'standard_id', 'select_type', 'title', 'description', 'repeats', 'freq', 'freq_term', 'location', 'category', 'organised_by', 'image', 'start_date', 'end_date', 'allDay', 'url', 'created_by', 'updated_by', 'status',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    // protected $dates = ['start_date' ,  'end_date' , 'deleted_at'];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the school for this event.
     *
     * @return BelongsTo
     */
    public function school()
    {
        return $this->belongsTo('App\Models\School', 'school_id');
    }

    /**
     * Get the academic year for this event.
     *
     * @return BelongsTo
     */
    public function academicYear()
    {
        return $this->belongsTo('\App\Models\AcademicYear', 'academic_year_id');
    }

    /**
     * Get the standard link for this event.
     *
     * @return BelongsTo
     */
    public function standardlink()
    {
        return $this->belongsTo('App\Models\StandardLink', 'standard_id');
    }

    /**
     * Get the user who created this event.
     *
     * @return BelongsTo
     */
    public function createdBy()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    /**
     * Get the user who last updated this event.
     *
     * @return BelongsTo
     */
    public function updatedBy()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }

    /**
     * Get notes related to this event.
     *
     * @return HasMany
     */
    public function notes()
    {
        return $this->hasMany('App\Models\Notes', 'entity_id', 'id');
    }

    /**
     * Scope to filter events by church.
     *
     * @param  Builder  $query
     * @param  int  $church_id
     * @return Builder
     */
    public function scopeByChurch($query, $church_id)
    {
        $query->where('church_id', $church_id);

        return $query;
    }

    /**
     * Get event reminders.
     *
     * @return HasMany
     */
    public function eventreminder()
    {
        return $this->hasMany('App\Models\Reminder', 'entity_id', 'id')->where('entity_name', '=', 'App\\Models\\Events');
    }

    /**
     * Get event gallery images.
     *
     * @return HasMany
     */
    public function eventgallery()
    {
        return $this->hasMany('App\Models\EventGallery', 'event_id', 'id');
    }

    /**
     * Get the image path for this event.
     *
     * @return string|null
     */
    public function getImagePathAttribute()
    {
        if ($this->image == null) {
            return $this->eventImagePath($this->category, $this->image);
        }
    }

    /**
     * Get count of photos in this event gallery.
     *
     * @param  int  $id
     * @param  int  $school_id
     * @return int
     */
    public function getphotocount($id, $school_id)
    {
        $count = EventGallery::where('school_id', $school_id)->where('event_id', $id)->count();

        return $count;
    }
}
