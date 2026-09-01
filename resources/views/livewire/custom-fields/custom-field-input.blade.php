<div class="my-2" wire:key="custom-field-{{ $customField->id }}">
    <label class="tw-form-label">
        {{ $customField->label }}
        @if ($entityConfig?->is_required)
            <span class="text-red-500">*</span>
        @endif
    </label>
    @if ($customField->instructions)
        <p class="text-xs text-gray-500 mb-1">{{ $customField->instructions }}</p>
    @endif

    @switch($customField->field_type)
        @case('textarea')
            <textarea wire:model.blur="value" placeholder="{{ $customField->placeholder }}" class="tw-form-control w-full my-1 py-2"></textarea>
            @break

        @case('select')
            <select wire:model.live="value" class="tw-form-control w-full my-1 py-2">
                <option value="">Select {{ $customField->label }}</option>
                @foreach ($customField->options as $option)
                    <option value="{{ $option->option_value }}">{{ $option->option_label }}</option>
                @endforeach
            </select>
            @break

        @case('radio')
            <div class="flex flex-wrap gap-4 my-1">
                @foreach ($customField->options as $option)
                    <label class="flex items-center gap-1 text-sm">
                        <input type="radio" wire:model.live="value" value="{{ $option->option_value }}">
                        {{ $option->option_label }}
                    </label>
                @endforeach
            </div>
            @break

        @case('checkbox')
            <div class="flex flex-wrap gap-4 my-1">
                @foreach ($customField->options as $option)
                    <label class="flex items-center gap-1 text-sm">
                        <input type="checkbox" wire:model.live="checkboxValues" value="{{ $option->option_value }}">
                        {{ $option->option_label }}
                    </label>
                @endforeach
            </div>
            @break

        @case('file')
            <input type="file" wire:model="file" class="tw-form-control w-full my-1">
            @if ($existingFilePath)
                <a href="{{ Storage::url($existingFilePath) }}" target="_blank" class="text-xs text-blue-600 underline">View current file</a>
            @endif
            <div wire:loading wire:target="file" class="text-xs text-gray-500">Uploading...</div>
            @break

        @case('date')
            <input type="date" wire:model.live="value" class="tw-form-control w-full my-1 py-2">
            @break

        @case('number')
            <input type="number" wire:model.blur="value" placeholder="{{ $customField->placeholder }}" class="tw-form-control w-full my-1 py-2">
            @break

        @case('email')
            <input type="email" wire:model.blur="value" placeholder="{{ $customField->placeholder }}" class="tw-form-control w-full my-1 py-2">
            @break

        @default
            <input type="text" wire:model.blur="value" placeholder="{{ $customField->placeholder }}" class="tw-form-control w-full my-1 py-2">
    @endswitch

    @error('value') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
    @error('checkboxValues') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
    @error('file') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror

    <div wire:loading.remove wire:target="value,checkboxValues,file">
        @if ($saved)
            <span class="text-green-600 text-xs">Saved</span>
        @endif
    </div>
</div>
