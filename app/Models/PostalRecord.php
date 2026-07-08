<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Models;

use App\Traits\Common;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PostalRecord
 *
 * Model for managing postal records and correspondence.
 *
 * @property int $id
 * @property int $school_id
 * @property int $academic_year_id
 * @property string $type
 * @property string $reference_number
 * @property int $confidential
 * @property string $sender_title
 * @property string $sender_address
 * @property string $receiver_title
 * @property string $receiver_address
 * @property \DateTime $postal_date
 * @property string $description
 * @property int $entry_by
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property string $attachment_path
 *
 * @mixin \Eloquent
 */
class PostalRecord extends Model
{
    use Common;

    protected $table = 'postal_record';

    protected $fillable = [
        'school_id', 'academic_year_id', 'type', 'reference_number', 'confidential', 'sender_title', 'sender_address', 'receiver_title', 'receiver_address', 'postal_date', 'description', 'entry_by',
    ];

    /**
     * Get the full file path for the postal record attachment.
     *
     * @return string
     */
    public function getAttachmentPathAttribute()
    {
        return $this->getFilePath($this->attachment);
    }
}
