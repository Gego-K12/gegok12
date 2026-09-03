<div class="px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto">
    <div class="flex flex-wrap lg:flex-row justify-between">
        <div></div>
        <div class="relative flex items-center">
            <a href="#" wire:click.prevent="openAddModal" class="no-underline text-white px-4 my-3 mx-1 flex items-center custom-green py-1 justify-center">
                <span class="mx-1 text-sm font-semibold">Add</span>
            </a>
        </div>
    </div>

    <div class="custom-table mb-3">
        <table class="w-full overflow-x-auto">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>File</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($documents as $document)
                    <tr wire:key="document-{{ $document['id'] }}">
                        <td>{{ $document['name'] }}</td>
                        <td>{{ $document['type'] }}</td>
                        <td>
                            @if($document['path'] !== null)
                                <a href="{{ $document['path'] }}" target="_blank">View</a>
                            @else
                                --
                            @endif
                        </td>
                        <td>
                            <div class="flex items-center">
                                <a href="#" wire:click.prevent="openEditModal({{ $document['id'] }})" class="mx-1 text-blue-600">Edit</a>
                                <a href="#" wire:click.prevent="delete({{ $document['id'] }})" wire:confirm="Do you want to delete this Document?" class="mx-1 text-red-600">Delete</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4"><p class="font-semibold text-s" style="text-align: center">No Records Found</p></td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($showModal)
        <div class="modal modal-mask" style="position: fixed; z-index: 9998; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,.5);">
            <div class="modal-wrapper px-4" style="display: table-cell; vertical-align: middle;">
                <div class="modal-container w-full max-w-md px-8 mx-auto" style="margin: 0 auto; padding: 20px 30px; background-color: #fff; border-radius: 2px; box-shadow: 0 2px 8px rgba(0,0,0,.33);">
                    <div class="modal-header flex justify-between items-center">
                        <h2>{{ $editingId ? 'Edit Document' : 'Add Document' }}</h2>
                        <button type="button" class="modal-default-button text-2xl py-1" wire:click="closeModal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="flex items-center">
                            <div class="w-full lg:w-1/4"><label for="type" class="tw-form-label">Type</label></div>
                            <div class="my-2 w-full lg:w-3/4">
                                <select wire:model="type" id="type" class="tw-form-control w-full">
                                    <option value="" disabled>Select Type</option>
                                    @foreach(\App\Livewire\Admin\Student\Profile\Documents::TYPES as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('type') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="flex items-center">
                            <div class="w-full lg:w-1/4"><label for="title" class="tw-form-label">Title</label></div>
                            <div class="my-2 w-full lg:w-3/4">
                                <input type="text" wire:model="title" id="title" class="tw-form-control w-full" placeholder="Enter Title">
                                @error('title') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="flex items-center">
                            <div class="w-full lg:w-1/4"><label for="attachment" class="tw-form-label">Attach File</label></div>
                            <div class="my-2 w-full lg:w-3/4">
                                <input type="file" wire:model="attachment" class="tw-form-control w-full">
                                <div wire:loading wire:target="attachment" class="text-xs text-gray-500">Uploading...</div>
                                @error('attachmentPath') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="my-6">
                        <button type="button" wire:click="save" wire:loading.attr="disabled" wire:target="save" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
