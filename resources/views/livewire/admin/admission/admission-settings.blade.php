<div class="bg-white shadow px-4 py-3 mb-4">

    @if (session('admission-settings-success'))
    <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
        {{ session('admission-settings-success') }}
    </div>
    @endif

    <div class="flex flex-col lg:flex-row">
        <div class="tw-form-group w-full lg:w-1/3">
            <div class="lg:mr-8 md:mr-8">
                <div class="mb-2 flex items-center justify-between">
                    <label class="tw-form-label">Admission Open Status</label>
                    <div>
                        @if ($admissionOpen)
                            <span class="text-white px-4 py-1 mx-1 custom-green rounded">Open</span>
                            <a href="{{ url('/'.$schoolSlug.'/admission-form') }}" target="_blank" class="text-white px-4 py-1 mx-1 blue-bg rounded">View</a>
                        @else
                            <span class="text-white px-4 py-1 mx-1 bg-red-500 rounded">Closed</span>
                        @endif
                    </div>
                </div>
                <div class="w-full lg:w-3/4 my-2">
                    <label class="toggle-label">
                        <input type="checkbox" wire:model.live="admissionOpen">
                        <span class="back">
                            <span class="toggle"></span>
                            <span class="label on">ON</span>
                            <span class="label off">OFF</span>
                        </span>
                    </label>
                </div>
                @error('admissionOpen') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="tw-form-group w-full lg:w-1/3">
            <div class="lg:mr-8 md:mr-8">
                <div class="mb-2">
                    <label class="tw-form-label">Admission Closed Message</label>
                </div>
                <div class="w-full lg:w-3/4 my-2">
                    <textarea wire:model="closeMessage" class="tw-form-control w-full" placeholder="Admission Closed Message"></textarea>
                </div>
                @error('closeMessage') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="tw-form-group w-full lg:w-1/3">
            <div class="lg:mr-8 md:mr-8">
                <div class="mb-2">
                    <label class="tw-form-label">Admission Closes On</label>
                </div>
                <div class="w-full lg:w-3/4 my-2">
                    <input type="datetime-local" wire:model="closeOn" class="tw-form-control w-full">
                </div>
                @error('closeOn') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="tw-form-group w-full lg:w-1/3">
            <div class="lg:mr-8 md:mr-8">
                <div class="mb-2">
                    <label class="tw-form-label">Application Fee Amount</label>
                </div>
                <div class="w-full lg:w-3/4 my-2">
                    <input type="number" step="0.01" min="0" wire:model="feeAmount" class="tw-form-control w-full" placeholder="e.g. 500">
                </div>
                @error('feeAmount') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>

    <div class="py-3">
        <a href="#" class="btn-primary submit-btn blue-bg text-sm text-white px-4 py-2 rounded" wire:click="save">Save</a>
    </div>
</div>