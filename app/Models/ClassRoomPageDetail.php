<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ClassRoomPageDetail
 *
 * Model for managing user interactions with classroom pages.
 *
 * @property int $id
 * @property int $user_id
 * @property int $page_id
 * @property int $is_following
 * @property int $like
 * @property int $dislike
 * @property int $status
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime $deleted_at
 * @property-read User $user
 * @property-read ClassRoomPage $classRoomPage
 *
 * @mixin \Eloquent
 */
class ClassRoomPageDetail extends Model
{
    use SoftDeletes;

    protected $table = 'class_room_page_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'page_id', 'is_following', 'like', 'dislike', 'status',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get the user for this page detail record.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('\App\Models\User', 'user_id');
    }

    /**
     * Get the classroom page for this detail record.
     *
     * @return BelongsTo
     */
    public function classRoomPage()
    {
        return $this->belongsTo('\App\Models\ClassRoomPage', 'page_id');
    }

    /**
     * Scope to get records with likes for a specific page.
     *
     * @param  Builder  $query
     * @param  int  $page_id
     * @return Collection
     */
    public function scopeByLike($query, $page_id)
    {
        $count = $query->where('page_id', $page_id)->where('like', 1)->get();

        return $count;
    }

    /**
     * Scope to count records with dislikes for a specific page.
     *
     * @param  Builder  $query
     * @param  int  $page_id
     * @return int
     */
    public function scopeByUnlike($query, $page_id)
    {
        $count = $query->where('page_id', $page_id)->where('dislike', 1)->count();

        return $count;
    }
}
