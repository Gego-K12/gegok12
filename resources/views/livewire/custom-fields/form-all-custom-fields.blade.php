<div>
    @if ($fields->isEmpty())
        <p class="text-sm text-gray-500">No custom fields configured.</p>
    @else
        @foreach ($fields as $field)
            @livewire('custom-fields.custom-field-input', [
                'customFieldId' => $field->id,
                'entityType' => $entityType,
                'entityId' => $entityId,
                'schoolId' => $schoolId,
            ], key('custom-field-input-'.$field->id))
        @endforeach
    @endif
</div>
