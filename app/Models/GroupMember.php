<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupMember extends Model
{
    use SoftDeletes;
    
    protected $table = 'group_members';

    protected $fillable = [
        'group_id' , 'member_id' ,'member_type'
    ];

}
