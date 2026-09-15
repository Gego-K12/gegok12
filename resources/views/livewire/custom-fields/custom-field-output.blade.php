<div @class(['my-2 text-sm' => ! $bare, 'flex py-1 text-xs' => $bare])>
    <span @class(['font-semibold' => ! $bare, 'text-gray-700 font-medium mx-2' => $bare])>{{ $customField->label }}:</span>

    @if ($customField->field_type === 'file' && $fieldValue?->file_path)
        <a href="{{ Storage::url($fieldValue->file_path) }}" target="_blank" class="text-blue-600 underline">View file</a>
    @elseif (in_array($customField->field_type, ['select', 'radio', 'checkbox']))
        @if($bare)
            <p>{{ count($selectedOptionLabels) ? implode(', ', $selectedOptionLabels) : '-' }}</p>
        @else
            {{ count($selectedOptionLabels) ? implode(', ', $selectedOptionLabels) : '-' }}
        @endif
    @else
        @if($bare)
            <p>{{ $fieldValue?->value ?: '-' }}</p>
        @else
            {{ $fieldValue?->value ?: '-' }}
        @endif
    @endif
</div>
