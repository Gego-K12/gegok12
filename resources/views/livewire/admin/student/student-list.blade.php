<div>
    <div class="flex flex-col justify-between my-3">
        <h1 class="admin-h1 mb-3 font-bold font-exo">Students ( {{ $students->total() }} )</h1>

        <div class="bg-white p-2 flex flex-wrap items-center justify-between gap-2 staff-toolbar">
            <div class="flex items-center" style="width: 42%;">
                <input
                    type="text"
                    wire:model.live.debounce.500ms="search"
                    placeholder="Search query"
                    class="tw-form-control w-full text-sm"
                >
            </div>

            <div class="flex items-center gap-2">
                <select wire:model.live="standardId" class="alphabet-select">
                    <option value="">All Classes</option>
                    @foreach($standardLinks as $standardLink)
                        <option value="{{ $standardLink['id'] }}">{{ $standardLink['standard_section'] }}</option>
                    @endforeach
                </select>

                <select wire:model.live="letter" class="alphabet-select">
                    <option value="">A - Z</option>
                    @foreach($alphabets as $alphabet)
                        <option value="{{ $alphabet }}">{{ $alphabet }}</option>
                    @endforeach
                </select>

                <a href="#" wire:click.prevent="clearFilters" class="clear-btn">Clear</a>
            </div>
        </div>
    </div>

    <div class="bg-white p-2 flex flex-wrap my-2">

        @if (session()->has('success'))
            <div class="alert alert-success w-full">{{ session('success') }}</div>
        @endif

        @if($letter && $students->isEmpty())
            <div class="no-names-message w-full">
                <i class="fa-solid fa-circle-info"></i> No students found for the letter "{{ $letter }}".
            </div>
        @endif

        <div class="w-full" x-data="studentBulkActions">
            @if(count($selected) > 0)
            <div class="flex flex-wrap items-center gap-2 w-full my-2">
                <span class="text-sm text-gray-600 mr-2">{{ count($selected) }} students selected</span>
                <a href="#" class="bulk-action-btn bulk-action-btn--green" x-on:click.prevent="openTag()">Add Tag</a>
                <a href="#" class="bulk-action-btn bulk-action-btn--blue" x-on:click.prevent="openMessage()">Send Message</a>
                <a href="#" class="bulk-action-btn bulk-action-btn--blue" x-on:click.prevent="openShift()">Shift</a>
                <a href="#" class="bulk-action-btn bulk-action-btn--green" x-on:click.prevent="openGroup()">Add Group</a>
                @if($feeEnabled)
                <a href="#" class="bulk-action-btn bulk-action-btn--green" x-on:click.prevent="openFees()">Add Fees Details</a>
                @endif
            </div>
            @endif

            {{-- Add Tag modal --}}
            <div x-show="modal === 'tag'" x-cloak class="modal modal-mask">
                <div class="modal-wrapper px-4">
                    <div class="modal-container w-full max-w-md px-8 mx-auto">
                        <div class="modal-header flex justify-between items-center">
                            <h2>Add Tag To Students</h2>
                            <button type="button" class="modal-default-button text-2xl py-1" x-on:click="close()">&times;</button>
                        </div>
                        <div class="modal-body">
                            <label class="tw-form-label">Existing Tag</label>
                            <select x-model="tagName" class="tw-form-control w-full">
                                <option value="">Select an existing tag (optional)</option>
                                <template x-for="tag in tags" x-bind:key="tag.id">
                                    <option x-bind:value="tag.tag_name ?? tag.name" x-text="tag.tag_name ?? tag.name"></option>
                                </template>
                            </select>
                        </div>
                        <div class="modal-body">
                            <label class="tw-form-label">Or Type A New Tag</label>
                            <input type="text" x-model="tagName" class="tw-form-control w-full" placeholder="New tag name">
                            <span class="text-red-500 text-xs font-semibold" x-show="errors.tag_name" x-text="errors.tag_name ? errors.tag_name[0] : ''"></span>
                        </div>
                        <div class="my-6">
                            <a href="#" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" x-on:click.prevent="submitTag()">Submit</a>
                            <a href="#" class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 mr-3 text-sm font-medium" x-on:click.prevent="close()">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Send Message modal --}}
            <div x-show="modal === 'message'" x-cloak class="modal modal-mask">
                <div class="modal-wrapper px-4">
                    <div class="modal-container w-full max-w-md px-8 mx-auto">
                        <div class="modal-header flex justify-between items-center">
                            <h2>Send Message</h2>
                            <button type="button" class="modal-default-button text-2xl py-1" x-on:click="close()">&times;</button>
                        </div>
                        <div class="modal-body">
                            <label class="tw-form-label">Subject</label>
                            <input type="text" x-model="subject" class="tw-form-control w-full">
                            <span class="text-red-500 text-xs font-semibold" x-show="errors.subject" x-text="errors.subject ? errors.subject[0] : ''"></span>
                        </div>
                        <div class="modal-body">
                            <label class="tw-form-label">Message</label>
                            <textarea x-model="message" class="tw-form-control w-full" rows="6"></textarea>
                            <span class="text-red-500 text-xs font-semibold" x-show="errors.message" x-text="errors.message ? errors.message[0] : ''"></span>
                        </div>
                        <div class="modal-body">
                            <label class="tw-form-label">
                                <input type="checkbox" x-model="sendLater"> Send Later
                            </label>
                        </div>
                        <div class="modal-body" x-show="sendLater">
                            <label class="tw-form-label">Date Time</label>
                            <input type="datetime-local" x-model="executedAt" class="tw-form-control w-full">
                        </div>
                        <div class="my-6">
                            <a href="#" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" x-on:click.prevent="submitMessage()">Send</a>
                            <a href="#" class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 mr-3 text-sm font-medium" x-on:click.prevent="close()">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Shift modal --}}
            <div x-show="modal === 'shift'" x-cloak class="modal modal-mask">
                <div class="modal-wrapper px-4">
                    <div class="modal-container w-full max-w-md px-8 mx-auto">
                        <div class="modal-header flex justify-between items-center">
                            <h2>Shift Students</h2>
                            <button type="button" class="modal-default-button text-2xl py-1" x-on:click="close()">&times;</button>
                        </div>
                        <div class="modal-body">
                            <label class="tw-form-label">Select Standard</label>
                            <select x-model="shiftStd" class="tw-form-control w-full">
                                <option value="">Select</option>
                                @foreach($standardLinks as $standardLink)
                                    <option value="{{ $standardLink['id'] }}">{{ $standardLink['standard_section'] }}</option>
                                @endforeach
                            </select>
                            <span class="text-red-500 text-xs font-semibold" x-show="errors.shift_std" x-text="errors.shift_std ? errors.shift_std[0] : ''"></span>
                        </div>
                        <div class="my-6">
                            <a href="#" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" x-on:click.prevent="submitShift()">Shift</a>
                            <a href="#" class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 mr-3 text-sm font-medium" x-on:click.prevent="close()">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Add Group modal --}}
            <div x-show="modal === 'group'" x-cloak class="modal modal-mask">
                <div class="modal-wrapper px-4">
                    <div class="modal-container w-full max-w-md px-8 mx-auto">
                        <div class="modal-header flex justify-between items-center">
                            <h2>Add Students To Group</h2>
                            <button type="button" class="modal-default-button text-2xl py-1" x-on:click="close()">&times;</button>
                        </div>
                        <div class="modal-body">
                            <label class="tw-form-label">Select Group</label>
                            <select x-model="groupId" class="tw-form-control w-full">
                                <option value="">Select</option>
                                <template x-for="group in groups" x-bind:key="group.id">
                                    <option x-bind:value="group.id" x-text="group.group_name"></option>
                                </template>
                            </select>
                            <span class="text-red-500 text-xs font-semibold" x-show="errors.group_id" x-text="errors.group_id ? errors.group_id[0] : ''"></span>
                        </div>
                        <div class="my-6">
                            <a href="#" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" x-on:click.prevent="submitGroup()">Submit</a>
                            <a href="#" class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 mr-3 text-sm font-medium" x-on:click.prevent="close()">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>

            @if($feeEnabled)
            {{-- Add Fees Details modal --}}
            <div x-show="modal === 'fees'" x-cloak class="modal modal-mask">
                <div class="modal-wrapper px-4">
                    <div class="modal-container w-full max-w-md px-8 mx-auto">
                        <div class="modal-header flex justify-between items-center">
                            <h2>Fees Payment Detail</h2>
                            <button type="button" class="modal-default-button text-2xl py-1" x-on:click="close()">&times;</button>
                        </div>
                        <div class="modal-body">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b">
                                        <th class="text-left text-sm px-2 py-2"></th>
                                        <th class="text-left text-sm px-2 py-2">Title</th>
                                        <th class="text-left text-sm px-2 py-2">Term</th>
                                        <th class="text-left text-sm px-2 py-2">Amount</th>
                                        <th class="text-left text-sm px-2 py-2">Due Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="fee in feeList" x-bind:key="fee.id">
                                        <tr class="border-b">
                                            <td class="py-3 px-2">
                                                <input type="radio" x-bind:value="fee.id" x-model="feeId" x-on:click="selectFee(fee)">
                                            </td>
                                            <td class="py-3 px-2"><p class="font-semibold text-xs" x-text="fee.name"></p></td>
                                            <td class="py-3 px-2"><p class="font-semibold text-xs" x-text="fee.term"></p></td>
                                            <td class="py-3 px-2"><p class="font-semibold text-xs" x-text="fee.amount"></p></td>
                                            <td class="py-3 px-2"><p class="font-semibold text-xs" x-text="fee.end_date"></p></td>
                                        </tr>
                                    </template>
                                    <tr x-show="feeList.length === 0">
                                        <td colspan="5" class="text-center py-3">No Records Found</td>
                                    </tr>
                                </tbody>
                            </table>
                            <span class="text-red-500 text-xs font-semibold" x-show="errors.fee_id" x-text="errors.fee_id ? errors.fee_id[0] : ''"></span>
                        </div>
                        <div class="modal-body" x-show="feeId">
                            <label class="tw-form-label">Paid On</label>
                            <input type="date" x-model="paidOn" class="tw-form-control w-full">
                            <span class="text-red-500 text-xs font-semibold" x-show="errors.paid_on" x-text="errors.paid_on ? errors.paid_on[0] : ''"></span>
                        </div>
                        <div class="modal-body" x-show="feeId">
                            <label class="tw-form-label">
                                <input type="checkbox" x-model="notifyParent"> Notify Parent
                            </label>
                        </div>
                        <div class="my-6">
                            <a href="#" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" x-on:click.prevent="submitFees()">Submit</a>
                            <a href="#" class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 mr-3 text-sm font-medium" x-on:click.prevent="close()">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="w-full overflow-x-auto student-table-wrap">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-left text-sm">
                        <th class="border px-2 py-2 w-10">
                            <input type="checkbox" wire:model.live="selectPage">
                        </th>
                        <th class="border px-2 py-2 cursor-pointer" wire:click="sortBy('firstname')">
                            Name
                            @if($sortField === 'firstname')
                                <i class="fa-solid fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                            @endif
                        </th>
                        <th class="border px-2 py-2">Class</th>
                        <th class="border px-2 py-2 cursor-pointer" wire:click="sortBy('status')">
                            Status
                            @if($sortField === 'status')
                                <i class="fa-solid fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                            @endif
                        </th>
                        @if($birthday)
                        <th class="border px-2 py-2">Date of Birth</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                    <tr wire:key="student-{{ $student->id }}">
                        <td class="border px-2 py-2">
                            @if($student->status === 'active')
                            <input
                                type="checkbox"
                                class="student-row-checkbox"
                                wire:model.live="selected"
                                value="{{ $student->id }}"
                                data-parents="{{ $student->parents->pluck('userParent.id')->filter()->implode(',') }}"
                            >
                            @endif
                        </td>
                        <td class="border px-2 py-2">
                            <a href="{{ url('/admin/student/show/'.$student->name) }}" class="flex items-center no-underline text-gray-800 hover:text-blue-600">
                                <img src="{{ optional($student->userprofile)->AvatarPath }}" class="w-10 h-10 rounded-full mr-2">
                                <span class="font-semibold">{{ $student->FullName }}</span>
                            </a>
                        </td>
                        <td class="border px-2 py-2">
                            {{ optional(optional($student->studentAcademicLatest)->standardLink)->StandardSection ?? '—' }}
                        </td>
                        <td class="border px-2 py-2">
                            <span class="rounded-full px-2 py-1 text-xs font-semibold {{ $student->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600' }}">
                                {{ ucfirst($student->status) }}
                            </span>
                        </td>
                        @if($birthday)
                        <td class="border px-2 py-2">
                            {{ optional($student->userprofile)->date_of_birth ? \Carbon\Carbon::parse($student->userprofile->date_of_birth)->format('d M Y') : '—' }}
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ $birthday ? 5 : 4 }}" class="border px-2 py-4 text-center text-gray-500">No students found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="w-full mt-3">
            {{ $students->links() }}
        </div>
    </div>
</div>

@script
<script>
    Alpine.data('studentBulkActions', () => ({
        modal: null,
        errors: {},

        tagName: '',
        tags: [],

        subject: '',
        message: '',
        sendLater: false,
        executedAt: '',

        shiftStd: '',

        groupId: '',
        groups: [],

        standardLinkId: '',
        feeId: '',
        feeList: [],
        paidOn: '',
        notifyParent: false,

        selectedStudentIds() {
            return Array.from(document.querySelectorAll('.student-row-checkbox:checked')).map(el => parseInt(el.value));
        },

        selectedParentIds() {
            const ids = [];
            document.querySelectorAll('.student-row-checkbox:checked').forEach(el => {
                const raw = el.dataset.parents;
                if (raw) {
                    raw.split(',').forEach(id => { if (id) ids.push(parseInt(id)); });
                }
            });
            return ids;
        },

        close() {
            this.modal = null;
            this.errors = {};
        },

        openTag() {
            this.modal = 'tag';
            this.errors = {};
            this.tagName = '';
            axios.get('/admin/student-tags').then(r => { this.tags = r.data.tags ?? []; });
        },

        openMessage() {
            this.modal = 'message';
            this.errors = {};
            this.subject = '';
            this.message = '';
            this.sendLater = false;
            this.executedAt = '';
        },

        openShift() {
            this.modal = 'shift';
            this.errors = {};
            this.shiftStd = '';
        },

        openGroup() {
            this.modal = 'group';
            this.errors = {};
            this.groupId = '';
            axios.get('/admin/grouplist').then(r => { this.groups = r.data.data ?? []; });
        },

        openFees() {
            this.modal = 'fees';
            this.errors = {};
            this.feeId = '';
            this.standardLinkId = '';
            this.paidOn = '';
            this.notifyParent = false;
            axios.get('/admin/feedetails/list').then(r => { this.feeList = r.data.data ?? []; });
        },

        selectFee(fee) {
            this.standardLinkId = fee.standardLink_id;
        },

        submitTag() {
            axios.post('/admin/tags/add-students', {
                tag_name: this.tagName,
                selectedUsers: this.selectedStudentIds(),
            }).then(() => window.location.reload())
              .catch(e => { this.errors = e.response?.data?.errors || {}; });
        },

        submitMessage() {
            axios.post('/admin/student/sendMessageToAll', {
                selected: this.selectedParentIds(),
                selectedUsers: this.selectedStudentIds(),
                subject: this.subject,
                message: this.message,
                send_later: this.sendLater,
                executed_at: this.executedAt,
            }).then(() => window.location.reload())
              .catch(e => { this.errors = e.response?.data?.errors || {}; });
        },

        submitShift() {
            axios.post('/admin/student/shift', {
                selectedUsers: this.selectedStudentIds(),
                shift_std: this.shiftStd,
            }).then(() => window.location.reload())
              .catch(e => { this.errors = e.response?.data?.errors || {}; });
        },

        submitGroup() {
            axios.post('/admin/groups/add-members', {
                group_id: this.groupId,
                selectedUsers: this.selectedStudentIds(),
            }).then(() => window.location.reload())
              .catch(e => { this.errors = e.response?.data?.errors || {}; });
        },

        submitFees() {
            axios.post('/admin/feedetail/add', {
                selectedUsers: this.selectedStudentIds(),
                standardLink_id: this.standardLinkId,
                fee_id: this.feeId,
                paid_on: this.paidOn,
                notify_parent: this.notifyParent,
            }).then(() => window.location.reload())
              .catch(e => { this.errors = e.response?.data?.errors || {}; });
        },
    }));
</script>
@endscript
