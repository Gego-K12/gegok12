<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Models;

use Spatie\Tags\Tag as BaseTag;
use Illuminate\Support\Str;

class SpatieTag extends BaseTag
{
    protected $table = 'tags';

    protected $fillable = [
        'tag_name',
        'name',
        'slug',
        'type',
        'order_column',
    ];

    protected $casts = [
        'name' => 'array',
        'slug' => 'array',
    ];

    protected static function booted()
    {
        static::saving(function ($tag) {
            $name = null;

            if (is_array($tag->name)) {
                $name = $tag->name['en'] ?? null;
            } elseif (is_string($tag->name)) {
                $decoded = json_decode($tag->name, true);
                $name = $decoded['en'] ?? $tag->name;
            }

            if ($name) {
                $tag->tag_name = $tag->tag_name ?: $name;

                if (empty($tag->slug)) {
                    $tag->slug = [
                        'en' => Str::slug($name),
                    ];
                }
            }

            $tag->type = $tag->type ?: 'student';
        });
    }
}