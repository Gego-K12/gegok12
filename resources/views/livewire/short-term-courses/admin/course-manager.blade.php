<div>
    @if (session('stc-success'))
        <div class="bg-green-100 text-green-800 text-sm px-3 py-2 rounded mb-3">{{ session('stc-success') }}</div>
    @endif
    @if (session('stc-error'))
        <div class="bg-red-100 text-red-800 text-sm px-3 py-2 rounded mb-3">{{ session('stc-error') }}</div>
    @endif

    <div class="bg-white shadow px-4 py-3">
        <div class="flex items-center justify-between mb-3">
            <h2 class="font-semibold text-base text-gray-700">Courses</h2>
            <button type="button" wire:click="openCreateModal" class="blue-bg text-sm text-white px-3 py-1 rounded">
                + Add New Course
            </button>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 border-b">
                    <th class="py-2">Name</th>
                    <th>Category</th>
                    <th>Duration</th>
                    <th>Primary Incharge</th>
                    <th>Batches</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($courses as $course)
                    <tr class="border-b">
                        <td class="py-2">
                            @if ($course->banner_image)
                                <img src="{{ Storage::url($course->banner_image) }}" class="inline-block w-8 h-8 object-cover rounded mr-2 align-middle">
                            @endif
                            <a href="{{ url('/admin/short-term-courses/courses/'.$course->id) }}" class="text-blue-600 hover:underline">{{ $course->name }}</a>
                        </td>
                        <td>{{ $course->category->name ?? '' }}</td>
                        <td>{{ $course->duration_value }} {{ $course->duration_unit }}</td>
                        <td>{{ $course->primaryIncharge->FullName ?? $course->primaryIncharge->name ?? '—' }}</td>
                        <td>{{ $course->batches_count }}</td>
                        <td>{{ $course->status }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-sm text-gray-500 py-3">No courses yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($showCreateModal)
        <div class="fixed inset-0 z-50 flex items-start justify-center overflow-y-auto bg-black bg-opacity-50 py-8"
             x-data="{
                editor: null,
                async submitCourse() {
                    if (this.editor) {
                        await $wire.set('description', this.editor.getData());
                    }
                    await $wire.createCourse();
                }
             }">
            <div class="bg-white rounded shadow-lg w-full max-w-3xl p-5">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-semibold text-lg text-gray-800">New Course</h2>
                    <button type="button" wire:click="closeCreateModal" class="text-gray-400 hover:text-gray-700 text-xl leading-none">&times;</button>
                </div>

                <form @submit.prevent="submitCourse">
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Category</label>
                            <select wire:model="category_id" class="tw-form-control text-sm w-full">
                                <option value="">Select category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Name</label>
                            <input type="text" wire:model="name" class="tw-form-control text-sm w-full">
                            @error('name') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-3 mb-3">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Duration</label>
                            <input type="number" wire:model="duration_value" min="1" class="tw-form-control text-sm w-full">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Unit</label>
                            <select wire:model="duration_unit" class="tw-form-control text-sm w-full">
                                <option value="sessions">Sessions</option>
                                <option value="weeks">Weeks</option>
                                <option value="months">Months</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Mode</label>
                            <select wire:model="mode" class="tw-form-control text-sm w-full">
                                <option value="in_person">In person</option>
                                <option value="online">Online</option>
                                <option value="hybrid">Hybrid</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="block text-xs text-gray-600 mb-1">Cover Image</label>
                        <input type="file" wire:model="cover_image" accept="image/*" class="text-sm">
                        <div wire:loading wire:target="cover_image" class="text-xs text-gray-500">Uploading...</div>
                        @if ($cover_image)
                            <img src="{{ $cover_image->temporaryUrl() }}" class="mt-2 h-20 rounded">
                        @endif
                        @error('cover_image') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="block text-xs text-gray-600 mb-1">Description</label>
                        <div wire:ignore x-init="
                            editor = await ClassicEditor.create($refs.stcDescriptionEditor, {
                                toolbar: ['bold', 'italic', 'bulletedList', 'numberedList', 'link', 'undo', 'redo'],
                            });
                            editor.setData(@js($description ?? ''));
                        ">
                            <textarea x-ref="stcDescriptionEditor" rows="4"></textarea>
                        </div>
                        @error('description') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Who can join — eligible classes</label>
                            <p class="text-xs text-gray-400 mb-1">Leave all unchecked to allow every class.</p>
                            <div class="flex flex-wrap gap-2 border rounded p-2 max-h-28 overflow-y-auto">
                                @foreach ($standards as $standard)
                                    <label class="flex items-center gap-1 text-xs">
                                        <input type="checkbox" wire:model="eligible_standard_ids" value="{{ $standard->id }}">
                                        {{ $standard->name }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Prerequisite course(s)</label>
                            <p class="text-xs text-gray-400 mb-1">Optional — e.g. "Spoken Hindi L1" for "Spoken Hindi L2".</p>
                            <select wire:model="prerequisite_course_ids" multiple class="tw-form-control text-xs w-full" style="height:6.5rem">
                                @foreach ($eligibleCourses as $eligibleCourse)
                                    <option value="{{ $eligibleCourse->id }}">{{ $eligibleCourse->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs text-gray-600 mb-1">Primary Incharge (Teacher or Non-Teaching Staff)</label>
                        <select wire:model="primary_incharge_user_id" class="tw-form-control text-sm w-full">
                            <option value="">Select a person</option>
                            @foreach ($staffOptions as $staff)
                                <option value="{{ $staff->id }}">{{ $staff->FullName ?? $staff->name }}</option>
                            @endforeach
                        </select>
                        @error('primary_incharge_user_id') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" wire:click="closeCreateModal" class="text-sm text-gray-600 px-3 py-1">Cancel</button>
                        <button type="submit" class="blue-bg text-sm text-white px-4 py-1 rounded" wire:loading.attr="disabled" wire:target="createCourse">
                            Create Course
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
