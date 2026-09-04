<div>
    <div class="bg-white rounded shadow p-4 max-w-lg">
        <div class="mb-4">
            <label class="tw-form-label text-sm font-bold">Number of Payment Codes</label>
            <input type="number" min="1" max="500" wire:model="quantity" class="tw-form-control w-full my-1 py-2">
            @error('quantity') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="tw-form-label text-sm font-bold">Amount (per code)</label>
            <input type="number" step="0.01" min="0.01" wire:model="amount" class="tw-form-control w-full my-1 py-2">
            @error('amount') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
        </div>

        <button type="button" wire:click="save" wire:loading.attr="disabled" wire:target="save" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md transition disabled:opacity-50">
            Generate
        </button>
    </div>
</div>
