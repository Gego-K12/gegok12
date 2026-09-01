<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Helpers;

use App\Models\CustomField;
use App\Models\CustomFieldEntityConfig;
use App\Models\CustomFieldValue;
use Illuminate\Support\Collection;

class CustomFieldHelper
{
    /**
     * Get the custom fields enabled for a given entity type, with their
     * options and the matching entity config eager-loaded.
     */
    public static function getFieldsForEntity(string $entityType, int $schoolId): Collection
    {
        return CustomField::where('school_id', $schoolId)
            ->where('status', true)
            ->whereHas('entityConfigs', function ($query) use ($entityType) {
                $query->where('entity_type', $entityType)->where('is_enabled', true);
            })
            ->with(['options', 'entityConfigs' => function ($query) use ($entityType) {
                $query->where('entity_type', $entityType);
            }])
            ->orderBy('order_no')
            ->get();
    }

    /**
     * Get the stored values for an entity record, keyed by custom_field_id.
     */
    public static function getValues(string $entityType, int $entityId): Collection
    {
        return CustomFieldValue::where('entity_type', $entityType)
            ->where('entity_id', $entityId)
            ->get()
            ->keyBy('custom_field_id');
    }

    /**
     * Build Laravel validation rules for the `custom_fields` input of a
     * plain HTML form submitting values for a not-yet-created entity
     * (e.g. an "Add Student" form), keyed by "custom_fields.{id}".
     */
    public static function validationRules(string $entityType, int $schoolId): array
    {
        $rules = [];

        foreach (self::getFieldsForEntity($entityType, $schoolId) as $field) {
            $config = $field->entityConfigs->first();
            $required = $config?->is_required ? 'required' : 'nullable';
            $key = "custom_fields.{$field->id}";

            $rule = match ($field->field_type) {
                'email' => "{$required}|email",
                'number' => "{$required}|numeric",
                'date' => "{$required}|date",
                'file' => "{$required}|file|max:5120",
                'checkbox' => $required === 'required' ? 'required|array|min:1' : 'nullable|array',
                default => "{$required}|string|max:1000",
            };

            if (! in_array($field->field_type, ['file', 'checkbox', 'date']) && $additional = self::resolveAdditionalRule($field, $config)) {
                $rule .= '|'.$additional;
            }

            $rules[$key] = $rule;
        }

        return $rules;
    }

    /**
     * The extra validation rule to apply for one field on one entity:
     * a per-entity Entity Config validation type takes precedence when
     * set, otherwise falls back to the field's own base custom regex.
     */
    public static function resolveAdditionalRule(CustomField $field, ?CustomFieldEntityConfig $config): ?string
    {
        if ($config && $config->validation_type !== 'none' && $config->additionalRule()) {
            return $config->additionalRule();
        }

        return $field->baseValidationRule();
    }

    /**
     * Human-readable `:attribute` names for validationRules()'s keys, so
     * error messages read "The PAN Card field is required." instead of
     * "The custom_fields.5 field is required."
     */
    public static function validationAttributes(string $entityType, int $schoolId): array
    {
        $attributes = [];

        foreach (self::getFieldsForEntity($entityType, $schoolId) as $field) {
            $attributes["custom_fields.{$field->id}"] = $field->label;
        }

        return $attributes;
    }

    /**
     * Create or update the stored value for one field on one entity record.
     */
    public static function setValue(
        int $customFieldId,
        string $entityType,
        int $entityId,
        int $schoolId,
        ?string $value,
        ?string $filePath = null
    ): CustomFieldValue {
        return CustomFieldValue::updateOrCreate(
            [
                'custom_field_id' => $customFieldId,
                'entity_type' => $entityType,
                'entity_id' => $entityId,
            ],
            [
                'school_id' => $schoolId,
                'value' => $value,
                'file_path' => $filePath,
            ]
        );
    }
}
