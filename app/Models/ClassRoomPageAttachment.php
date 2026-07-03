<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Models;

use App\Traits\Common;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

/**
 * Class ClassRoomPageAttachment
 *
 * Model for managing classroom page file attachments.
 *
 * @property int $id
 * @property int $page_id
 * @property string $attachment_file
 * @property int $status
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime $deleted_at
 * @property string $attachment_path
 * @property-read ClassRoomPage $classRoomPage
 *
 * @mixin \Eloquent
 */
class ClassRoomPageAttachment extends Model implements HasMedia
{
    use Common;
    use HasMediaTrait;
    use SoftDeletes;

    protected $table = 'class_room_page_attachments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'page_id', 'attachment_file', 'status',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get the classroom page for this attachment.
     *
     * @return BelongsTo
     */
    public function classRoomPage()
    {
        return $this->belongsTo('\App\Models\ClassRoomPage', 'page_id');
    }

    /**
     * Get the full file path for the attachment.
     *
     * @return string
     */
    public function getAttachmentPathAttribute()
    {
        return $this->getFilePath($this->attachment_file);
    }
}
