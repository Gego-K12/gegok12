<div>
    @if ($fields->isNotEmpty())
        <div @class(['bg-white shadow my-5 px-4 py-3' => ! $bare])>
            <h2 @class(['text-lg font-semibold mb-2' => ! $bare, 'text-gray-700 font-medium mx-2 mb-1' => $bare])>Additional Info</h2>

            @foreach ($fields as $field)
                @livewire('custom-fields.custom-field-output', [
                    'customFieldId' => $field->id,
                    'entityType' => $entityType,
                    'entityId' => $entityId,
                    'bare' => $bare,
                ], key('custom-field-output-'.$field->id))
            @endforeach
        </div>
    @endif
</div>
