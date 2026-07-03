<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Country
 *
 * Model for managing country information.
 *
 * @property int $id
 * @property string $name
 * @property string $short_name
 * @property string $iso_code
 * @property string $tel_prefix
 * @property int $status
 * @property int $order
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property-read Collection|School[] $school
 * @property-read Collection|Userprofile[] $userprofile
 * @property-read Collection|State[] $state
 * @property-read Collection|City[] $city
 *
 * @mixin \Eloquent
 */
class Country extends Model
{
    use HasFactory;

    protected $table = 'countries';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'short_name', 'iso_code', 'tel_prefix', 'status', 'order',
    ];

    /**
     * Get schools in this country.
     *
     * @return HasMany
     */
    public function school()
    {
        return $this->hasMany('App\Models\School', 'country_id', 'id');
    }

    /**
     * Get user profiles from this country.
     *
     * @return HasMany
     */
    public function userprofile()
    {
        return $this->hasMany('App\Models\Userprofile', 'country_id', 'id');
    }

    /**
     * Get states in this country.
     *
     * @return HasMany
     */
    public function state()
    {
        return $this->hasMany('App\Models\State', 'country_id', 'id');
    }

    /**
     * Get cities in this country.
     *
     * @return HasMany
     */
    public function city()
    {
        return $this->hasMany('App\Models\City', 'country_id', 'id');
    }
}
