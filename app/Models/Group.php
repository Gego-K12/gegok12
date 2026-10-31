<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;
    protected $table = 'groups';

    protected $fillable = [
        'standardLink_id' , 'group_name' , 'type' ,'status'
    ];

    public function members()
    {
        return $this->hasMany(GroupMember::class, 'group_id', 'id');
    }
    public function standardlink()
    {
        return $this->belongsTo(StandardLink::class,'standardLink_id');
    }

    public function scopeForAcademy($query, int $academyId)
    {
        return $query->whereHas('standardlink', fn ($link) => $link->where('school_id', $academyId));
    }

}
