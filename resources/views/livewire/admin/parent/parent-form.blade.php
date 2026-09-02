<div class="bg-white shadow px-4 py-3">
    @unless ($this->isEdit())
    <div class="flex flex-col lg:flex-row">
        <div class="tw-form-group w-full lg:w-1/3">
            <div class="lg:mr-8 md:mr-8">
                <div class="flex">
                    <div class="w-1/2 flex items-center lg:mr-8 md:mr-8">
                        <input type="radio" wire:model.live="mode" value="add">
                        <span class="text-sm mx-1">Add Parent</span>
                    </div>
                    <div class="w-1/2 flex items-center mr-2 lg:mr-8 md:mr-8">
                        <input type="radio" wire:model.live="mode" value="select">
                        <span class="text-sm mx-1">Select Parent</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endunless

    @if ($mode === 'select' && ! $this->isEdit())
    <div wire:key="select-parent">
        <div class="flex">
            <div class="tw-form-group w-full lg:w-1/3">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label class="tw-form-label">Class</label>
                    </div>
                    <div class="mb-2">
                        <select class="tw-form-control w-full" wire:model.live="standardLinkId">
                            <option value="" disabled>Select Class</option>
                            @foreach ($standardLinklist as $standardLink)
                            <option value="{{ $standardLink['id'] }}">{{ $standardLink['standard_section'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        @if ($standardLinkId !== '')
        <div class="tw-form-group w-full lg:w-1/2">
            <div class="lg:mr-8 md:mr-8">
                <div class="mb-2">
                    <label class="tw-form-label">Select Parent</label>
                </div>
                @if (empty($parentOptions))
                <p class="text-sm text-gray-500">No parents found for this class.</p>
                @else
                <div class="border rounded divide-y max-h-64 overflow-y-auto">
                    @foreach ($parentOptions as $option)
                    <label class="flex items-center gap-2 px-3 py-2 text-sm">
                        <input type="checkbox" wire:model="selectedParentIds" value="{{ $option['id'] }}">
                        <span>{{ $option['fullname'] }} <span class="text-xs text-gray-500">({{ $option['mobile_no'] }})</span></span>
                    </label>
                    @endforeach
                </div>
                @endif
                @error('selectedParentIds') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
            </div>
        </div>
        @endif

        <div class="my-6">
            <button type="button" wire:click="save" wire:loading.attr="disabled" wire:target="save" class="btn btn-primary submit-btn">Submit</button>
        </div>
    </div>
    @endif

    @if ($mode === 'add' || $this->isEdit())
    <div wire:key="add-parent">
        <div class="flex">
            <div class="tw-form-group w-full lg:w-1/3">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label class="tw-form-label">Relation:<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <select class="tw-form-control w-full" wire:model="relation">
                            <option value="" disabled>Relationship</option>
                            <option value="father">Father</option>
                            <option value="mother">Mother</option>
                            <option value="guardian">Guardian</option>
                        </select>
                    </div>
                    @error('relation') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

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

            @unless ($this->isEdit())
            <div class="tw-form-group w-full lg:w-1/3" wire:key="mobile-field">
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
            @endunless
        </div>

        <div class="flex flex-col lg:flex-row">
            <div class="tw-form-group w-full lg:w-1/3">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label class="tw-form-label">Alternate Number</label>
                    </div>
                    <div class="mb-2">
                        <input type="text" class="tw-form-control w-full" wire:model="alternate_no" placeholder="Alternate Number">
                    </div>
                    @error('alternate_no') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                </div>
            </div>

            @unless ($this->isEdit())
            <div class="tw-form-group w-full lg:w-1/3" wire:key="email-field">
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
            @endunless
        </div>

        <div class="flex flex-col lg:flex-row flex-wrap gap-y-2">
            @foreach ($qualifications as $index => $qualification)
            <div class="tw-form-group w-full lg:w-1/3" wire:key="qualification-{{ $index }}">
                <div class="lg:mr-2 md:mr-2">
                    <div class="mb-2">
                        <label class="tw-form-label">Qualification</label>
                    </div>
                    <div class="flex items-center">
                        <div class="mb-2 flex-1">
                            <select class="tw-form-control w-full" wire:model="qualifications.{{ $index }}">
                                <option value="" disabled>Select Qualification</option>
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

        <div class="flex flex-col lg:flex-row">
            <div class="tw-form-group w-full lg:w-1/3">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label class="tw-form-label">Occupation<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <select class="tw-form-control w-full" wire:model.live="profession">
                            <option value="" disabled>Occupation</option>
                            @foreach ($professions as $professionOption)
                            <option value="{{ $professionOption['num'] }}">{{ $professionOption['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('profession') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                </div>
            </div>

            @if ($this->hasOccupationDetails())
            <div class="tw-form-group w-full lg:w-1/3" wire:key="sub-occupation">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label class="tw-form-label">Sub-Category</label>
                    </div>
                    <div class="mb-2">
                        <input type="text" class="tw-form-control w-full" wire:model="sub_occupation" placeholder="Sub Category">
                    </div>
                    @error('sub_occupation') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                </div>
            </div>
            @endif
        </div>

        @if ($this->hasOccupationDetails())
        <div class="flex flex-col lg:flex-row" wire:key="occupation-details">
            <div class="tw-form-group w-full lg:w-1/3">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label class="tw-form-label">Designation</label>
                    </div>
                    <div class="mb-2">
                        <input type="text" class="tw-form-control w-full" wire:model="designation" placeholder="Designation">
                    </div>
                    @error('designation') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="tw-form-group w-full lg:w-1/3">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label class="tw-form-label">Organization Name</label>
                    </div>
                    <div class="mb-2">
                        <input type="text" class="tw-form-control w-full" wire:model="organization_name" placeholder="Organization Name">
                    </div>
                    @error('organization_name') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="tw-form-group w-full lg:w-1/3">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label class="tw-form-label">Annual Income<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <input type="text" class="tw-form-control w-full" wire:model="annual_income" placeholder="Annual Income">
                    </div>
                    @error('annual_income') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
        @endif

        <div
            class="flex flex-col lg:flex-row gap-6 my-4"
            x-data="parentAddressMap()"
            x-init="init(@js($official_address))">
            <div class="w-full lg:w-1/2">
                <label class="tw-form-label">Address</label>
                <div class="relative flex items-center">
                    <input
                        type="text"
                        x-ref="addressInput"
                        x-model="localAddress"
                        @change="$wire.set('official_address', localAddress)"
                        placeholder="Enter a location"
                        class="tw-form-control w-full pr-10">
                    <button
                        type="button"
                        @click="codeAddress()"
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

        <div class="flex flex-col lg:flex-row">
            <div class="tw-form-group w-full lg:w-1/6">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label class="tw-form-label">Siblings<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <select class="tw-form-control w-full" wire:model.live="siblings">
                            <option value="" disabled>Select Sibling</option>
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                        </select>
                    </div>
                    @error('siblings') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                </div>
            </div>

            @if ($siblings === 'yes')
            <div class="tw-form-group w-full lg:w-1/6" wire:key="siblings-count">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label class="tw-form-label">Siblings Count<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <input type="text" class="tw-form-control w-full" wire:model="siblings_count" placeholder="Siblings Count">
                    </div>
                    @error('siblings_count') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="tw-form-group w-full lg:w-4/6" wire:key="sibling-rows">
                @foreach ($siblingRows as $index => $siblingRow)
                <div class="flex flex-col lg:flex-row" wire:key="sibling-row-{{ $index }}">
                    <div class="w-full lg:w-1/4">
                        <div class="lg:mr-8 md:mr-8">
                            <div class="mb-2">
                                <label class="tw-form-label">Sibling Relation<span class="text-red-500">*</span></label>
                            </div>
                            <div class="mb-2">
                                <select wire:model="siblingRows.{{ $index }}.sibling_relation" class="tw-form-control w-full">
                                    <option value="" disabled>Select Relation</option>
                                    @foreach ($siblinglist as $siblingOption)
                                    <option value="{{ $siblingOption['id'] }}">{{ $siblingOption['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error("siblingRows.{$index}.sibling_relation") <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="w-full lg:w-1/4">
                        <div class="lg:mr-8 md:mr-8">
                            <div class="mb-2">
                                <label class="tw-form-label">Sibling Name<span class="text-red-500">*</span></label>
                            </div>
                            <div class="mb-2">
                                <input type="text" wire:model="siblingRows.{{ $index }}.sibling_name" class="tw-form-control w-full" placeholder="Sibling Name">
                            </div>
                            @error("siblingRows.{$index}.sibling_name") <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="w-full lg:w-1/4">
                        <div class="lg:mr-8 md:mr-8">
                            <div class="mb-2">
                                <label class="tw-form-label">Sibling Date Of Birth<span class="text-red-500">*</span></label>
                            </div>
                            <div class="mb-2">
                                <input type="date" wire:model="siblingRows.{{ $index }}.sibling_date_of_birth" class="tw-form-control w-full">
                            </div>
                            @error("siblingRows.{$index}.sibling_date_of_birth") <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="w-full lg:w-1/4">
                        <div class="lg:mr-8 md:mr-8">
                            <div class="mb-2">
                                <label class="tw-form-label">Sibling Class</label>
                            </div>
                            <div class="mb-2">
                                <select class="tw-form-control w-full" wire:model="siblingRows.{{ $index }}.sibling_standard">
                                    <option value="" disabled>Select Class</option>
                                    <option value="not_studying">Not Studying In This School</option>
                                    @foreach ($standardLinklist as $standardLink)
                                    <option value="{{ $standardLink['id'] }}">{{ $standardLink['standard_section'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error("siblingRows.{$index}.sibling_standard") <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    @if (count($siblingRows) > 1)
                    <button type="button" class="py-8 px-2 text-red-600" wire:click="removeSiblingRow({{ $index }})">&times;</button>
                    @endif
                </div>
                @endforeach

                <div class="w-full lg:w-1/4">
                    <div class="lg:mr-8 md:mr-8">
                        <div class="mb-2">
                            <label class="tw-form-label">Add Sibling</label>
                        </div>
                        <div class="mb-2">
                            <button type="button" class="tw-form-control w-full text-left" wire:click="addSiblingRow">+ </button>
                        </div>
                    </div>
                </div>
            </div>
            @endif
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
    @endif
</div>

@once
@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('parentAddressMap', () => ({
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
                    this.$wire.set('official_address', this.localAddress);
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
                        this.$wire.set('official_address', this.localAddress);
                    }
                });
            },
        }));
    });
</script>
@endpush
@endonce