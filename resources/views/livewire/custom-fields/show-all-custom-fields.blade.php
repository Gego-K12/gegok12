<div>
    @if ($fields->isNotEmpty())
        <div class="bg-white shadow my-5 px-4 py-3">
            <h2 class="text-lg font-semibold mb-2">Custom Fields</h2>

            @foreach ($fields as $field)
                @livewire('custom-fields.custom-field-output', [
                    'customFieldId' => $field->id,
                    'entityType' => $entityType,
                    'entityId' => $entityId,
                ], key('custom-field-output-'.$field->id))
            @endforeach
        </div>
    @endif
</div>
