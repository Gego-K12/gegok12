<div class="px-4 py-4">
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="text-sm font-semibold text-gray-800 tracking-wide">Student Tags</h3>
            <div class="flex items-center gap-2">
                <button type="button" wire:click="openModal" class="inline-flex items-center gap-1.5 text-xs font-medium px-3 py-1.5 rounded-lg border border-gray-300 text-gray-600 bg-white hover:bg-gray-50 hover:border-gray-400 transition-all">
                    New Tag
                </button>
                <button type="button" wire:click="saveTags" wire:loading.attr="disabled" wire:target="saveTags" class="inline-flex items-center gap-1.5 text-xs font-medium px-3 py-1.5 rounded-lg bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 transition-all shadow-sm">
                    Save Tags
                </button>
            </div>
        </div>

        @if($successMessage)
            <div class="flex items-center gap-2 mx-5 mt-4 px-3 py-2.5 bg-green-50 border border-green-200 rounded-lg text-green-700 text-xs">
                {{ $successMessage }}
            </div>
        @endif

        <div class="p-5 space-y-5">
            <div>
                <div class="flex items-center gap-2 mb-2.5">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Selected</span>
                    @if(count($selectedTags))
                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-blue-100 text-blue-700 text-[10px] font-bold">{{ count($selectedTags) }}</span>
                    @endif
                </div>
                <div class="min-h-[42px] flex flex-wrap gap-2 p-3 bg-gray-50 border border-gray-200 rounded-lg">
                    @forelse($selectedTags as $tagName)
                        <span wire:key="selected-{{ $tagName }}" class="inline-flex items-center gap-1.5 bg-blue-600 text-white text-xs font-medium px-3 py-1 rounded-full shadow-sm">
                            {{ $tagName }}
                            <button type="button" wire:click="toggleTag('{{ $tagName }}')" class="flex items-center justify-center w-4 h-4 rounded-full bg-blue-500 hover:bg-blue-400 transition-colors text-white leading-none" aria-label="Remove tag">&times;</button>
                        </span>
                    @empty
                        <span class="text-xs text-gray-400 italic self-center">No tags selected — click a tag below to add it</span>
                    @endforelse
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between mb-2.5">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Available Tags</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2 overflow-y-auto pr-0.5" style="max-height: 340px;">
                    @php $availableTags = $tags->reject(fn($tag) => in_array($tag->tag_name, $selectedTags, true)); @endphp
                    @forelse($availableTags as $tag)
                        <button type="button" wire:key="available-{{ $tag->id }}" wire:click="toggleTag('{{ $tag->tag_name }}')" class="group flex items-center gap-2 border border-gray-200 rounded-lg px-3 py-2 text-xs text-gray-600 text-left bg-white hover:bg-blue-50 hover:border-blue-300 hover:text-blue-700 transition-all cursor-pointer">
                            {{ $tag->tag_name }}
                        </button>
                    @empty
                        <div class="col-span-3 flex flex-col items-center justify-center py-8 text-gray-400">
                            <span class="text-xs">All available tags are selected</span>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    @if($showModal)
        <div class="modal modal-mask" style="position: fixed; z-index: 9998; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,.45);">
            <div class="modal-wrapper px-4" style="display: table-cell; vertical-align: middle;">
                <div class="modal-container w-full max-w-sm px-6 py-5 mx-auto" style="margin: 0 auto; background-color: #fff; border-radius: 16px; box-shadow: 0 2px 8px rgba(0,0,0,.33);">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-sm font-semibold text-gray-800">Add Tag to Student</h2>
                        <button type="button" wire:click="closeModal" class="text-gray-400 hover:text-gray-600">&times;</button>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Select Existing Tag</label>
                            <select wire:model="tagName" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 text-gray-700 bg-white">
                                <option value="">— Choose a tag —</option>
                                @foreach($tags as $tag)
                                    <option value="{{ $tag->tag_name }}">{{ $tag->tag_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="text-center text-xs text-gray-400">OR</div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Create a New Tag</label>
                            <input type="text" wire:model="newTagName" placeholder="e.g. Needs Extra Support" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 text-gray-700">
                        </div>

                        @error('tagName') <p class="text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center justify-end gap-2 mt-5">
                        <button type="button" wire:click="closeModal" class="text-xs font-medium px-4 py-2 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-100">Cancel</button>
                        <button type="button" wire:click="submitTag" class="text-xs font-medium px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Save Tag</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
