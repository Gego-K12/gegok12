<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Smstemplate
 *
 * Model for managing SMS message templates.
 *
 * @property int $id
 * @property string $name
 * @property string $content
 * @property int $status
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 *
 * @mixin \Eloquent
 */
class Smstemplate extends Model
{
    //
    protected $table = 'sms_templates';

    protected $fillable = ['name', 'template', 'content', 'status', 'template_id'];
}
