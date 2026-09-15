<div class="bg-white shadow px-4 py-3">
    <h1>Profile</h1>

    @if ($this->isEdit())
        <div class="my-6" wire:key="avatar-field">
            <div class="flex items-center">
                <img src="{{ $avatar ? $avatar->temporaryUrl() : $avatarDisplay }}" class="img-responsive w-12 h-12 rounded-full object-cover">
                <div class="mx-2">
                    <h2 class="font-semibold text-sm text-gray-700">{{ $firstname }} {{ $lastname }}</h2>
                    <label class="tw-label cursor-pointer text-xs text-gray-600"> Change Avatar
                        <input type="file" wire:model="avatar" class="hidden" wire:key="avatar-input">
                    </label>
                    <div wire:loading wire:target="avatar" class="text-xs text-gray-500">Uploading...</div>
                    @error('avatar') <span class="text-red-500 text-xs font-semibold block">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
    @endif

    <div class="flex flex-col lg:flex-row">
        <div class="tw-form-group w-full lg:w-1/3">
            <div class="lg:mr-8 md:mr-8">
                <div class="mb-2">
                    <label class="tw-form-label">First Name<span class="text-red-500">*</span></label>
                </div>
                <div class="mb-2">
                    <input type="text" class="tw-form-control w-full" wire:model="firstname" placeholder="First Name">
                </div>
                @error('firstname') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="tw-form-group w-full lg:w-1/3">
            <div class="lg:mr-8 md:mr-8">
                <div class="mb-2">
                    <label class="tw-form-label">Last Name</label>
                </div>
                <div class="mb-2">
                    <input type="text" class="tw-form-control w-full" wire:model="lastname" placeholder="Last Name">
                </div>
                @error('lastname') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="tw-form-group w-full lg:w-1/3">
            <div class="lg:mr-8 md:mr-8">
                <div class="mb-2">
                    <label class="tw-form-label">Date Of Birth<span class="text-red-500">*</span></label>
                </div>
                <div class="mb-2">
                    <input type="date" class="tw-form-control w-full" wire:model="date_of_birth">
                </div>
                @error('date_of_birth') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row">
        <div class="tw-form-group w-full lg:w-1/3">
            <div class="lg:mr-8 md:mr-8">
                <div class="mb-2">
                    <label class="tw-form-label">Employee ID<span class="text-red-500">*</span></label>
                </div>
                <div class="mb-2">
                    <input type="text" class="tw-form-control w-full" wire:model="employee_id" placeholder="Employee ID">
                </div>
                @error('employee_id') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="tw-form-group w-full lg:w-1/3">
            <div class="lg:mr-8 md:mr-8">
                <div class="mb-2">
                    <label class="tw-form-label">Designation<span class="text-red-500">*</span></label>
                </div>
                <div class="mb-2">
                    <select class="tw-form-control w-full" wire:model.live="designation">
                        <option value="" disabled>Select Designation</option>
                        @foreach ($designationlist as $option)
                            <option value="{{ $option['id'] }}">{{ $option['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                @error('designation') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
            </div>
        </div>

        @if ($this->hasSubDesignation())
            <div class="tw-form-group w-full lg:w-1/3" wire:key="sub-designation">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label class="tw-form-label">Sub Designation<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <input type="text" class="tw-form-control w-full" wire:model="sub_designation" placeholder="Sub Designation">
                    </div>
                    @error('sub_designation') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                </div>
            </div>
        @endif
    </div>

    @if ($this->hasReportingTo())
        <div class="flex flex-col lg:flex-row" wire:key="reporting-to">
            <div class="tw-form-group w-full lg:w-1/3">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label class="tw-form-label">Reporting To</label>
                    </div>
                    <div class="mb-2">
                        <select class="tw-form-control w-full" wire:model="reporting_to">
                            <option value="" disabled>Select</option>
                            @foreach (($designation === 'head_of_the_department' ? $hodList : $principalList) as $option)
                                <option value="{{ $option['id'] }}">{{ $option['fullname'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('reporting_to') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
    @endif

    @unless ($this->isEdit())
        <div class="flex flex-col lg:flex-row">
            <div class="tw-form-group w-full lg:w-1/3">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label class="tw-form-label">Mobile Number<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <input type="text" class="tw-form-control w-full" wire:model="mobile_no" placeholder="Mobile Number">
                    </div>
                    @error('mobile_no') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="tw-form-group w-full lg:w-1/3">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label class="tw-form-label">Email ID<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <input type="text" class="tw-form-control w-full" wire:model="email" placeholder="Email ID">
                    </div>
                    @error('email') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
    @endunless

    <div class="flex flex-col lg:flex-row">
        <div class="tw-form-group w-full lg:w-1/5">
            <div class="lg:mr-8 md:mr-8">
                <div class="mb-2">
                    <label class="tw-form-label">Gender<span class="text-red-500">*</span></label>
                </div>
                <div class="flex tw-form-control">
                    <div class="w-1/2 flex items-center mr-2 lg:mr-8 md:mr-8">
                        <input type="radio" wire:model="gender" value="male">
                        <span class="text-sm mx-1">Male</span>
                    </div>
                    <div class="w-1/2 flex items-center">
                        <input type="radio" wire:model="gender" value="female">
                        <span class="text-sm mx-1">Female</span>
                    </div>
                </div>
                @error('gender') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="tw-form-group w-full lg:w-2/5">
            <div class="lg:mr-8 md:mr-8">
                <div class="mb-2">
                    <label class="tw-form-label">Blood Group<span class="text-red-500">*</span></label>
                </div>
                <div class="mb-2">
                    <select class="tw-form-control w-full" wire:model="blood_group">
                        <option value="" disabled>Select Blood Group</option>
                        @foreach ($bloodGroups as $bloodGroup)
                            <option value="{{ $bloodGroup['num'] }}">{{ $bloodGroup['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                @error('blood_group') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="tw-form-group w-full lg:w-2/5">
            <div class="lg:mr-8 md:mr-8">
                <div class="mb-2">
                    <label class="tw-form-label">Aadhaar Number</label>
                </div>
                <div class="mb-2">
                    <input type="text" class="tw-form-control w-full" wire:model="aadhar_number" placeholder="Aadhar Number">
                </div>
                @error('aadhar_number') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row">
        <div class="tw-form-group w-full lg:w-1/3">
            <div class="lg:mr-8 md:mr-8">
                <div class="mb-2">
                    <label class="tw-form-label">Marital Status{!! $this->isEdit() ? '' : ' <span class="text-red-500">*</span>' !!}</label>
                </div>
                <div class="mb-2">
                    <select class="tw-form-control w-full" wire:model="marital_status">
                        <option value="" disabled>Select Marital Status</option>
                        @foreach ($maritalList as $option)
                            <option value="{{ $option['id'] }}">{{ $option['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                @error('marital_status') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="tw-form-group w-full lg:w-1/3">
            <div class="lg:mr-8 md:mr-8">
                <div class="mb-2">
                    <label class="tw-form-label">Joining Date<span class="text-red-500">*</span></label>
                </div>
                <div class="mb-2">
                    <input type="date" class="tw-form-control w-full" wire:model="joining_date">
                </div>
                @error('joining_date') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
            </div>
        </div>

        @unless ($this->isEdit())
            <div class="tw-form-group w-full lg:w-1/3" wire:key="avatar-field">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label class="tw-form-label">Avatar</label>
                    </div>
                    <div class="mb-2">
                        <input type="file" wire:model="avatar" class="tw-form-control w-full" wire:key="avatar-input">
                    </div>
                    <div wire:loading wire:target="avatar" class="text-xs text-gray-500">Uploading...</div>
                    @if ($avatar)
                        <img src="{{ $avatar->temporaryUrl() }}" class="w-16 h-16 rounded-full object-cover my-2">
                    @endif
                    @error('avatar') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                </div>
            </div>
        @endunless
    </div>

    <div class="flex flex-col lg:flex-row" wire:key="job-details">
        <div class="tw-form-group w-full lg:w-1/3">
            <div class="lg:mr-8 md:mr-8">
                <div class="mb-2">
                    <label class="tw-form-label">Job Type{!! $this->isEdit() ? '' : ' <span class="text-red-500">*</span>' !!}</label>
                </div>
                <div class="flex tw-form-control">
                    <div class="w-1/2 flex items-center mr-2 lg:mr-8 md:mr-8">
                        <input type="radio" wire:model="job_type" value="full_time">
                        <span class="text-sm mx-1">Full Time</span>
                    </div>
                    <div class="w-1/2 flex items-center">
                        <input type="radio" wire:model="job_type" value="part_time">
                        <span class="text-sm mx-1">Part Time</span>
                    </div>
                </div>
                @error('job_type') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="tw-form-group w-full lg:w-2/3">
            <div class="lg:mr-8 md:mr-8">
                <div class="mb-2">
                    <label class="tw-form-label">Interested In</label>
                </div>
                <div class="mb-2">
                    <textarea class="tw-form-control w-full" wire:model="interested_in" rows="2"></textarea>
                </div>
                @error('interested_in') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>

    <hr style="border-width: 1px;">
    <h1>Educational Qualification</h1>

    <div class="flex flex-col lg:flex-row flex-wrap gap-y-2">
        @foreach ($qualifications as $index => $qualification)
            <div class="tw-form-group w-full lg:w-1/3" wire:key="qualification-{{ $index }}">
                <div class="lg:mr-2 md:mr-2">
                    <div class="mb-2">
                        <label class="tw-form-label">Certificate</label>
                    </div>
                    <div class="flex items-center">
                        <div class="mb-2 flex-1">
                            <select class="tw-form-control w-full" wire:model="qualifications.{{ $index }}">
                                <option value="" disabled>Select Certificate</option>
                                @foreach ($qualificationlist as $qualificationOption)
                                    <option value="{{ $qualificationOption['id'] }}">{{ $qualificationOption['display_name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if (count($qualifications) > 1)
                            <button type="button" class="mx-2 text-red-600" wire:click="removeQualificationRow({{ $index }})">&times;</button>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach

        <div class="tw-form-group w-full lg:w-1/3">
            <div class="lg:mr-8 md:mr-8">
                <div class="mb-2">
                    <label class="tw-form-label">Add New</label>
                </div>
                <div class="mb-2">
                    <button type="button" class="tw-form-control w-full text-left" wire:click="addQualificationRow">+ </button>
                </div>
            </div>
        </div>
    </div>

    @if (in_array(1, $qualifications))
        <div class="flex flex-col lg:flex-row" wire:key="sub-qualification">
            <div class="tw-form-group w-full lg:w-1/3">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label class="tw-form-label">Sub Qualification (SSLC/HSSLC/Diploma)</label>
                    </div>
                    <div class="mb-2">
                        <input type="text" class="tw-form-control w-full" wire:model="sub_qualification" placeholder="Sub Qualification">
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="flex flex-col lg:flex-row">
        <div class="tw-form-group w-full lg:w-1/3">
            <div class="lg:mr-8 md:mr-8">
                <div class="mb-2">
                    <label class="tw-form-label">UG Degree</label>
                </div>
                <div class="mb-2">
                    <select class="tw-form-control w-full" wire:model.live="ug_degree">
                        <option value="">Select UG Degree</option>
                        @foreach ($uglist as $option)
                            <option value="{{ $option['id'] }}">{{ $option['display_name'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="tw-form-group w-full lg:w-1/3">
            <div class="lg:mr-8 md:mr-8">
                <div class="mb-2">
                    <label class="tw-form-label">PG Degree</label>
                </div>
                <div class="mb-2">
                    <select class="tw-form-control w-full" wire:model.live="pg_degree">
                        <option value="">Select PG Degree</option>
                        @foreach ($pglist as $option)
                            <option value="{{ $option['id'] }}">{{ $option['display_name'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        @if ($ug_degree || $pg_degree)
            <div class="tw-form-group w-full lg:w-1/3" wire:key="specialization">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label class="tw-form-label">Specialization<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <input type="text" class="tw-form-control w-full" wire:model="specialization" placeholder="Specialization">
                    </div>
                    @error('specialization') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                </div>
            </div>
        @endif
    </div>

    <hr style="border-width: 1px;">
    <h1>Notes (Optional)</h1>

    <div class="flex flex-col lg:flex-row">
        <div class="tw-form-group w-full lg:w-1/2">
            <div class="lg:mr-8 md:mr-8">
                <div class="mb-2">
                    <textarea class="tw-form-control w-full" wire:model="notes" rows="3" placeholder="Notes"></textarea>
                </div>
                @error('notes') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>

    <hr style="border-width: 1px;">
    <h1>Address</h1>

    <div
        class="flex flex-col lg:flex-row gap-6 my-4"
        x-data="staffAddressMap()"
        x-init="init(@js($address))"
    >
        <div class="w-full lg:w-1/2">
            <label class="tw-form-label">Address</label>
            <div class="relative flex items-center">
                <input
                    type="text"
                    x-ref="addressInput"
                    x-model="localAddress"
                    x-on:change="$wire.set('address', localAddress)"
                    placeholder="Enter a location"
                    class="tw-form-control w-full pr-10"
                >
                <button
                    type="button"
                    x-on:click="codeAddress()"
                    class="absolute right-0 inset-y-0 flex items-center justify-center w-10 text-gray-400 hover:text-gray-600"
                    style="background: transparent; border: none; cursor: pointer;"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </button>
            </div>
        </div>
        <div class="w-full lg:w-1/2">
            <div x-ref="mapCanvas" class="w-full border border-gray-300 rounded" style="height: 250px;"></div>
        </div>
    </div>

    <div class="tw-form-group">
        <div class="flex flex-col lg:flex-row">
            <div class="w-full lg:w-1/4 lg:mr-8 md:mr-8">
                <div class="mb-2">
                    <label class="tw-form-label">Country<span class="text-red-500">*</span></label>
                </div>
                <div class="mb-2">
                    <select class="tw-form-control w-full" wire:model.live="country_id">
                        <option value="" disabled>Select Country</option>
                        @foreach ($countrylist as $country)
                            <option value="{{ $country['id'] }}">{{ $country['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                @error('country_id') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
            </div>

            <div class="w-full lg:w-1/4 lg:mr-8 md:mr-8">
                <div class="mb-2">
                    <label class="tw-form-label">State<span class="text-red-500">*</span></label>
                </div>
                <div class="mb-2">
                    <select class="tw-form-control w-full" wire:model.live="state_id">
                        <option value="" disabled>Select State</option>
                        @foreach ($statelist[$country_id] ?? [] as $state)
                            <option value="{{ $state['id'] }}">{{ $state['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                @error('state_id') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
            </div>

            <div class="w-full lg:w-1/4 lg:mr-8 md:mr-8">
                <div class="mb-2">
                    <label class="tw-form-label">City<span class="text-red-500">*</span></label>
                </div>
                <div class="mb-2">
                    <select class="tw-form-control w-full" wire:model="city_id">
                        <option value="" disabled>Select City</option>
                        @foreach ($citylist[$state_id] ?? [] as $city)
                            <option value="{{ $city['id'] }}">{{ $city['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                @error('city_id') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
            </div>

            <div class="w-full lg:w-1/4 lg:mr-8 md:mr-8">
                <div class="mb-2">
                    <label class="tw-form-label">Pincode{!! $this->isEdit() ? '' : ' <span class="text-red-500">*</span>' !!}</label>
                </div>
                <div class="mb-2">
                    <input type="text" class="tw-form-control w-full" wire:model="pincode" placeholder="Enter Pincode">
                </div>
                @error('pincode') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>

    @if ($customFields->isNotEmpty())
        <hr style="border-width: 1px;">
        <h2 class="text-lg font-semibold mb-2 mt-4">Additional Info</h2>

        @foreach ($customFields as $field)
            @include('livewire.admin.student.partials.custom-field', ['field' => $field])
        @endforeach
    @endif

    <div class="my-6">
        <button type="button" wire:click="save" wire:loading.attr="disabled" wire:target="save" class="btn btn-primary submit-btn">Submit</button>
    </div>
</div>

@once
    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('staffAddressMap', () => ({
                    localAddress: '',
                    map: null,
                    marker: null,
                    geocoder: null,
                    autocomplete: null,

                    init(address) {
                        this.localAddress = address || '';
                        this.waitForGoogle().then(() => this.initMap());
                    },

                    waitForGoogle() {
                        return new Promise((resolve) => {
                            const check = () => {
                                if (window.google && window.google.maps) {
                                    resolve();
                                } else {
                                    setTimeout(check, 300);
                                }
                            };
                            check();
                        });
                    },

                    async initMap() {
                        const { Map } = await google.maps.importLibrary('maps');
                        const { AdvancedMarkerElement } = await google.maps.importLibrary('marker');

                        const center = { lat: 9.9252007, lng: 78.1197754 };

                        this.map = new Map(this.$refs.mapCanvas, {
                            zoom: 15,
                            center,
                            mapId: 'DEMO_MAP_ID',
                        });

                        this.marker = new AdvancedMarkerElement({
                            map: this.map,
                            position: center,
                            gmpDraggable: true,
                        });

                        this.geocoder = new google.maps.Geocoder();

                        this.marker.addListener('dragend', (event) => {
                            this.setLocation(event.latLng.lat(), event.latLng.lng());
                        });

                        this.autocomplete = new google.maps.places.Autocomplete(this.$refs.addressInput);

                        this.autocomplete.addListener('place_changed', () => {
                            const place = this.autocomplete.getPlace();
                            if (!place.geometry) return;

                            this.localAddress = place.formatted_address;
                            this.setLocation(place.geometry.location.lat(), place.geometry.location.lng());
                            this.$wire.set('address', this.localAddress);
                        });

                        if (this.localAddress) {
                            this.geocoder.geocode({ address: this.localAddress }, (results, status) => {
                                if (status === 'OK') {
                                    this.setLocation(results[0].geometry.location.lat(), results[0].geometry.location.lng());
                                }
                            });
                        }
                    },

                    setLocation(lat, lng) {
                        this.map.setCenter({ lat, lng });
                        this.marker.position = { lat, lng };
                    },

                    codeAddress() {
                        if (!this.geocoder || !this.localAddress) return;

                        this.geocoder.geocode({ address: this.localAddress }, (results, status) => {
                            if (status === 'OK') {
                                this.localAddress = results[0].formatted_address;
                                this.setLocation(results[0].geometry.location.lat(), results[0].geometry.location.lng());
                                this.$wire.set('address', this.localAddress);
                            }
                        });
                    },
                }));
            });
        </script>
    @endpush
@endonce
