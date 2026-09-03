<div class="px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto">
    <div class="flex flex-wrap lg:flex-row justify-between">
        <div></div>
        <div class="flex items-center">
            @if(! $account)
                <a href="#" wire:click.prevent="openAddModal" class="no-underline text-white px-4 my-3 mx-1 flex items-center custom-green py-1 justify-center">
                    <span class="mx-1 text-sm font-semibold">Add</span>
                </a>
            @endif
        </div>
    </div>

    <div class="custom-table mx-3 my-3">
        <table class="w-full overflow-x-auto">
            <thead>
                <tr>
                    <th>Bank Name</th>
                    <th>Account Number</th>
                    <th>IFSC code</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @if($account)
                    <tr>
                        <td>{{ $account->name }}</td>
                        <td>{{ $account->account_number }}</td>
                        <td>{{ $account->ifsc_code }}</td>
                        <td>
                            <a href="#" wire:click.prevent="openEditModal({{ $account->id }})" class="text-blue-600">Edit</a>
                        </td>
                    </tr>
                @else
                    <tr>
                        <td colspan="4"><p class="font-semibold text-s" style="text-align: center">No Records Found</p></td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    @if($showModal)
        <div class="modal modal-mask" style="position: fixed; z-index: 9998; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,.5);">
            <div class="modal-wrapper px-4" style="display: table-cell; vertical-align: middle;">
                <div class="modal-container w-full max-w-md px-8 mx-auto" style="margin: 0 auto; padding: 20px 30px; background-color: #fff; border-radius: 2px; box-shadow: 0 2px 8px rgba(0,0,0,.33);">
                    <div class="modal-header flex justify-between items-center">
                        <h2>{{ $editingId ? 'Edit Bank Details' : 'Add Bank Details' }}</h2>
                        <button type="button" class="modal-default-button text-2xl py-1" wire:click="closeModal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="flex items-center">
                            <div class="w-full lg:w-1/4"><label class="tw-form-label">Bank Name</label></div>
                            <div class="my-2 w-full lg:w-3/4">
                                <input type="text" wire:model="bank_name" class="tw-form-control w-full">
                                @error('bank_name') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="flex items-center">
                            <div class="w-full lg:w-1/4"><label class="tw-form-label">Key</label></div>
                            <div class="my-2 w-full lg:w-3/4">
                                <input type="text" wire:model="key" class="tw-form-control w-full" placeholder="Eg:SBI">
                                @error('key') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="flex items-center">
                            <div class="w-full lg:w-1/4"><label class="tw-form-label">Account Number</label></div>
                            <div class="my-2 w-full lg:w-3/4">
                                <input type="text" wire:model="account_number" class="tw-form-control w-full" placeholder="Enter account number">
                                @error('account_number') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="flex items-center">
                            <div class="w-full lg:w-1/4"><label class="tw-form-label">IFSC code</label></div>
                            <div class="my-2 w-full lg:w-3/4">
                                <input type="text" wire:model="ifsc_code" class="tw-form-control w-full">
                                @error('ifsc_code') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="my-6">
                        <button type="button" wire:click="save" wire:loading.attr="disabled" wire:target="save" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
