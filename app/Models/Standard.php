<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Laracasts\Presenter\PresentableTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Standard
 *
 * Model for managing academic standards/grades.
 *
 * @property int $id
 * @property int $school_id
 * @property string $name
 * @property int $order
 * @property int $status
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime $deleted_at
 * @property-read \App\Models\School $school
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Subject[] $subject
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\StandardLink[] $standardLink
 * @property-read \App\Models\Promotion $currentPromotion
 * @property-read \App\Models\Promotion $nextPromotion
 * @mixin \Eloquent
 */
class Standard extends Model
{
    //
    use SoftDeletes;
    use PresentableTrait;

    protected $presenter = "App\Presenters\UserprofilePresenter";

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'standards';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'school_id' ,  'name' ,'slug' , 'order' , 'status'
    ];

    /**
     * Get the school for this standard.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function school()
    {
        return $this->belongsTo('\App\Models\School','school_id');
    }

    /**
     * Get the current promotion record for this standard.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currentPromotion()
    {
        return $this->belongsTo('App\Models\Promotion','standard_id','id');
    }

    /**
     * Get the next promotion record for this standard.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function nextPromotion()
    {
        return $this->belongsTo('App\Models\Promotion','standard_id','id');
    }

    /**
     * Get subjects for this standard.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subject()
    {
        return $this->hasMany('\App\Models\Subject','school_id','id');
    }

    /**
     * Get standard links (class-section combinations) for this standard.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function standardLink()
    {
        return $this->hasMany('\App\Models\StandardLink','standard_id','id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function getStandardNameAttribute()
    {
        

        $name = strtoupper($this->name);

        if (in_array($name, ['PREKG', 'LKG', 'UKG'])) {
            return $name;
        }

        // Convert only numeric standards to Roman
        if (is_numeric($this->name)) {

            return $this->present()->integerToRoman($this->name);
        }

        // Return any other standard name as it is

        return strtoupper($this->name);
    }
}
