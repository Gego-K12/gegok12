<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PostDetail
 *
 * Model for managing like/unlike/save details on posts.
 *
 * @property int $id
 * @property int $user_id
 * @property int $post_id
 * @property int $like
 * @property int $unlike
 * @property int $save
 * @property int $status
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime $deleted_at
 * @property-read User $user
 * @property-read Post $post
 *
 * @mixin \Eloquent
 */
class PostDetail extends Model
{
    //
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'post_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'post_id', 'like', 'unlike', 'save', 'status',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get the user who liked/unliked/saved this post.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('\App\Models\User', 'user_id');
    }

    /**
     * Get the post this detail belongs to.
     *
     * @return BelongsTo
     */
    public function post()
    {
        return $this->belongsTo('\App\Models\Post', 'post_id');
    }

    /**
     * Get count of likes for a post.
     *
     * @param  Builder  $query
     * @param  int  $post_id
     * @return int
     */
    public function scopeByLikeCount($query, $post_id)
    {
        $count = $query->where('post_id', $post_id)->where('like', 1)->count();

        return $count;
    }

    /**
     * Get count of unlikes for a post.
     *
     * @param  Builder  $query
     * @param  int  $post_id
     * @return int
     */
    public function scopeByUnlikeCount($query, $post_id)
    {
        $count = $query->where('post_id', $post_id)->where('unlike', 1)->count();

        return $count;
    }
}
