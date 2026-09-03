<div class="px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3">
    <label class="font-semibold text-lg text-gray-800 capitalize">Notes</label>
    <div class="flex flex-col lg:flex-row md:flex-row py-4">
        <div class="w-full lg:w-1/2 md:w-1/2">
            <div class="notes-content">
                <ul>
                    @forelse($tasks as $task)
                        <li class="task_item px-4 py-2 mb-2 bg-gray-200 border-b-1 flex" wire:key="note-{{ $task->id }}">
                            <div class="flex w-full">
                                <div class="flex-1 flex flex-col">
                                    <div class="task_item_title" style="cursor: pointer;" wire:click="edit({{ $task->id }})">{{ $task->notes }}</div>
                                    <p class="date text-xs text-gray-500">{{ $task->created_at?->format('d M Y, H:i') }}</p>
                                </div>
                                <div class="delete" style="cursor: pointer;" wire:click="delete({{ $task->id }})" wire:confirm="Are you sure to delete note?">&times;</div>
                            </div>
                        </li>
                    @empty
                        <li class="emptyList text-sm text-gray-700">No notes added</li>
                    @endforelse
                </ul>
            </div>
        </div>
        <div class="w-full lg:w-1/2 md:w-1/2 lg:mx-4 md:mx-4 my-3 lg:my-0 md:my-0">
            <div class="form-group">
                <textarea rows="5" cols="50" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-0" wire:model="notes" placeholder="Add a note"></textarea>
                @error('notes') <p class="text-red-500 text-xs my-1">{{ $message }}</p> @enderror
            </div>
            <div class="my-6">
                <button type="button" wire:click="save" wire:loading.attr="disabled" wire:target="save" class="btn btn-primary submit-btn">{{ $editingId ? 'Update' : 'Submit' }}</button>
                @if($editingId)
                    <button type="button" wire:click="cancelEdit" class="mx-2 text-sm text-gray-600">Cancel</button>
                @endif
            </div>
        </div>
    </div>
</div>
