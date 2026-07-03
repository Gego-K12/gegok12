<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MailTemplate
 *
 * Model for managing email message templates.
 *
 * @property int $id
 * @property string $name
 * @property string $subject
 * @property string $mail_content
 * @property int $status
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 *
 * @mixin \Eloquent
 */
class MailTemplate extends Model
{
    //
    protected $table = 'mailtemplates';

    protected $fillable = ['name', 'subject', 'mail_content', 'status'];
}
