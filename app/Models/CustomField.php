<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomField extends Model
{
    use SoftDeletes;

    protected $table = 'custom_fields';

    protected $fillable = [
        'school_id', 'field_name', 'label', 'placeholder', 'instructions', 'validation_pattern', 'field_type', 'status', 'order_no',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function options(): HasMany
    {
        return $this->hasMany(CustomFieldOption::class)->orderBy('order_no');
    }

    public function entityConfigs(): HasMany
    {
        return $this->hasMany(CustomFieldEntityConfig::class);
    }

    public function values(): HasMany
    {
        return $this->hasMany(CustomFieldValue::class);
    }

    /**
     * The field-level custom regex rule (if any), applied to every entity
     * that uses this field unless a per-entity Entity Config validation
     * type overrides it.
     */
    public function baseValidationRule(): ?string
    {
        return $this->validation_pattern ? 'regex:'.$this->validation_pattern : null;
    }

    /**
     * Add PCRE delimiters around a regex pattern if the admin typed the
     * bare pattern body without them (a very common mistake — PHP requires
     * e.g. "/^[A-Z]{5}$/", not just "^[A-Z]{5}$"). Leaves already-delimited
     * patterns untouched.
     */
    public static function normalizePattern(?string $pattern): ?string
    {
        $pattern = $pattern !== null ? trim($pattern) : null;

        if (! $pattern) {
            return null;
        }

        $delimiter = $pattern[0];
        $closers = ['(' => ')', '{' => '}', '[' => ']', '<' => '>'];
        $closingChar = $closers[$delimiter] ?? $delimiter;

        if (! ctype_alnum($delimiter) && $delimiter !== '\\' && $delimiter !== ' ') {
            $body = substr($pattern, 1);

            if (preg_match('/'.preg_quote($closingChar, '/').'[a-zA-Z]*$/', $body)) {
                return $pattern;
            }
        }

        return '/'.str_replace('/', '\/', $pattern).'/';
    }
}
