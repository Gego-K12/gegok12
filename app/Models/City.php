<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class City
 *
 * Model for managing city information.
 *
 * @property int $id
 * @property int $country_id
 * @property int $state_id
 * @property string $name
 * @property int $status
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property-read Country $country
 * @property-read State $state
 * @property-read Collection|Userprofile[] $userprofile
 * @property-read Collection|School[] $school
 *
 * @mixin \Eloquent
 */
class City extends Model
{
    use HasFactory;

    protected $table = 'cities';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'country_id', 'state_id', 'name', 'status',
    ];

    /**
     * Get the country for this city.
     *
     * @return BelongsTo
     */
    public function country()
    {
        return $this->belongsTo('App\Models\Country', 'country_id');
    }

    /**
     * Get the state for this city.
     *
     * @return BelongsTo
     */
    public function state()
    {
        return $this->belongsTo('App\Models\State', 'state_id');
    }

    /**
     * Get user profiles for this city.
     *
     * @return HasMany
     */
    public function userprofile()
    {
        return $this->hasMany('App\Models\Userprofile', 'city_id', 'id');
    }

    /**
     * Get schools in this city.
     *
     * @return HasMany
     */
    public function school()
    {
        return $this->hasMany('App\Models\School', 'city_id', 'id');
    }
}
