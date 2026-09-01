<div class="my-2">
    <p class="tw-form-label mb-0">{{ $customField->label }}</p>

    @if ($customField->field_type === 'file' && $fieldValue?->file_path)
        <a href="{{ Storage::url($fieldValue->file_path) }}" target="_blank" class="text-sm text-blue-600 underline">View file</a>
    @elseif (in_array($customField->field_type, ['select', 'radio', 'checkbox']))
        <p class="text-sm">{{ count($selectedOptionLabels) ? implode(', ', $selectedOptionLabels) : '-' }}</p>
    @else
        <p class="text-sm">{{ $fieldValue?->value ?: '-' }}</p>
    @endif
</div>
