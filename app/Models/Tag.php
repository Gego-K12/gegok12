<?php
// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Models;

use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\Models\Media;
use App\Traits\Common;
use App\Models\PostTag;

/**
 * Class Tag
 *
 * Model for managing post tags.
 *
 * @property int $id
 * @property string $tag_name
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property int $tagCount
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Post[] $posts
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PostTag[] $posttag
 * @mixin \Eloquent
 */
class Tag extends Model
{
    //

    use Common;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tags';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tag_name',
        'name',
        'slug',
        'type',
        'order_column',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'array',
        'slug' => 'array',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */

    /**
     * Get posts with this tag.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

    /**
     * Get post tags for this tag.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function posttag()
    {
        return $this->belongsToMany(PostTag::class);
    }

    /**
     * Get count of posts with this tag.
     *
     * @param string $tag_name
     * @return int
     */
    public function getTag($tag_name)
    {//dump($tag_name);

        $tag=Tag::where('tag_name',$tag_name)->first();

        $count=PostTag::where('tag_id',$tag->id)->count();

        return $count;

    }

}
