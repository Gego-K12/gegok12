<div class="relative">
    @if (session()->has('success'))
        <div class="alert alert-success mt-2">{{ session('success') }}</div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-danger mt-2">{{ session('error') }}</div>
    @endif

    <div class="flex flex-wrap items-center justify-between my-3">
        <h1 class="admin-h1 mb-3 font-bold font-exo">Teaching Staff ( {{ $totalStaffCount }} )</h1>

        <div class="relative flex items-center">
            <a href="{{ url('/admin/teacher/add') }}" class="no-underline text-white px-4 my-3 mx-1 flex items-center custom-green py-1 justify-center">
                <span class="mx-1 text-sm font-semibold">+ Add Teaching Staff</span>
            </a>

            <div class="relative">
                <button type="button" class="action-menu-toggle bg-gray-100 hover:bg-gray-200 rounded-full w-9 h-9 flex items-center justify-center" aria-label="More actions">
                    <i class="fa-solid fa-ellipsis-vertical text-gray-600"></i>
                </button>
                <ul class="action-menu-dropdown hidden list-reset absolute right-0 top-full mt-1 w-44 bg-white shadow-lg rounded z-20 py-1">
                    <li>
                        <button type="button" wire:click="openExportModal" class="w-full text-left no-underline flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fa-solid fa-file-export w-4 text-center mr-2 text-gray-500"></i> Export
                        </button>
                    </li>
                    <li>
                        <a href="{{ url('/admin/import/teacher') }}" class="no-underline flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fa-solid fa-file-import w-4 text-center mr-2 text-gray-500"></i> Import
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/admin/teacher/id-card') }}" class="no-underline flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fa-solid fa-id-card w-4 text-center mr-2 text-gray-500"></i> Id Card
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/admin/attendance/staff/add') }}" class="no-underline flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fa-solid fa-calendar-check w-4 text-center mr-2 text-gray-500"></i> Attendance
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="my-4 flex flex-wrap items-center justify-between gap-2">
        <ul class="list-reset flex gap-2">
            <li>
                <button type="button" wire:click="$set('view', 'current')" class="px-5 py-2 text-sm font-semibold rounded-full {{ $view === 'current' ? 'bg-red-600 text-white' : 'text-gray-500 hover:text-gray-700' }}">Current Staff</button>
            </li>
            <li>
                <button type="button" wire:click="$set('view', 'exit')" class="px-5 py-2 text-sm font-semibold rounded-full {{ $view === 'exit' ? 'bg-red-600 text-white' : 'text-gray-500 hover:text-gray-700' }}">Relieved Staff</button>
            </li>
        </ul>

        <div class="flex items-center gap-2">
            <button type="button" wire:click="$toggle('showFilters')" class="text-sm font-semibold border rounded px-3 py-2 flex items-center gap-2 {{ $showFilters ? 'bg-gray-100 text-gray-800' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fa-solid fa-filter"></i> Filter
            </button>
            <select wire:model.live="alphabet" class="tw-form-control text-sm">
                <option value="">A - Z</option>
                @foreach (range('A', 'Z') as $letter)
                    <option value="{{ $letter }}">{{ $letter }}</option>
                @endforeach
            </select>
            <button type="button" wire:click="resetFilters" class="text-sm font-semibold text-red-600 hover:underline">Clear</button>
        </div>
    </div>

    @if ($alphabet && $teachers->total() === 0)
        <div class="my-3 px-4 py-3 text-sm text-amber-800 bg-amber-50 border border-amber-200 rounded flex items-center gap-2">
            <i class="fa-solid fa-circle-info"></i> No staff found for the letter "{{ $alphabet }}".
        </div>
    @endif

    @if ($showFilters)
    <div class="bg-white shadow px-4 py-3 my-3">
        <div class="flex justify-between items-center mb-2">
            <h3 class="text-sm font-semibold text-gray-700">Filter</h3>
            <button type="button" wire:click="$toggle('showFilters')" class="text-gray-400 hover:text-gray-600 text-xl leading-none" aria-label="Hide filter">&times;</button>
        </div>
        <div class="flex flex-wrap items-end gap-3">
            <div>
                <label class="tw-form-label text-xs">First Name</label>
                <input type="text" wire:model.live.debounce.400ms="firstname" class="tw-form-control" placeholder="First Name">
            </div>
            <div>
                <label class="tw-form-label text-xs">Last Name</label>
                <input type="text" wire:model.live.debounce.400ms="lastname" class="tw-form-control" placeholder="Last Name">
            </div>
            <div>
                <label class="tw-form-label text-xs">Phone</label>
                <input type="text" wire:model.live.debounce.400ms="mobileNo" class="tw-form-control" placeholder="Phone">
            </div>
            <div>
                <label class="tw-form-label text-xs">Email</label>
                <input type="text" wire:model.live.debounce.400ms="email" class="tw-form-control" placeholder="Email">
            </div>
            <div>
                <label class="tw-form-label text-xs">Additional Certificates</label>
                <select wire:model.live="qualification" class="tw-form-control">
                    <option value="">All</option>
                    @foreach ($qualifications as $qualificationOption)
                        <option value="{{ $qualificationOption->id }}">{{ $qualificationOption->display_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="tw-form-label text-xs">Designation</label>
                <select wire:model.live="designation" class="tw-form-control">
                    <option value="">All</option>
                    @foreach ($designations as $designationOption)
                        <option value="{{ $designationOption['id'] }}">{{ $designationOption['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="tw-form-label text-xs">Gender</label>
                <select wire:model.live="gender" class="tw-form-control">
                    <option value="">All</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
            </div>
            <div>
                <label class="tw-form-label text-xs">Blood Group</label>
                <select wire:model.live="bloodGroup" class="tw-form-control">
                    <option value="">All</option>
                    @foreach ($bloodGroups as $bloodGroupOption)
                        <option value="{{ $bloodGroupOption['num'] }}">{{ $bloodGroupOption['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="tw-form-label text-xs">Marital Status</label>
                <select wire:model.live="maritalStatus" class="tw-form-control">
                    <option value="">All</option>
                    @foreach ($maritalOptions as $maritalOption)
                        <option value="{{ $maritalOption['id'] }}">{{ $maritalOption['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="tw-form-label text-xs">Birthday</label>
                <select wire:model.live="dateOfBirthMonth" class="tw-form-control">
                    <option value="">Month</option>
                    @foreach (['01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April', '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August', '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'] as $monthId => $monthName)
                        <option value="{{ $monthId }}">{{ $monthName }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="tw-form-label text-xs">Job Type</label>
                <select wire:model.live="jobType" class="tw-form-control">
                    <option value="">All</option>
                    <option value="full_time">Full Time</option>
                    <option value="part_time">Part Time</option>
                </select>
            </div>
            <div>
                <button type="button" wire:click="resetFilters" class="text-sm border bg-gray-100 text-grey-darkest py-2 px-4">Reset</button>
            </div>
        </div>
    </div>
    @endif

    @if (count($selected) > 0)
        <div class="bg-amber-50 border border-amber-200 rounded p-3 my-3 flex items-center justify-between flex-wrap gap-2">
            <div class="text-sm text-amber-800">
                <span>{{ count($selected) }} of {{ $teachers->total() }} teacher(s) selected.</span>
                @if (count($selected) < $teachers->total())
                    <button type="button" wire:click="selectAllMatching" class="text-amber-600 font-semibold text-sm ml-2">Select all {{ $teachers->total() }} teachers</button>
                @endif
                <button type="button" wire:click="clearSelection" class="text-gray-500 font-semibold text-sm ml-2">Clear selection</button>
            </div>
            <button type="button" wire:click="openMessageModal" class="btn btn-submit blue-bg text-white rounded px-3 py-1 text-sm font-medium">Send Message</button>
        </div>
    @endif

    <div class="">
        <div class="flex flex-wrap custom-table my-3 overflow-auto">
            <table class="w-full">
                <thead class="bg-grey-light">
                    <tr class="border-b">
                        <th class="text-left text-sm px-2 py-2 text-grey-darker w-8"></th>
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">Name</th>
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">Designation</th>
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">Status</th>
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">Last Login</th>
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">Primary Role</th>
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">Subject Teacher to</th>
                        @if ($dateOfBirthMonth)
                            <th class="text-left text-sm px-2 py-2 text-grey-darker">Date of Birth</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($teachers as $teacher)
                        @php
                            $profile = $teacher->userprofile;
                            $teacherProfile = $teacher->latestTeacherProfile;
                            $subjectLinks = $teacher->teacherlink->filter(fn ($link) => $link->standardLink && $link->subject);
                        @endphp
                        <tr class="border-b" wire:key="teacher-{{ $teacher->id }}">
                            <td class="py-3 px-2 align-top">
                                <input type="checkbox" wire:model.live="selected" value="{{ $teacher->id }}">
                            </td>
                            <td class="py-3 px-2">
                                <div class="flex items-center">
                                    <img src="{{ optional($profile)->AvatarPath }}" class="w-10 h-10 rounded-full mr-2">
                                    <div>
                                        <a href="{{ url('/admin/teacher/show/'.$teacher->name) }}" class="font-semibold text-sm text-blue-700 hover:underline">
                                            {{ optional($profile)->gender === 'female' ? 'Ms.' : 'Mr.' }} {{ $teacher->FullName }}
                                        </a>
                                        @if (optional($teacherProfile)->employee_id)
                                            <p class="text-xs text-gray-500">Employee ID: {{ $teacherProfile->employee_id }}</p>
                                        @endif
                                        @if (optional($profile)->joining_date)
                                            <p class="text-xs text-gray-500">Date of Join: {{ \Carbon\Carbon::parse($profile->joining_date)->format('d M Y') }}</p>
                                        @endif
                                        @if (optional($profile)->relieved_at)
                                            <p class="text-xs text-gray-500">Date of Relieve: {{ \Carbon\Carbon::parse($profile->relieved_at)->format('d M Y') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-2 align-top">
                                <p class="text-sm">{{ optional($teacherProfile)->designation_name }}</p>
                            </td>
                            <td class="py-3 px-2 align-top">
                                <span class="rounded-full px-2 py-1 text-xs font-semibold {{ $teacher->status === 'exit' ? 'bg-red-100 text-red-700' : ($teacher->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600') }}">
                                    {{ $teacher->status === 'exit' ? 'Relieved' : ($teacher->status === 'active' ? 'Active' : 'Inactive') }}
                                </span>
                            </td>
                            <td class="py-3 px-2 align-top">
                                @if ($teacher->lastLogin)
                                    <span class="text-gray-700 text-sm">{{ $teacher->lastLogin->created_at->format('d M Y, h:i A') }}</span>
                                @else
                                    <span class="rounded-full px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-500">Never Logged In</span>
                                @endif
                            </td>
                            <td class="py-3 px-2 align-top">
                                @if ($teacher->standardLink)
                                    <span class="text-sm">Class Teacher to: {{ $teacher->standardLink->standard_section }}</span>
                                @else
                                    <span class="text-gray-400">&mdash;</span>
                                @endif
                            </td>
                            <td class="py-3 px-2 align-top">
                                @if ($subjectLinks->isNotEmpty())
                                    @foreach ($subjectLinks as $link)
                                        <div class="text-xs">{{ $link->standardLink->standard_section }} - {{ $link->subject->name }}</div>
                                    @endforeach
                                @else
                                    <span class="text-gray-400">&mdash;</span>
                                @endif
                            </td>
                            @if ($dateOfBirthMonth)
                                <td class="py-3 px-2 align-top">
                                    <p class="text-sm">{{ optional($profile)->date_of_birth ? \Carbon\Carbon::parse($profile->date_of_birth)->format('d M Y') : '-' }}</p>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr class="border-b">
                            <td colspan="{{ $dateOfBirthMonth ? 8 : 7 }}">
                                <p class="font-semibold text-s" style="text-align: center">No Records Found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $teachers->links() }}
        </div>
    </div>

    @if ($showMessageModal)
        <div class="modal modal-mask" style="position: fixed; z-index: 9998; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,.5); display: table;">
            <div class="modal-wrapper px-4" style="display: table-cell; vertical-align: middle;">
                <div class="modal-container w-full max-w-2xl px-8 mx-auto" style="margin: 0 auto; padding: 20px 30px; background-color: #fff; border-radius: 2px; box-shadow: 0 2px 8px rgba(0,0,0,.33);">
                    <div class="modal-header flex justify-between items-center mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fa-solid fa-paper-plane text-blue-600 text-lg"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-800">Send Message</h2>
                                <p class="text-sm text-gray-600">To {{ count($selected) }} selected teacher(s)</p>
                            </div>
                        </div>
                        <button type="button" class="text-gray-400 hover:text-gray-600 text-3xl leading-none" wire:click="closeMessageModal">&times;</button>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2 uppercase">Subject</label>
                            <input type="text" wire:model="subject" placeholder="Enter subject" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                            @error('subject') <span class="text-red-500 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2 uppercase">Message</label>
                            <textarea wire:model="message" placeholder="Type your message to the teachers..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" rows="8"></textarea>
                            @error('message') <span class="text-red-500 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="border border-gray-200 rounded-lg p-4">
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" wire:model.live="sendLater" class="mt-1">
                                <div>
                                    <span class="text-sm font-semibold text-gray-700">Send later</span>
                                    <p class="text-xs text-gray-600 mt-1">Schedule delivery for a specific date and time instead of sending now.</p>
                                </div>
                            </label>
                        </div>

                        @if ($sendLater)
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2 uppercase">Date & Time</label>
                                <input type="datetime-local" wire:model="executedAt" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                @error('executedAt') <span class="text-red-500 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        <div class="flex gap-3 pt-4">
                            <button type="button" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition" wire:click="closeMessageModal">
                                Cancel
                            </button>
                            <button type="button" class="px-6 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition flex items-center gap-2" wire:click="sendMessage" wire:loading.attr="disabled" wire:target="sendMessage">
                                <i class="fa-solid fa-paper-plane"></i> Send Message
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($showExportModal)
        <div class="modal modal-mask" style="position: fixed; z-index: 9998; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,.5); display: table;">
            <div class="modal-wrapper px-4" style="display: table-cell; vertical-align: middle;">
                <div class="modal-container w-full max-w-md px-8 mx-auto" style="margin: 0 auto; padding: 20px 30px; background-color: #fff; border-radius: 2px; box-shadow: 0 2px 8px rgba(0,0,0,.33); max-height: 550px; overflow: auto;">
                    <div class="modal-header flex justify-between items-center">
                        <h2>Custom Export</h2>
                        <button type="button" class="modal-default-button text-2xl py-1" wire:click="closeExportModal">&times;</button>
                    </div>
                    <div class="modal-body my-3">
                        <label class="flex items-center gap-2 mb-2">
                            <input type="checkbox" wire:click="toggleAllExportColumns($event.target.checked)" @checked(count($exportColumns) === count($exportableColumns))>
                            <span class="font-semibold">Check All</span>
                        </label>
                        @foreach ($exportableColumns as $columnKey => $columnLabel)
                            <label class="flex items-center gap-2 py-1">
                                <input type="checkbox" wire:model="exportColumns" value="{{ $columnKey }}">
                                <span>{{ $columnLabel }}</span>
                            </label>
                        @endforeach
                    </div>
                    <div class="my-6 flex gap-2">
                        <button type="button" wire:click="submitExport" class="btn btn-submit blue-bg text-white rounded px-3 py-1 text-sm font-medium">Submit</button>
                        <button type="button" wire:click="closeExportModal" class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 text-sm font-medium">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
