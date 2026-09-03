<div class="bg-white shadow px-4 py-3">
    <h1>Personal Details</h1>

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

    @unless ($this->isEdit())
    <div class="flex flex-col lg:flex-row">
        <div class="tw-form-group w-full lg:w-1/2">
            <div class="lg:mr-8 md:mr-8">
                <div class="mb-2">
                    <label class="tw-form-label">Mobile Number</label>
                </div>
                <div class="mb-2">
                    <input type="text" class="tw-form-control w-full" wire:model="mobile_no" placeholder="Mobile Number">
                </div>
                @error('mobile_no') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="tw-form-group w-full lg:w-1/2">
            <div class="lg:mr-8 md:mr-8">
                <div class="mb-2">
                    <label class="tw-form-label">Email ID</label>
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
                        <span class="text-sm mx-1">Boy</span>
                    </div>
                    <div class="w-1/2 flex items-center">
                        <input type="radio" wire:model="gender" value="female">
                        <span class="text-sm mx-1">Girl</span>
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

    <div
        class="flex flex-col lg:flex-row gap-6 my-4"
        x-data="studentAddressMap()"
        x-init="init(@js($address))">
        <div class="w-full lg:w-1/2">
            <label class="tw-form-label">Address</label>
            <div class="relative flex items-center">
                <input
                    type="text"
                    x-ref="addressInput"
                    x-model="localAddress"
                    x-on:change="$wire.set('address', localAddress)"
                    placeholder="Enter a location"
                    class="tw-form-control w-full pr-10">
                <button
                    type="button"
                    x-on:click="codeAddress()"
                    class="absolute right-0 inset-y-0 flex items-center justify-center w-10 text-gray-400 hover:text-gray-600"
                    style="background: transparent; border: none; cursor: pointer;">
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
                    <label class="tw-form-label">Pincode</label>
                </div>
                <div class="mb-2">
                    <input type="text" class="tw-form-control w-full" wire:model="pincode" placeholder="Enter Pincode">
                </div>
                @error('pincode') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>

    <div class="tw-form-group">
        <div class="flex flex-col lg:flex-row">
            <div class="w-full lg:w-1/4 lg:mr-8 md:mr-8">
                <div class="mb-2">
                    <label class="tw-form-label">Birth Place</label>
                </div>
                <div class="mb-2">
                    <input type="text" class="tw-form-control w-full" wire:model="birth_place" placeholder="Birth Place">
                </div>
                @error('birth_place') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
            </div>

            <div class="w-full lg:w-1/4 lg:mr-8 md:mr-8">
                <div class="mb-2">
                    <label class="tw-form-label">Native Place</label>
                </div>
                <div class="mb-2">
                    <input type="text" class="tw-form-control w-full" wire:model="native_place" placeholder="Native Place">
                </div>
                @error('native_place') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
            </div>

            <div class="w-full lg:w-1/4 lg:mr-8 md:mr-8">
                <div class="mb-2">
                    <label class="tw-form-label">Mother Tongue<span class="text-red-500">*</span></label>
                </div>
                <div class="mb-2">
                    <input type="text" class="tw-form-control w-full" wire:model="mother_tongue" placeholder="Mother Tongue">
                </div>
                @error('mother_tongue') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
            </div>

            <div class="w-full lg:w-1/4 lg:mr-8 md:mr-8">
                <div class="mb-2">
                    <label class="tw-form-label">Caste<span class="text-red-500">*</span></label>
                </div>
                <div class="mb-2">
                    <select class="tw-form-control w-full" wire:model="caste">
                        <option value="" disabled>Select Caste</option>
                        @foreach ($casteList as $casteOption)
                        <option value="{{ $casteOption['id'] }}">{{ $casteOption['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                @error('caste') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
            </div>

            <div class="w-full lg:w-1/4 lg:mr-8 md:mr-8">
                <div class="mb-2">
                    <label class="tw-form-label">Sub Caste</label>
                </div>
                <div class="mb-2">
                    <input type="text" class="tw-form-control w-full" wire:model="sub_caste" placeholder="Sub Caste">
                </div>
                @error('sub_caste') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row">
        <div class="tw-form-group w-full lg:w-1/3">
            <div class="lg:mr-8 md:mr-8">
                <div class="mb-2">
                    <label class="tw-form-label">Mode Of Transport</label>
                </div>
                <div class="mb-2">
                    <select class="tw-form-control w-full" wire:model.live="mode_of_transport">
                        <option value="" disabled>Select Transport</option>
                        @foreach ($transportList as $transport)
                        <option value="{{ $transport['id'] }}">{{ $transport['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                @error('mode_of_transport') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
            </div>
        </div>

        @if (in_array($mode_of_transport, ['auto', 'rickshaw', 'taxi']))
        <div class="tw-form-group w-full lg:w-1/2" wire:key="driver-fields">
            <div class="flex flex-col lg:flex-row">
                <div class="w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8">
                        <div class="mb-2">
                            <label class="tw-form-label">Driver Name<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2">
                            <input type="text" class="tw-form-control w-full" wire:model="driver_name" placeholder="Driver Name">
                        </div>
                        @error('driver_name') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8">
                        <div class="mb-2">
                            <label class="tw-form-label">Driver Contact Number<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2">
                            <input type="text" class="tw-form-control w-full" wire:model="driver_contact_number" placeholder="Driver Contact Number">
                        </div>
                        @error('driver_contact_number') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="flex flex-col lg:flex-row">
        @unless ($this->isEdit())
        <div class="tw-form-group w-full lg:w-1/3" wire:key="avatar-field">
            <div class="lg:mr-8 md:mr-8">
                <div class="mb-2">
                    <label class="tw-form-label">Avatar<span class="text-red-500">*</span></label>
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

        <div class="tw-form-group w-full lg:w-1/2" wire:key="notes-field">
            <div class="lg:mr-8 md:mr-8">
                <div class="mb-2">
                    <label class="tw-form-label">Notes</label>
                </div>
                <div class="mb-2">
                    <textarea class="tw-form-control w-full" wire:model="notes" rows="3"></textarea>
                </div>
                @error('notes') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>

    @if($customFields->isNotEmpty())
    <hr style="border-width: 1px;">
    <h2 class="text-lg font-semibold mb-2 mt-4">Additional Info</h2>

    @foreach ($customFields as $field)
    @include('livewire.admin.student.partials.custom-field', [
    'field' => $field,
    'existingFilePath' => $existingCustomFieldFiles[$field->id] ?? null,
    ])
    @endforeach
    @endif

    <hr style="border-width: 1px;">
    <h1>Academic Details</h1>

    <div class="flex flex-col lg:flex-row">
        <div class="tw-form-group w-full lg:w-1/3">
            <div class="lg:mr-8 md:mr-8">
                <div class="mb-2">
                    <label class="tw-form-label">Admission Number<span class="text-red-500">*</span></label>
                </div>
                <div class="mb-2">
                    <input type="text" class="tw-form-control w-full" wire:model="registration_number" placeholder="Admission Number">
                </div>
                @error('registration_number') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="tw-form-group w-full lg:w-1/3">
            <div class="lg:mr-8 md:mr-8">
                <div class="mb-2">
                    <label class="tw-form-label">EMIS Number</label>
                </div>
                <div class="mb-2">
                    <input type="text" class="tw-form-control w-full" wire:model="EMIS_number" placeholder="EMIS Number">
                </div>
                @error('EMIS_number') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
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
    </div>

    <div class="flex flex-col lg:flex-row">
        <div class="tw-form-group w-full lg:w-1/3">
            <div class="lg:mr-8 md:mr-8">
                <div class="mb-2">
                    <label class="tw-form-label">Class<span class="text-red-500">*</span></label>
                </div>
                <div class="mb-2">
                    <select class="tw-form-control w-full" wire:model.live="standard">
                        <option value="" disabled>Select Class</option>
                        @foreach ($standardLinklist as $standardLink)
                        <option value="{{ $standardLink['id'] }}">{{ $standardLink['standard_section'] }}</option>
                        @endforeach
                    </select>
                </div>
                @error('standard') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="tw-form-group w-full lg:w-1/3">
            <div class="lg:mr-8 md:mr-8">
                <div class="mb-2">
                    <label class="tw-form-label">Roll Number<span class="text-red-500">*</span></label>
                </div>
                <div class="mb-2">
                    <input type="text" class="tw-form-control w-full" wire:model="roll_number" placeholder="Roll Number">
                </div>
                @error('roll_number') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="tw-form-group w-full lg:w-1/3">
            <div class="lg:mr-8 md:mr-8">
                <div class="mb-2">
                    <label class="tw-form-label">ID Card Number</label>
                </div>
                <div class="mb-2">
                    <input type="text" class="tw-form-control w-full" wire:model="id_card_number" placeholder="ID Card Number">
                </div>
                @error('id_card_number') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="tw-form-group w-full lg:w-1/3">
            <div class="lg:mr-8 md:mr-8">
                <div class="mb-2">
                    <label class="tw-form-label whitespace-no-wrap">Board Registration Number<span class="text-red-500 whitespace-no-wrap text-xs">*Only For Class X, XI, XII</span></label>
                </div>
                <div class="mb-2">
                    <input type="text" class="tw-form-control w-full" wire:model="board_registration_number" placeholder="Board Registration Number">
                </div>
                @error('board_registration_number') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>

    <div class="my-6">
        <button type="button" wire:click="save" wire:loading.attr="disabled" wire:target="save" class="btn btn-primary submit-btn">Submit</button>
        @unless ($this->isEdit())
        <button type="button" wire:click="resetForm" class="btn btn-reset reset-btn">Reset</button>
        @endunless
    </div>
</div>

@once
@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('studentAddressMap', () => ({
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
                const {
                    Map
                } = await google.maps.importLibrary('maps');
                const {
                    AdvancedMarkerElement
                } = await google.maps.importLibrary('marker');

                const center = {
                    lat: 9.9252007,
                    lng: 78.1197754
                };

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
                    this.geocoder.geocode({
                        address: this.localAddress
                    }, (results, status) => {
                        if (status === 'OK') {
                            this.setLocation(results[0].geometry.location.lat(), results[0].geometry.location.lng());
                        }
                    });
                }
            },

            setLocation(lat, lng) {
                this.map.setCenter({
                    lat,
                    lng
                });
                this.marker.position = {
                    lat,
                    lng
                };
            },

            codeAddress() {
                if (!this.geocoder || !this.localAddress) return;

                this.geocoder.geocode({
                    address: this.localAddress
                }, (results, status) => {
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