<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Models;

use App\Traits\Common;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class BackgroundImage
 *
 * Model for managing background images with file path resolution.
 *
 * @property int $id
 * @property string $background_image
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime $deleted_at
 * @property string $attachment_path
 *
 * @mixin \Eloquent
 */
class BackgroundImage extends Model
{
    use Common;
    use SoftDeletes;

    /**
     * Get the full attachment path for the background image.
     *
     * @return string
     */
    public function getAttachmentPathAttribute()
    {
        return $this->getFilePath($this->background_image);
    }
}
