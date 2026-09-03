@php($required = $field->entityConfigs->first()?->is_required)
<div class="my-2" wire:key="custom-field-{{ $field->id }}">
    <label class="tw-form-label">
        {{ $field->label }}
        @if ($required)<span class="text-red-500">*</span>@endif
    </label>
    @if ($field->instructions)
        <p class="text-xs text-gray-500 mb-1">{{ $field->instructions }}</p>
    @endif

    @switch($field->field_type)
        @case('textarea')
            <textarea wire:model="custom_fields.{{ $field->id }}" placeholder="{{ $field->placeholder }}" class="tw-form-control w-full my-1 py-2"></textarea>
            @break

        @case('select')
            <select wire:model="custom_fields.{{ $field->id }}" class="tw-form-control w-full my-1 py-2">
                <option value="">Select {{ $field->label }}</option>
                @foreach ($field->options as $option)
                    <option value="{{ $option->option_value }}">{{ $option->option_label }}</option>
                @endforeach
            </select>
            @break

        @case('radio')
            <div class="flex flex-wrap gap-4 my-1">
                @foreach ($field->options as $option)
                    <label class="flex items-center gap-1 text-sm">
                        <input type="radio" wire:model="custom_fields.{{ $field->id }}" value="{{ $option->option_value }}">
                        {{ $option->option_label }}
                    </label>
                @endforeach
            </div>
            @break

        @case('checkbox')
            <div class="flex flex-wrap gap-4 my-1">
                @foreach ($field->options as $option)
                    <label class="flex items-center gap-1 text-sm">
                        <input type="checkbox" wire:model="custom_fields.{{ $field->id }}" value="{{ $option->option_value }}">
                        {{ $option->option_label }}
                    </label>
                @endforeach
            </div>
            @break

        @case('file')
            <input type="file" wire:model="custom_fields.{{ $field->id }}" class="tw-form-control w-full my-1">
            @if (! empty($existingFilePath ?? null))
                <a href="{{ Storage::url($existingFilePath) }}" target="_blank" class="text-xs text-blue-600 underline">View current file</a>
            @endif
            <div wire:loading wire:target="custom_fields.{{ $field->id }}" class="text-xs text-gray-500">Uploading...</div>
            @break

        @case('date')
            <input type="date" wire:model="custom_fields.{{ $field->id }}" class="tw-form-control w-full my-1 py-2">
            @break

        @case('number')
            <input type="number" wire:model="custom_fields.{{ $field->id }}" placeholder="{{ $field->placeholder }}" class="tw-form-control w-full my-1 py-2">
            @break

        @case('email')
            <input type="email" wire:model="custom_fields.{{ $field->id }}" placeholder="{{ $field->placeholder }}" class="tw-form-control w-full my-1 py-2">
            @break

        @default
            <input type="text" wire:model="custom_fields.{{ $field->id }}" placeholder="{{ $field->placeholder }}" class="tw-form-control w-full my-1 py-2">
    @endswitch

    @error("custom_fields.{$field->id}")
        <span class="text-red-500 text-xs font-semibold">{{ $message }}</span>
    @enderror
</div>
