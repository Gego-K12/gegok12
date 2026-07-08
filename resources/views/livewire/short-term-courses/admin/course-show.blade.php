<div>
    @if (session('stc-success'))
        <div class="bg-green-100 text-green-800 text-sm px-3 py-2 rounded mb-3">{{ session('stc-success') }}</div>
    @endif
    @if (session('stc-error'))
        <div class="bg-red-100 text-red-800 text-sm px-3 py-2 rounded mb-3">{{ session('stc-error') }}</div>
    @endif

    <a href="{{ url('/admin/short-term-courses') }}" class="text-xs text-blue-600">&larr; Back to courses</a>

    {{-- Head summary --}}
    <div class="bg-white shadow px-4 py-4 mt-2 mb-4 flex items-start gap-4">
        @if ($course->banner_image)
            <img src="{{ Storage::url($course->banner_image) }}" class="w-20 h-20 object-cover rounded">
        @else
            <div class="w-20 h-20 rounded bg-gray-200 flex items-center justify-center text-gray-400 text-xs">No image</div>
        @endif

        <div class="flex-1">
            <div class="flex items-center gap-2">
                <h1 class="admin-h1 mb-0">{{ $course->name }}</h1>
                <span class="text-xs px-2 py-0.5 rounded {{ $course->status === 'retired' ? 'bg-gray-200 text-gray-600' : 'bg-green-100 text-green-700' }}">
                    {{ $course->status }}
                </span>
            </div>
            <p class="text-sm text-gray-500 mt-1">
                {{ $course->category->name ?? '' }}
                &middot; {{ $course->duration_value }} {{ $course->duration_unit }}
                &middot; {{ ucfirst(str_replace('_', ' ', $course->mode)) }}
                &middot; {{ $course->batches_count }} batch(es)
            </p>
            <p class="text-sm text-gray-600 mt-1">
                Primary Incharge: <strong>{{ $course->primaryIncharge->FullName ?? $course->primaryIncharge->name ?? '—' }}</strong>
            </p>
        </div>

        @if ($course->status !== 'retired')
            <button type="button" wire:click="retireCourse" wire:confirm="Retire this course?" class="text-xs text-gray-500 border rounded px-3 py-1 self-start">
                Retire Course
            </button>
        @endif
    </div>

    {{-- Tabs --}}
    <div class="bg-white shadow">
        <div class="flex border-b text-sm">
            @foreach (['info' => 'Info', 'batches' => 'Batches', 'students' => 'Students', 'invites' => 'Invites'] as $key => $label)
                <button type="button" wire:click="setTab('{{ $key }}')"
                    class="px-4 py-2 {{ $activeTab === $key ? 'border-b-2 border-blue-600 text-blue-600 font-semibold' : 'text-gray-500' }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>

        <div class="p-4">
            @if ($activeTab === 'info')
                <dl class="grid grid-cols-2 gap-x-6 gap-y-3 text-sm">
                    <div>
                        <dt class="text-xs text-gray-500">Category</dt>
                        <dd>{{ $course->category->name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Duration</dt>
                        <dd>{{ $course->duration_value }} {{ $course->duration_unit }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Mode</dt>
                        <dd>{{ ucfirst(str_replace('_', ' ', $course->mode)) }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Primary Incharge</dt>
                        <dd>{{ $course->primaryIncharge->FullName ?? $course->primaryIncharge->name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Eligible Classes</dt>
                        <dd>
                            @forelse ($course->eligibleStandards as $standard)
                                <span class="inline-block bg-gray-100 text-xs px-2 py-0.5 rounded mr-1">{{ $standard->name }}</span>
                            @empty
                                All classes
                            @endforelse
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Prerequisite Course(s)</dt>
                        <dd>
                            @forelse ($course->prerequisiteCourses as $prerequisite)
                                <span class="inline-block bg-gray-100 text-xs px-2 py-0.5 rounded mr-1">{{ $prerequisite->name }}</span>
                            @empty
                                None
                            @endforelse
                        </dd>
                    </div>
                    <div class="col-span-2">
                        <dt class="text-xs text-gray-500">Description</dt>
                        <dd class="prose prose-sm max-w-none">{!! $course->description ?: '—' !!}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Created By</dt>
                        <dd>{{ $course->createdBy->FullName ?? $course->createdBy->name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Created On</dt>
                        <dd>{{ $course->created_at?->format('d M Y') }}</dd>
                    </div>
                </dl>
            @elseif ($activeTab === 'batches')
                <livewire:short-term-courses.admin.batch-manager :course-id="$course->id" :key="'batch-manager-'.$course->id" />
            @elseif ($activeTab === 'students')
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs text-gray-500 border-b">
                            <th class="py-2">Student</th>
                            <th>Batch</th>
                            <th>Status</th>
                            <th>Enrolled On</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($enrollments as $enrollment)
                            <tr class="border-b">
                                <td class="py-2">{{ $enrollment->student->FullName ?? $enrollment->student->name ?? '' }}</td>
                                <td>{{ $enrollment->batch->name ?? '' }}</td>
                                <td>{{ $enrollment->status }}</td>
                                <td>{{ $enrollment->enrolled_on?->format('d M Y') ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-sm text-gray-500 py-3">No students enrolled yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            @elseif ($activeTab === 'invites')
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs text-gray-500 border-b">
                            <th class="py-2">Student</th>
                            <th>Batch</th>
                            <th>Status</th>
                            <th>Invited By</th>
                            <th>Expires</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($invitations as $invitation)
                            <tr class="border-b">
                                <td class="py-2">{{ $invitation->student->FullName ?? $invitation->student->name ?? '' }}</td>
                                <td>{{ $invitation->batch->name ?? '' }}</td>
                                <td>{{ $invitation->status }}</td>
                                <td>{{ $invitation->invitedBy->FullName ?? $invitation->invitedBy->name ?? '' }}</td>
                                <td>{{ $invitation->expires_at?->format('d M Y') ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-sm text-gray-500 py-3">No invitations sent yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
