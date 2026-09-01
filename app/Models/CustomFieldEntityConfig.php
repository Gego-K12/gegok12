<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomFieldEntityConfig extends Model
{
    protected $table = 'custom_field_entity_config';

    protected $fillable = [
        'custom_field_id', 'entity_type', 'is_enabled', 'is_required', 'order_no',
        'validation_type', 'validation_pattern', 'validation_digits',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'is_required' => 'boolean',
    ];

    public function customField(): BelongsTo
    {
        return $this->belongsTo(CustomField::class);
    }

    /**
     * Build the extra Laravel validation rule (if any) implied by this
     * config's validation_type, for appending onto a field's base rule set.
     */
    public function additionalRule(): ?string
    {
        return match ($this->validation_type) {
            'numeric' => $this->validation_digits ? 'digits:'.$this->validation_digits : 'numeric',
            'decimal' => 'regex:/^[0-9]+(\.[0-9]+)?$/',
            'alphanumeric' => 'alpha_num',
            'alphabetic' => 'regex:/^[A-Za-z\s]+$/',
            'url' => 'url',
            'regexp' => $this->validation_pattern ? 'regex:'.$this->validation_pattern : null,
            default => null,
        };
    }
}
