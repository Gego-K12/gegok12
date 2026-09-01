<div class="bg-white rounded shadow p-4 max-w-lg">
    <form wire:submit.prevent="save">
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">
                Label <span class="text-red-500">*</span>
            </label>
            <input
                type="text"
                wire:model="label"
                class="w-full border rounded px-3 py-2 text-sm"
                placeholder="e.g. PAN Card Number">
            @error('label') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Field Type</label>
            <select wire:model.live="field_type" class="w-full border rounded px-3 py-2 text-sm">
                @foreach ($fieldTypes as $value => $typeLabel)
                <option value="{{ $value }}">{{ $typeLabel }}</option>
                @endforeach
            </select>
            @error('field_type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
        @if (in_array($field_type, ['select', 'radio', 'checkbox']))
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Options</label>

            @foreach ($options as $index => $option)
            <div class="flex gap-2 mb-2">
                <input
                    type="text"
                    wire:model="options.{{ $index }}.option_label"
                    placeholder="Option label"
                    class="flex-1 border rounded px-3 py-2 text-sm">
                <button
                    type="button"
                    wire:click="removeOption({{ $index }})"
                    class="text-red-500 px-2">
                    &times;
                </button>
            </div>
            @error("options.{$index}.option_label") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            @endforeach

            <button type="button" wire:click="addOption" class="text-blue-600 text-sm">
                + Add option
            </button>
            @error('options') <span class="text-red-500 text-xs block">{{ $message }}</span> @enderror
        </div>
        @endif

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Placeholder Text</label>
            <input
                type="text"
                wire:model="placeholder"
                class="w-full border rounded px-3 py-2 text-sm"
                placeholder="Enter Placeholder Text">
            @error('placeholder') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Instructions for user</label>
            <textarea
                wire:model="instructions"
                class="w-full border rounded px-3 py-2 text-sm"
                placeholder="Enter Instruction for User"></textarea>
            @error('instructions') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Custom Validation (RegEx)</label>
            <input
                type="text"
                wire:model="validationPattern"
                class="w-full border rounded px-3 py-2 text-sm"
                placeholder="Enter Regular Expression">
            @error('validationPattern') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            <p class="text-xs text-gray-500 mt-1">Example for Regular Expression: /^[a-zA-Z0-9_]*$/</p>
            <a href="https://regexr.com" target="_blank" class="text-xs text-blue-600 underline">Check this Link regexr.com</a>
        </div>


        <div class="mb-4">
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" wire:model="status">
                Active
            </label>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-2">Usage</label>

            @foreach ($entityTypes as $value => $entityLabel)
            <div class="flex items-center justify-between px-2 py-2 {{ $loop->even ? 'bg-gray-50' : '' }} rounded">
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" wire:model.live="entityEnabled.{{ $value }}">
                    {{ $entityLabel }}
                </label>
                <label class="flex items-center gap-2 text-sm {{ ($entityEnabled[$value] ?? false) ? '' : 'text-gray-400' }}">
                    <input type="checkbox" wire:model="entityRequired.{{ $value }}" @disabled(! ($entityEnabled[$value] ?? false))>
                    Is Required
                </label>
            </div>
            @endforeach
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ url('admin/custom-fields') }}" class="px-4 py-2 border rounded text-sm">
                Cancel
            </a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">
                {{ $field_id ? 'Update' : 'Save' }}
            </button>
        </div>
    </form>
</div>