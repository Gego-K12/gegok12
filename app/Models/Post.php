<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Models;

use App\Traits\Common;
use Illuminate\Database\Eloquent\Collection;
// use Spatie\MediaLibrary\HasMedia\HasMedia;
use Illuminate\Database\Eloquent\Model;
// use Spatie\MediaLibrary\Models\Media;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Class Post
 *
 * Model for managing posts and wall updates.
 *
 * @property int $id
 * @property int $school_id
 * @property int $academic_year_id
 * @property int $entity_id
 * @property string $entity_name
 * @property string $description
 * @property array $attachment_file
 * @property string $visibility
 * @property int $visible_for
 * @property \DateTime $post_created_at
 * @property int $is_posted
 * @property \DateTime $posted_at
 * @property int $status
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime $deleted_at
 * @property string $attachment_path
 * @property string $postComments
 * @property-read School $school
 * @property-read AcademicYear $academicYear
 * @property-read StandardLink $standardLink
 * @property-read PostDetail $postDetail
 * @property-read Collection|PostComment[] $postComment
 * @property-read Collection|Tag[] $tag
 *
 * @mixin \Eloquent
 */
class Post extends Model implements HasMedia
{
    use Common;
    //
    // use HasMediaTrait;
    use InteractsWithMedia;
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'posts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_id', 'academic_year_id', 'entity_id', 'entity_name', 'description', 'attachment_file', 'visibility', 'visible_for', 'post_created_at', 'is_posted', 'posted_at', 'status',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'attachment_file' => 'array',
        'post_created_at' => 'datetime',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['posted_at', 'deleted_at'];

    /**
     * Get the school for this post.
     *
     * @return BelongsTo
     */
    public function school()
    {
        return $this->belongsTo('\App\Models\School', 'school_id');
    }

    /**
     * Get the academic year for this post.
     *
     * @return BelongsTo
     */
    public function academicYear()
    {
        return $this->belongsTo('\App\Models\AcademicYear', 'academic_year_id');
    }

    /**
     * Get the standard link for visibility of this post.
     *
     * @return BelongsTo
     */
    public function standardLink()
    {
        return $this->belongsTo('\App\Models\StandardLink', 'visible_for');
    }

    /**
     * Get the post details for this post.
     *
     * @return HasOne
     */
    public function postDetail()
    {
        return $this->hasOne('\App\Models\PostDetail', 'post_id', 'id');
    }

    /**
     * Get all comments on this post.
     *
     * @return HasMany
     */
    public function postComment()
    {
        return $this->hasMany('\App\Models\PostComment', 'entity_id', 'id');
    }

    /**
     * Get the attachment file paths for this post.
     *
     * @return array
     */
    public function getAttachmentPathAttribute()
    {
        $files = $this->attachment_file ?? [];

        if (! is_array($files)) {
            $files = [];
        }

        $attachment = [];

        foreach ($files as $index => $file) {

            $attachment[] = [
                'original_path' => $file,
                'path' => $this->getFilePath($file),
                'id' => $index,
            ];
        }

        return $attachment;
    }

    /**
     * Get formatted post comments with additional details.
     *
     * @return array
     */
    public function getPostCommentsAttribute()
    {
        $i = 0;
        $array = [];
        foreach ($this->postComment as $postComment) {
            $array[$i]['comment_id'] = $postComment->id;
            $array[$i]['post_id'] = $postComment->post_id;
            $array[$i]['user_id'] = $postComment->user_id;
            $array[$i]['comments'] = $postComment->comments;
            $array[$i]['attachment_file'] = $postComment->attachment_file == '' ? null : $this->getFilePath($postComment->attachment_file);
            $array[$i]['user_name'] = $postComment->user->name;
            $array[$i]['user_fullname'] = ucwords($postComment->user->FullName);
            $array[$i]['user_avatar'] = $postComment->user->userprofile->AvatarPath;
            $array[$i]['commented_at'] = $postComment->updated_at->diffForHumans();
            $array[$i]['comment_details'] = $postComment->PostCommentDetails;
            $array[$i]['like_count'] = $postComment->CommentLikeCount;
            $array[$i]['unlike_count'] = $postComment->CommentUnlikeCount;
            $postCommentDetail = PostCommentDetail::where('post_comment_id', $postComment->id)->first();
            if ($postCommentDetail != null) {
                $array[$i]['like'] = $postCommentDetail->like;
                $array[$i]['unlike'] = $postCommentDetail->unlike;
            }
            $i++;
        }

        return $array;
    }

    /**
     * Get the tags associated with this post.
     *
     * @return BelongsToMany
     */
    public function tag()
    {
        return $this->belongsToMany(Tag::class, 'post_tags');
    }
}
