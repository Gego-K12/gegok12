<div>
    <div class="flex items-center justify-between mb-3">
        <h3 class="font-semibold text-sm text-gray-700">Batches for {{ $course->name }}</h3>
        <button type="button" wire:click="openCreateModal" class="blue-bg text-sm text-white px-3 py-1 rounded">
            + Add Batch
        </button>
    </div>

    @if ($batches->isEmpty())
        <div class="border border-dashed rounded p-8 text-center text-sm text-gray-500">
            No batches created yet.
            <button type="button" wire:click="openCreateModal" class="text-blue-600 font-semibold">Create one</button>
        </div>
    @else
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 border-b">
                    <th class="py-2">Name</th>
                    <th>Schedule</th>
                    <th>Capacity</th>
                    <th>Enrolled</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($batches as $batch)
                    <tr class="border-b">
                        <td class="py-2">{{ $batch->name }}</td>
                        <td>{{ $batch->start_date->toDateString() }} to {{ $batch->end_date?->toDateString() ?? '—' }}</td>
                        <td>{{ $batch->capacity ?? 'Unlimited' }}</td>
                        <td>{{ $batch->active_enrollments_count }}</td>
                        <td>{{ $batch->status }}</td>
                        <td class="whitespace-nowrap">
                            <button type="button" wire:click="generateSessions({{ $batch->id }})" class="text-blue-600 text-xs mr-2" wire:loading.attr="disabled">Generate sessions</button>
                            <button type="button" wire:click="openBatch({{ $batch->id }})" class="text-green-600 text-xs mr-2" wire:loading.attr="disabled">Open</button>
                            <button type="button" wire:click="closeBatch({{ $batch->id }})" class="text-gray-600 text-xs" wire:loading.attr="disabled">Close</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if ($showCreateModal)
        <div class="fixed inset-0 z-50 flex items-start justify-center overflow-y-auto bg-black bg-opacity-50 py-8">
            <div class="bg-white rounded shadow-lg w-full max-w-2xl p-5">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-semibold text-lg text-gray-800">New Batch</h2>
                    <button type="button" wire:click="closeCreateModal" class="text-gray-400 hover:text-gray-700 text-xl leading-none">&times;</button>
                </div>

                <form wire:submit="createBatch">
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Batch name</label>
                            <input type="text" wire:model="name" class="tw-form-control text-sm w-full">
                            @error('name') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Venue</label>
                            <input type="text" wire:model="venue" class="tw-form-control text-sm w-full">
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-3 mb-3">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Start date</label>
                            <input type="date" wire:model="start_date" class="tw-form-control text-sm w-full">
                            @error('start_date') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">End date</label>
                            <input type="date" wire:model="end_date" class="tw-form-control text-sm w-full">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Capacity</label>
                            <input type="number" wire:model="capacity" min="1" class="tw-form-control text-sm w-full">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="block text-xs text-gray-600 mb-1">Days</label>
                        <div class="flex gap-3 text-xs">
                            @foreach (['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $day)
                                <label class="flex items-center gap-1">
                                    <input type="checkbox" wire:model="timing_days" value="{{ $day }}"> {{ $day }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Start time</label>
                            <input type="time" wire:model="start_time" class="tw-form-control text-sm w-full">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">End time</label>
                            <input type="time" wire:model="end_time" class="tw-form-control text-sm w-full">
                        </div>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" wire:click="closeCreateModal" class="text-sm text-gray-600 px-3 py-1">Cancel</button>
                        <button type="submit" class="blue-bg text-sm text-white px-4 py-1 rounded" wire:loading.attr="disabled" wire:target="createBatch">
                            Create Batch
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
