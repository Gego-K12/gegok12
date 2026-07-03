<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PostCommentDetail
 *
 * Model for managing like/unlike details on post comments.
 *
 * @property int $id
 * @property int $user_id
 * @property int $post_comment_id
 * @property int $like
 * @property int $unlike
 * @property int $status
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime $deleted_at
 * @property-read User $user
 * @property-read PostComment $postComment
 *
 * @mixin \Eloquent
 */
class PostCommentDetail extends Model
{
    //
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'post_comment_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'post_comment_id',  'like', 'unlike', 'status',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get the user who liked/unliked this comment.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('\App\Models\User', 'user_id');
    }

    /**
     * Get the comment this detail belongs to.
     *
     * @return BelongsTo
     */
    public function postComment()
    {
        return $this->belongsTo('\App\Models\PostComment', 'post_comment_id');
    }
}
