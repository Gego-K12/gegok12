<div class="bg-white shadow px-4 py-3 mb-4">

    @if (session('admission-settings-success'))
    <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
        {{ session('admission-settings-success') }}
    </div>
    @endif

    <div class="tw-form-group flex flex-col lg:flex-row items-start lg:items-center py-3 border-b">
        <label class="tw-form-label w-full lg:w-1/4">Admission Open Status</label>
        <div class="w-full lg:w-3/4 flex items-center flex-wrap gap-3">
            <label class="toggle-label">
                <input type="checkbox" wire:model.live="admissionOpen">
                <span class="back">
                    <span class="toggle"></span>
                    <span class="label on">ON</span>
                    <span class="label off">OFF</span>
                </span>
            </label>
            @if ($admissionOpen)
                <span class="text-white px-4 py-1 custom-green rounded">Open</span>
                <a href="{{ url('/'.$schoolSlug.'/admission-form') }}" target="_blank" class="text-white px-4 py-1 blue-bg rounded">View</a>
            @else
                <span class="text-white px-4 py-1 bg-red-500 rounded">Closed</span>
            @endif
            @error('admissionOpen') <span class="text-red-500 text-xs font-semibold w-full">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="tw-form-group flex flex-col lg:flex-row items-start lg:items-center py-3 border-b">
        <label class="tw-form-label w-full lg:w-1/4">Admission Closed Message</label>
        <div class="w-full lg:w-3/4">
            <textarea wire:model="closeMessage" class="tw-form-control w-full" placeholder="Admission Closed Message"></textarea>
            @error('closeMessage') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="tw-form-group flex flex-col lg:flex-row items-start lg:items-center py-3 border-b">
        <label class="tw-form-label w-full lg:w-1/4">Admission Closes On</label>
        <div class="w-full lg:w-3/4">
            <input type="datetime-local" wire:model="closeOn" class="tw-form-control w-full lg:w-1/2">
            @error('closeOn') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="tw-form-group flex flex-col lg:flex-row items-start lg:items-center py-3">
        <label class="tw-form-label w-full lg:w-1/4">Application Fee Amount</label>
        <div class="w-full lg:w-3/4">
            <input type="number" step="0.01" min="0" wire:model="feeAmount" class="tw-form-control w-full lg:w-1/2" placeholder="e.g. 500">
            @error('feeAmount') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="py-3">
        <a href="#" class="btn-primary submit-btn blue-bg text-sm text-white px-4 py-2 rounded" wire:click="save">Save</a>
    </div>
</div>
