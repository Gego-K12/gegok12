<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Models;

use App\Traits\Common;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class PostTag
 *
 * Model for managing tags on posts.
 *
 * @property int $id
 * @property int $tag_id
 * @property int $post_id
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property-read Collection|Tag[] $tag
 *
 * @mixin \Eloquent
 */
class PostTag extends Model
{
    //

    use Common;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'post_tags';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tag_id', 'post_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    /**
     * Get tags for this post.
     *
     * @return HasMany
     */
    public function tag()
    {
        return $this->hasMany(Tag::class);
    }
}
