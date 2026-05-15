<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $table = 'groups';

    protected $fillable = [
        'standardLink_id' , 'group_name' , 'type' ,'status'
    ];

    public function members()
    {
        return $this->hasMany(GroupMember::class,'id','group_id');
    }

}
