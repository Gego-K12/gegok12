<div>
    <div class="bg-white rounded shadow p-4">

        @if (session()->has('success'))
            <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex justify-between items-center mb-4">
            <a
                href="{{ url('admin/custom-fields/create') }}"
                class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700"
            >
                Add Custom Field
            </a>

            <input
                type="text"
                wire:model.live.debounce.500ms="search"
                placeholder="Search fields..."
                class="border rounded px-3 py-2 text-sm"
            >
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border px-4 py-2 text-left">Label</th>
                        <th class="border px-4 py-2 text-left">Field Name</th>
                        <th class="border px-4 py-2 text-left">Type</th>
                        <th class="border px-4 py-2 text-left">Usage</th>
                        <th class="border px-4 py-2 text-left">Status</th>
                        <th class="border px-4 py-2 text-left">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($fields as $field)
                        <tr>
                            <td class="border px-4 py-2">{{ $field->label }}</td>
                            <td class="border px-4 py-2 text-gray-500">{{ $field->field_name }}</td>
                            <td class="border px-4 py-2">{{ $fieldTypes[$field->field_type] ?? $field->field_type }}</td>
                            <td class="border px-4 py-2">
                                @forelse ($field->entityConfigs as $config)
                                    <span class="inline-block px-2 py-0.5 rounded text-xs mr-1 mb-1 {{ $config->is_required ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $entityTypes[$config->entity_type] ?? $config->entity_type }}{{ $config->is_required ? ' *' : '' }}
                                    </span>
                                @empty
                                    <span class="text-gray-400 text-xs">Not used anywhere</span>
                                @endforelse
                            </td>
                            <td class="border px-4 py-2">
                                <button
                                    type="button"
                                    wire:click="toggleStatus({{ $field->id }})"
                                    class="px-2 py-1 rounded text-xs {{ $field->status ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600' }}"
                                >
                                    {{ $field->status ? 'Active' : 'Inactive' }}
                                </button>
                            </td>
                            <td class="border px-4 py-2">
                                <a
                                    href="{{ url('admin/custom-fields/'.$field->id.'/edit') }}"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs"
                                >
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="border px-4 py-3 text-center text-gray-500">
                                No custom fields found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $fields->links() }}
        </div>
    </div>
</div>
