<div>
    <ul id="progressbar" class="w-full lg:w-9/12 mx-auto">
        <li @class(['active' => $currentStep === 1])><a href="#" class="text-gray-700 font-medium">Standard Detail</a></li>
        <li @class(['active' => $currentStep === 2])><a href="#" class="text-gray-700 font-medium">Student Detail</a></li>
        <li @class(['active' => $currentStep === 3])><a href="#" class="text-gray-700 font-medium">Academic Detail</a></li>
        <li @class(['active' => $currentStep === 4])><a href="#" class="text-gray-700 font-medium">Parent Detail</a></li>
        <li @class(['active' => $currentStep === 5])><a href="#" class="text-gray-700 font-medium">Personal Detail</a></li>
    </ul>

    @if ($submitted)
        <div class="bg-white shadow px-4 py-6 text-center">
            <div class="alert alert-success" id="success-alert">{{ $successMessage }}</div>
            <p class="text-sm text-gray-600 mt-2">Your admission application has been submitted successfully.</p>
        </div>
    @else
        @if ($submitError)
            <div class="alert alert-danger bg-red-200 text-red-500 text-sm p-2 rounded mb-2">{{ $submitError }}</div>
        @endif

        {{-- Step 1: Standard Detail --}}
        <div @class(['bg-white', 'shadow', 'px-4', 'py-3', 'hidden' => $currentStep !== 1])>
            <div class="my-5">
                <div class="tw-form-group w-full lg:w-3/4 md:w-3/4">
                    <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                        <div class="mb-2 w-full lg:w-1/4 md:w-1/4">
                            <label for="standard_id" class="tw-form-label">Class<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2 w-full lg:w-1/4 md:w-1/4">
                            <select class="tw-form-control w-full" id="standard_id" wire:model="standard_id">
                                <option value="" disabled>Select Class</option>
                                @foreach ($standardlist as $standard)
                                    <option value="{{ $standard['id'] }}">{{ $standard['name'] }}</option>
                                @endforeach
                            </select>
                            @error('standard_id') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="my-6">
                <a href="#" class="btn-primary submit-btn blue-bg text-sm text-white px-2 py-1 rounded mx-1" wire:click="nextStep">Next</a>
                <a href="#" class="btn-reset reset-btn" wire:click="resetForm">Reset</a>
            </div>
        </div>

        {{-- Step 2: Student Detail --}}
        <div @class(['bg-white', 'shadow', 'px-4', 'py-3', 'hidden' => $currentStep !== 2])>
            <fieldset class="shadow">
                <div class="flex flex-col lg:flex-row">
                    <div class="w-full lg:w-2/3">
                        <div class="flex flex-col lg:flex-row">
                            <div class="w-full lg:w-1/2 lg:mr-2">
                                <div class="my-1">
                                    <label class="tw-form-label">First Name<span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="name" placeholder="First Name" class="tw-form-control w-full my-1 py-2">
                                </div>
                                @error('name') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                            </div>
                            <div class="w-full lg:w-1/2 lg:mr-2">
                                <div class="my-1">
                                    <label class="tw-form-label">Last Name</label>
                                    <input type="text" wire:model="lastname" placeholder="Last Name" class="tw-form-control w-full my-1 py-2">
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col lg:flex-row">
                            <div class="w-full lg:w-1/2 lg:mr-2">
                                <div class="my-1">
                                    <label class="tw-form-label">Date of birth<span class="text-red-500">*</span></label>
                                    <input type="date" wire:model="date_of_birth" class="tw-form-control w-full my-1 py-2">
                                </div>
                                @error('date_of_birth') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                            </div>
                            <div class="w-full lg:w-1/2 lg:mr-2">
                                <div class="my-1">
                                    <label class="tw-form-label">Gender<span class="text-red-500">*</span></label>
                                    <div class="flex tw-form-control py-2 my-1">
                                        <div class="w-1/4 flex items-center mr-2 lg:mr-8 md:mr-8">
                                            <input type="radio" wire:model="gender" value="male">
                                            <span class="text-sm mx-2">Boy</span>
                                        </div>
                                        <div class="w-1/4 flex items-center">
                                            <input type="radio" wire:model="gender" value="female">
                                            <span class="text-sm mx-2">Girl</span>
                                        </div>
                                    </div>
                                </div>
                                @error('gender') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="flex flex-col lg:flex-row">
                            <div class="w-full lg:w-1/2 lg:mr-2">
                                <div class="my-1">
                                    <label class="tw-form-label">Height</label>
                                    <input type="text" wire:model="height" placeholder="Height" class="tw-form-control w-full my-1 py-2">
                                </div>
                                @error('height') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                            </div>
                            <div class="w-full lg:w-1/2 lg:mr-2">
                                <div class="my-1">
                                    <label class="tw-form-label">Weight</label>
                                    <input type="text" wire:model="weight" placeholder="Weight" class="tw-form-control w-full my-1 py-2">
                                </div>
                                @error('weight') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="w-full lg:w-1/3">
                        <div class="relative w-10/12 mx-auto my-2">
                            <label class="tw-form-label">Attach Photo</label>
                            <input type="file" wire:model="avatar" class="tw-form-control w-full">
                            @if ($avatar)
                                <img src="{{ $avatar->temporaryUrl() }}" style="width: 150px;height: 150px;object-fit: cover;" class="my-2" onerror="this.onerror=null;this.src='{{ url('/uploads/user/avatar/default-user.jpg') }}';">
                            @else
                                <img class="student-img text-sm border border-dashed border-gray-300 my-2" src="{{ url('/uploads/user/avatar/default-user.jpg') }}" style="width: 150px;height: 150px;">
                            @endif
                            @error('avatar') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="flex flex-col lg:flex-row">
                    <div class="w-full lg:w-1/3 lg:mr-2">
                        <div class="my-1">
                            <label class="tw-form-label">Birth Place</label>
                            <input type="text" wire:model="birth_place" placeholder="Birth Place" class="tw-form-control w-full my-1 py-2">
                        </div>
                        @error('birth_place') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                    </div>
                    <div class="w-full lg:w-1/3 lg:mr-2">
                        <div class="my-1">
                            <label class="tw-form-label">Nationality</label>
                            <input type="text" wire:model="nationality" placeholder="Nationality" class="tw-form-control w-full my-1 py-2">
                        </div>
                        @error('nationality') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                    </div>
                    <div class="my-1 w-full lg:w-1/3 lg:mr-2">
                        <label class="tw-form-label">Religion</label>
                        <input type="text" wire:model="religion" placeholder="Religion" class="tw-form-control w-full my-1 py-2">
                        @error('religion') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex flex-col lg:flex-row">
                    <div class="my-1 w-full lg:w-1/2 lg:mr-2">
                        <label class="tw-form-label">Community</label>
                        <input type="text" wire:model="community" placeholder="Community" class="tw-form-control w-full my-1 py-2">
                        <p class="text-xs mb-0">(BC / BCM / FC / MBC / OBC / Others / SC / SCA / ST )</p>
                        @error('community') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                    </div>
                    <div class="my-1 w-full lg:w-1/2 lg:mr-2">
                        <label class="tw-form-label">Mother tongue</label>
                        <input type="text" wire:model="mother_tongue" placeholder="Mother tongue" class="tw-form-control w-full my-1 py-2">
                        @error('mother_tongue') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="tw-form-label">Identification Marks</label>
                    <input type="text" wire:model="identification_marks" placeholder="Identification Marks" class="tw-form-control w-full lg:w-1/2 my-1 py-2">
                    @error('identification_marks') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                </div>

                <div class="flex flex-col lg:flex-row">
                    <div class="my-1 w-full lg:w-1/2 lg:mr-2">
                        <label class="tw-form-label">Aadhaar Number</label>
                        <input type="text" wire:model="aadhar_number" placeholder="Aadhar Number" class="tw-form-control w-full my-1 py-2">
                        @error('aadhar_number') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                    </div>
                    <div class="w-full lg:w-1/2 lg:mr-2 my-1">
                        <label class="tw-form-label">Blood Group</label>
                        <select wire:model="blood_group" class="tw-form-control w-full my-1 py-2">
                            <option value="" disabled>Select Blood Group</option>
                            @foreach ($bloodGroupList as $blood)
                                <option value="{{ $blood['num'] }}">{{ $blood['name'] }}</option>
                            @endforeach
                        </select>
                        @error('blood_group') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex flex-col lg:flex-row">
                    <div class="w-full lg:w-1/2 lg:mr-2">
                        <div class="my-1">
                            <label class="tw-form-label">School last studied</label>
                            <input type="text" wire:model="school_last_studied" placeholder="School last studied" class="tw-form-control w-full my-1 py-2">
                        </div>
                        @error('school_last_studied') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                    </div>
                    <div class="w-full lg:w-1/2 lg:mr-2">
                        <div class="my-1">
                            <label class="tw-form-label">Reason for leaving</label>
                            <input type="text" wire:model="reason_for_leaving" placeholder="Reason for leaving" class="tw-form-control w-full my-1 py-2">
                        </div>
                        @error('reason_for_leaving') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex flex-col lg:flex-row">
                    <div class="my-1 w-full lg:w-1/2 lg:mr-2">
                        <label class="tw-form-label">Address : Permanent</label>
                        <textarea wire:model="permanent_address" placeholder="Permanent Address" class="tw-form-control w-full my-1 py-2"></textarea>
                        @error('permanent_address') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                    </div>
                    <div class="my-1 w-full lg:w-1/2 lg:mr-2">
                        <label class="tw-form-label">Address : for communication</label>
                        <textarea wire:model="address_for_communication" placeholder="Communication Address" class="tw-form-control w-full my-1 py-2"></textarea>
                        @error('address_for_communication') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="my-1">
                    <div class="flex flex-col lg:flex-row lg:items-center">
                        <label class="tw-form-label">Is the sibling studying in the same school:</label>
                        <div class="flex py-2 my-1 lg:mx-5">
                            <div class="w-1/4 flex items-center mr-2 lg:mr-8 md:mr-8">
                                <input type="radio" wire:model.live="siblings" value="yes">
                                <span class="text-sm mx-2">Yes</span>
                            </div>
                            <div class="w-1/4 flex items-center">
                                <input type="radio" wire:model.live="siblings" value="no">
                                <span class="text-sm mx-2">No</span>
                            </div>
                        </div>
                        @error('siblings') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                    </div>
                    @if ($siblings === 'yes')
                        <div class="my-1">
                            <label class="tw-form-label">Sibling Details</label>
                            <input type="text" wire:model="siblings_details" placeholder="Sibling name / class studying in" class="tw-form-control w-full my-1 py-2">
                            @error('siblings_details') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                        </div>
                    @endif
                </div>

                <a href="#" class="btn-primary submit-btn blue-bg text-sm text-white px-2 py-1 rounded mx-1" wire:click="previousStep">Previous</a>
                <a href="#" class="btn-primary submit-btn blue-bg text-sm text-white px-2 py-1 rounded mx-1" wire:click="nextStep">Next</a>
            </fieldset>
        </div>

        {{-- Step 3: Academic Detail --}}
        <div @class(['bg-white', 'shadow', 'px-4', 'py-3', 'hidden' => $currentStep !== 3])>
            <fieldset class="shadow">
                <h6 class="text-sm font-bold mb-3">Half Yearly Mark Details</h6>
                <div class="flex flex-col lg:flex-row">
                    <div class="w-full lg:w-1/2 lg:mr-2">
                        <label class="tw-form-label mr-2">English</label>
                        <input type="text" wire:model="english" placeholder="English" class="tw-form-control w-1/2 mx-4 my-1 py-2">
                        @error('english') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                    </div>
                    <div class="w-full lg:w-1/2 lg:mr-2">
                        <label class="tw-form-label mr-2">Tamil</label>
                        <input type="text" wire:model="tamil" placeholder="Tamil" class="tw-form-control w-1/2 mx-4 my-1 py-2">
                        @error('tamil') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="flex flex-col lg:flex-row">
                    <div class="w-full lg:w-1/2 lg:mr-2">
                        <label class="tw-form-label mr-2">Maths</label>
                        <input type="text" wire:model="maths" placeholder="Maths" class="tw-form-control w-1/2 mx-4 my-1 py-2">
                        @error('maths') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                    </div>
                    <div class="w-full lg:w-1/2 lg:mr-2">
                        <label class="tw-form-label mr-2">Science</label>
                        <input type="text" wire:model="science" placeholder="Science" class="tw-form-control w-1/2 mx-4 my-1 py-2">
                        @error('science') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="flex flex-col lg:flex-row">
                    <div class="w-full lg:w-1/2 lg:mr-2">
                        <label class="tw-form-label mr-2">Social</label>
                        <input type="text" wire:model="social" placeholder="Social" class="tw-form-control w-1/2 mx-4 my-1 py-2">
                        @error('social') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="w-full my-1">
                    <h6 class="text-sm font-bold mb-3">Board of Study<span class="text-red-500">*</span></h6>
                    <ul class="list-reset leading-loose flex items-center flex-wrap">
                        @foreach ($boardList as $board)
                            <li class="mr-4">
                                <input type="radio" wire:model="board_of_education" value="{{ $board['id'] }}">
                                <span class="text-sm mr-2">{{ $board['name'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                    @error('board_of_education') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                </div>

                <div class="w-full my-1">
                    <h6 class="text-sm font-bold mb-3">Choice of Language<span class="text-red-500">*</span></h6>
                    <ul class="list-reset leading-loose flex items-center flex-wrap">
                        @foreach ($languageList as $language)
                            <li class="mr-4">
                                <input type="radio" wire:model="choice_of_language" value="{{ $language['id'] }}">
                                <span class="text-sm mr-2">{{ $language['name'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                    @error('choice_of_language') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                </div>

                <div class="my-1">
                    <h6 class="text-sm font-bold mb-3">Group Selection<span class="text-red-500 whitespace-no-wrap">*Only For Class X , XI , XII</span></h6>
                    <ul class="list-reset leading-loose">
                        @foreach ($groupList as $group)
                            <li>
                                <input type="radio" @checked($group_selection === $group['id']) wire:click="toggleGroupSelection('{{ $group['id'] }}')">
                                <span class="text-sm mx-2">{{ $group['name'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                    @error('group_selection') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                </div>

                <div class="w-full lg:w-1/2 lg:mr-2">
                    <label class="tw-form-label"><h6 class="text-sm font-bold mb-3">Board Registration Number<span class="text-red-500 whitespace-no-wrap">*Only For Class X , XI , XII</span></h6></label>
                    <input type="text" wire:model="board_registration_number" placeholder="Board Registration Number" class="tw-form-control w-full my-1 py-2">
                    @error('board_registration_number') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                </div>

                <a href="#" class="btn-primary submit-btn blue-bg text-sm text-white px-2 py-1 rounded mx-1" wire:click="previousStep">Previous</a>
                <a href="#" class="btn-primary submit-btn blue-bg text-sm text-white px-2 py-1 rounded mx-1" wire:click="nextStep">Next</a>
            </fieldset>
        </div>

        {{-- Step 4: Parent Detail --}}
        <div @class(['bg-white', 'shadow', 'px-4', 'py-3', 'hidden' => $currentStep !== 4])>
            <fieldset class="shadow">
                <h2 class="text-lg my-2">Father's Detail</h2>
                <div class="flex flex-col lg:flex-row">
                    <div class="w-full lg:w-2/3">
                        <div class="flex flex-col lg:flex-row">
                            <div class="w-full lg:w-1/2 lg:mr-2">
                                <div class="my-1">
                                    <label class="tw-form-label">Father Name<span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="father_name" placeholder="Father Name" class="tw-form-control w-full my-1 py-2">
                                    @error('father_name') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="w-full lg:w-1/2 lg:mr-2">
                                <div class="my-1">
                                    <label class="tw-form-label">Qualification<span class="text-red-500">*</span></label>
                                    <select wire:model="father_qualification_id" class="tw-form-control w-full">
                                        <option value="" disabled>Select Qualification</option>
                                        @foreach ($qualificationList as $qualification)
                                            <option value="{{ $qualification['id'] }}">{{ $qualification['display_name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('father_qualification_id') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="flex flex-col lg:flex-row">
                            <div class="w-full lg:w-1/2 lg:mr-2">
                                <div class="my-1">
                                    <label class="tw-form-label">Occupation<span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="father_occupation" placeholder="Occupation" class="tw-form-control w-full my-1 py-2">
                                </div>
                                @error('father_occupation') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                            </div>
                            <div class="w-full lg:w-1/2 lg:mr-2">
                                <div class="my-1">
                                    <label class="tw-form-label">Designation<span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="father_designation" placeholder="Designation" class="tw-form-control w-full my-1 py-2">
                                </div>
                                @error('father_designation') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="flex flex-col lg:flex-row">
                            <div class="w-full lg:w-1/2 lg:mr-2">
                                <div class="my-1">
                                    <label class="tw-form-label">Organization<span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="father_organisation" placeholder="Organization" class="tw-form-control w-full my-1 py-2">
                                </div>
                                @error('father_organisation') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                            </div>
                            <div class="w-full lg:w-1/2 lg:mr-2">
                                <div class="my-1">
                                    <label class="tw-form-label">Income (P.A)<span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="father_income" placeholder="Income" class="tw-form-control w-full my-1 py-2">
                                </div>
                                @error('father_income') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="w-full lg:w-1/3">
                        <div class="relative w-10/12 mx-auto my-2">
                            <label class="tw-form-label">Attach Photo</label>
                            <input type="file" wire:model="father_avatar" class="tw-form-control w-full">
                            @if ($father_avatar)
                                <img src="{{ $father_avatar->temporaryUrl() }}" style="width: 150px;height: 150px;object-fit: cover;" class="my-2" onerror="this.onerror=null;this.src='{{ url('/uploads/user/avatar/default-user.jpg') }}';">
                            @else
                                <img class="student-img text-sm border border-dashed border-gray-300 my-2" src="{{ url('/uploads/user/avatar/default-user.jpg') }}" style="width: 150px;height: 150px;">
                            @endif
                            @error('father_avatar') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="flex flex-col lg:flex-row">
                    <div class="w-full lg:w-1/3 lg:mr-2">
                        <div class="my-1">
                            <label class="tw-form-label">Mobile Number<span class="text-red-500">*</span></label>
                            <input type="text" wire:model="father_mobile_no" placeholder="Mobile Number" class="tw-form-control w-full my-1 py-2">
                        </div>
                        @error('father_mobile_no') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                    </div>
                    <div class="w-full lg:w-1/3 lg:mr-2">
                        <div class="my-1">
                            <label class="tw-form-label">Email ID</label>
                            <input type="text" wire:model="father_email" placeholder="Email ID" class="tw-form-control w-full my-1 py-2">
                        </div>
                        @error('father_email') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                    </div>
                    <div class="w-full lg:w-1/3 lg:mr-2">
                        <div class="my-1">
                            <label class="tw-form-label">Aadhaar ID</label>
                            <input type="text" wire:model="father_aadhar_number" placeholder="Aadhar Number" class="tw-form-control w-full my-1 py-2">
                        </div>
                        @error('father_aadhar_number') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                    </div>
                </div>

                <h2 class="text-lg my-2">Mother's Detail</h2>
                <div class="flex flex-col lg:flex-row">
                    <div class="w-full lg:w-2/3">
                        <div class="flex flex-col lg:flex-row">
                            <div class="w-full lg:w-1/2 lg:mr-2">
                                <div class="my-1">
                                    <label class="tw-form-label">Mother Name<span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="mother_name" placeholder="Mother Name" class="tw-form-control w-full my-1 py-2">
                                </div>
                                @error('mother_name') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                            </div>
                            <div class="w-full lg:w-1/2 lg:mr-2">
                                <div class="my-1">
                                    <label class="tw-form-label">Qualification<span class="text-red-500">*</span></label>
                                    <select wire:model="mother_qualification_id" class="tw-form-control w-full">
                                        <option value="" disabled>Select Qualification</option>
                                        @foreach ($qualificationList as $qualification)
                                            <option value="{{ $qualification['id'] }}">{{ $qualification['display_name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('mother_qualification_id') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="flex flex-col lg:flex-row">
                            <div class="w-full lg:w-1/2 lg:mr-2">
                                <div class="my-1">
                                    <label class="tw-form-label">Occupation<span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="mother_occupation" placeholder="Occupation" class="tw-form-control w-full my-1 py-2">
                                </div>
                                @error('mother_occupation') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                            </div>
                            <div class="w-full lg:w-1/2 lg:mr-2">
                                <div class="my-1">
                                    <label class="tw-form-label">Designation<span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="mother_designation" placeholder="Designation" class="tw-form-control w-full my-1 py-2">
                                </div>
                                @error('mother_designation') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="flex flex-col lg:flex-row">
                            <div class="w-full lg:w-1/2 lg:mr-2">
                                <div class="my-1">
                                    <label class="tw-form-label">Organization<span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="mother_organisation" placeholder="Organization" class="tw-form-control w-full my-1 py-2">
                                </div>
                                @error('mother_organisation') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                            </div>
                            <div class="w-full lg:w-1/2 lg:mr-2">
                                <div class="my-1">
                                    <label class="tw-form-label">Income (P.A)<span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="mother_income" placeholder="Income" class="tw-form-control w-full my-1 py-2">
                                </div>
                                @error('mother_income') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="w-full lg:w-1/3">
                        <div class="relative w-10/12 mx-auto my-2">
                            <label class="tw-form-label">Attach Photo</label>
                            <input type="file" wire:model="mother_avatar" class="tw-form-control w-full">
                            @if ($mother_avatar)
                                <img src="{{ $mother_avatar->temporaryUrl() }}" style="width: 150px;height: 150px;object-fit: cover;" class="my-2" onerror="this.onerror=null;this.src='{{ url('/uploads/user/avatar/default-user.jpg') }}';">
                            @else
                                <img class="student-img text-sm border border-dashed border-gray-300 my-2" src="{{ url('/uploads/user/avatar/default-user.jpg') }}" style="width: 150px;height: 150px;">
                            @endif
                        </div>
                        @error('mother_avatar') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex flex-col lg:flex-row">
                    <div class="w-full lg:w-1/3 lg:mr-2">
                        <div class="my-1">
                            <label class="tw-form-label">Mobile Number</label>
                            <input type="text" wire:model="mother_mobile_no" placeholder="Mobile Number" class="tw-form-control w-full my-1 py-2">
                            @error('mother_mobile_no') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="w-full lg:w-1/3 lg:mr-2">
                        <div class="my-1">
                            <label class="tw-form-label">Email ID</label>
                            <input type="text" wire:model="mother_email" placeholder="Email ID" class="tw-form-control w-full my-1 py-2">
                        </div>
                        @error('mother_email') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                    </div>
                    <div class="w-full lg:w-1/3 lg:mr-2">
                        <div class="my-1">
                            <label class="tw-form-label">Aadhaar ID</label>
                            <input type="text" wire:model="mother_aadhar_number" placeholder="Aadhar Number" class="tw-form-control w-full my-1 py-2">
                        </div>
                        @error('mother_aadhar_number') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                    </div>
                </div>

                <h2 class="text-lg my-2">Emergency Contact<span class="text-red-500">*</span></h2>
                <div class="flex flex-col lg:flex-row">
                    <div class="w-full lg:w-1/3 lg:mr-2">
                        <div class="my-1 flex items-center">
                            <label class="tw-form-label">1.</label>
                            <input type="text" wire:model="emergency_contact_1" placeholder="Mobile Number" class="tw-form-control w-full my-1 py-2 mx-2">
                        </div>
                        @error('emergency_contact_1') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                    </div>
                    <div class="w-full lg:w-2/3 lg:mr-2">
                        <div class="my-1 flex flex-col lg:flex-row lg:items-center ml-5 lg:ml-0">
                            <label class="tw-form-label w-full lg:w-4/12">Relationship with Student</label>
                            <input type="text" wire:model="relation_with_student_1" placeholder="Relationship" class="tw-form-control w-full lg:w-8/12 my-1 py-2">
                        </div>
                        @error('relation_with_student_1') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex flex-col lg:flex-row">
                    <div class="w-full lg:w-1/3 lg:mr-2">
                        <div class="my-1 flex items-center">
                            <label class="tw-form-label">2.</label>
                            <input type="text" wire:model="emergency_contact_2" placeholder="Mobile Number" class="tw-form-control w-full my-1 py-2 mx-2">
                        </div>
                        @error('emergency_contact_2') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                    </div>
                    <div class="w-full lg:w-2/3 lg:mr-2">
                        <div class="my-1 flex flex-col lg:flex-row items-center ml-5 lg:ml-0">
                            <label class="tw-form-label w-full lg:w-4/12">Relationship with Student</label>
                            <input type="text" wire:model="relation_with_student_2" placeholder="Relationship" class="tw-form-control w-full lg:w-8/12 my-1 py-2">
                        </div>
                        @error('relation_with_student_2') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                    </div>
                </div>

                <a href="#" class="btn-primary submit-btn blue-bg text-sm text-white px-2 py-1 rounded mx-1" wire:click="previousStep">Previous</a>
                <a href="#" class="btn-primary submit-btn blue-bg text-sm text-white px-2 py-1 rounded mx-1" wire:click="nextStep">Next</a>
            </fieldset>
        </div>

        {{-- Step 5: Personal Detail --}}
        <div @class(['bg-white', 'shadow', 'px-4', 'py-3', 'hidden' => $currentStep !== 5])>
            <fieldset class="shadow">
                <div class="my-1">
                    <h2 class="text-lg">Medical History<span class="text-red-500">*</span></h2>
                    <div class="flex flex-col lg:flex-row lg:items-center">
                        <label class="tw-form-label">Does the Student having any Health issues? : </label>
                        @foreach ($booleanList as $list)
                            <div class="flex py-2 lg:mx-1">
                                <div class="w-full lg:w-1/4 flex items-center mr-2 lg:mr-8 md:mr-8">
                                    <input type="radio" wire:model.live="medical_history" value="{{ $list['id'] }}">
                                    <span class="text-sm mx-2">{{ $list['name'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @error('medical_history') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror

                    @if ($medical_history === 'yes')
                        <ul class="flex flex-wrap list-reset leading-loose">
                            @foreach ($medicalList as $list)
                                <li class="w-full lg:w-1/4 md:w-1/2 my-2 relative flex items-center my-1">
                                    <input type="checkbox" wire:model="medical_details" value="{{ $list['id'] }}">
                                    <label class="tw-form-label mx-2">{{ $list['name'] }}</label>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <div class="my-1">
                    <h2 class="text-lg mt-4">Extra Curricular<span class="text-red-500">*</span></h2>
                    <div class="flex flex-col lg:flex-row lg:items-center">
                        <label class="tw-form-label">Does the Student has interest in any extra co – curricular activities ? :</label>
                        @foreach ($booleanList as $list)
                            <div class="flex py-2 lg:mx-1">
                                <div class="w-full lg:w-1/4 flex items-center mr-2 lg:mr-8 md:mr-8">
                                    <input type="radio" wire:model.live="extra_curricular_activities" value="{{ $list['id'] }}">
                                    <span class="text-sm mx-2">{{ $list['name'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @error('extra_curricular_activities') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror

                    @if ($extra_curricular_activities === 'yes')
                        <ul class="flex flex-wrap list-reset leading-loose">
                            @foreach ($activitiesList as $list)
                                <li class="w-full lg:w-1/4 md:w-1/2 my-2 relative flex items-center my-1">
                                    <input type="checkbox" wire:model="activities" value="{{ $list['id'] }}">
                                    <label class="tw-form-label mx-2">{{ $list['name'] }}</label>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <div class="my-1">
                    <h2 class="text-lg mt-4">Mode of Transport<span class="text-red-500">*</span></h2>
                    <div class="flex flex-wrap py-2">
                        @foreach ($transportList as $list)
                            <div class="w-full lg:w-1/4 md:w-1/2 my-2 relative flex items-center my-1">
                                <input type="radio" wire:model.live="mode_of_transport" value="{{ $list['id'] }}">
                                <span class="text-sm mx-2">{{ $list['name'] }}</span>
                            </div>
                        @endforeach
                    </div>
                    @error('mode_of_transport') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror

                    @if (in_array($mode_of_transport, ['auto', 'rickshaw', 'taxi']))
                        <div class="flex flex-col lg:flex-row">
                            <div class="w-full lg:w-1/2 my-1 mr-2">
                                <label class="tw-form-label">Driver's Name</label>
                                <input type="text" wire:model="driver_name" placeholder="Driver's Name" class="tw-form-control w-full my-1 py-2">
                                @error('driver_name') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                            </div>
                            <div class="w-full lg:w-1/2 my-1 mr-2">
                                <label class="tw-form-label">Phone Number</label>
                                <input type="text" wire:model="driver_mobile_number" placeholder="Phone Number" class="tw-form-control w-full my-1 py-2">
                                @error('driver_mobile_number') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    @endif
                </div>

                <div class="my-6">
                    <a href="#" class="btn-primary submit-btn blue-bg text-sm text-white px-2 py-1 rounded mx-1" wire:click="previousStep">Previous</a>
                    <a href="#" class="btn-primary submit-btn blue-bg text-sm text-white px-2 py-1 rounded mx-1" wire:click="submit" wire:loading.attr="disabled" wire:target="submit">Submit</a>
                </div>
            </fieldset>
        </div>
    @endif
</div>
