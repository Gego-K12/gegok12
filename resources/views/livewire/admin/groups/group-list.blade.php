<div>
    <div class="bg-white rounded shadow p-4">

        @if (session()->has('success'))
            <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex justify-between items-center mb-4">
            <button 
                type="button"
                wire:click="openModal"
                class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700"
            >
                Add Group
            </button>

            <input 
                type="text"
                wire:model.live.debounce.500ms="search"
                placeholder="Search groups..."
                class="border rounded px-3 py-2 text-sm"
            >
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border px-4 py-2 text-left">#</th>
                        <th class="border px-4 py-2 text-left">Group Name</th>
                        <th class="border px-4 py-2 text-left">Type</th>
                        <th class="border px-4 py-2 text-left">Created At</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($groups as $index => $group)
                        <tr>
                            <td class="border px-4 py-2">
                                {{ $groups->firstItem() + $index }}
                            </td>

                            <td class="border px-4 py-2">
                                {{ ucfirst($group->group_name) }}
                            </td>

                            <td class="border px-4 py-2">
                                {{ $group->type ?? '-' }}
                            </td>

                            <td class="border px-4 py-2">
                                {{ $group->created_at ? $group->created_at->format('d-m-Y') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="border px-4 py-3 text-center text-gray-500">
                                No groups found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $groups->links() }}
        </div>
    </div>

    {{-- Add Group Modal --}}
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded shadow-lg w-full max-w-md p-6">

                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold">Add Group</h2>

                    <button 
                        type="button"
                        wire:click="closeModal"
                        class="text-gray-500 hover:text-gray-700 text-xl"
                    >
                        &times;
                    </button>
                </div>

                <form wire:submit.prevent="saveGroup">
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">
                            Group Name <span class="text-red-500">*</span>
                        </label>

                        <input 
                            type="text"
                            wire:model="group_name"
                            class="w-full border rounded px-3 py-2 text-sm"
                            placeholder="Enter group name"
                        >

                        @error('group_name')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">
                            Type
                        </label>

                        <select 
                            wire:model.live="type"
                            class="w-full border rounded px-3 py-2 text-sm"
                        >
                            <option value="">Select Type</option>
                            <option value="global">Global</option>
                            <option value="class">Class</option>
                        </select>

                        @error('type')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    @if($type == 'class')
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">
                                Standard <span class="text-red-500">*</span>
                            </label>

                            <select 
                                wire:model="standardLink_id"
                                class="w-full border rounded px-3 py-2 text-sm"
                            >
                                <option value="">Select Standard</option>

                                @foreach($standardLinks as $standardLink)
                                    <option value="{{ $standardLink->id }}">
                                        {{ $standardLink->StandardSection }}
                                    </option>
                                @endforeach
                            </select>

                            @error('standard_id')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif

                    <div class="flex justify-end gap-2">
                        <button 
                            type="button"
                            wire:click="closeModal"
                            class="px-4 py-2 border rounded text-sm"
                        >
                            Cancel
                        </button>

                        <button 
                            type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700"
                        >
                            Save
                        </button>
                    </div>
                </form>

            </div>
        </div>
    @endif
</div>