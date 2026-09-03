<div class="overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3">
    <div>
        <ul class="flex flex-col lg:flex-row md:flex-row list-reset leading-loose my-2 text-sm">
            <li class="flex lg:px-4 md:px-2 py-1">
                <span class="w-4 h-4 bg-green-300 mx-1 inline-block"></span>
                <p>Paid Fee</p>
            </li>
            <li class="flex pr-4 py-1">
                <span class="w-4 h-4 bg-red-300 mx-1 inline-block"></span>
                <p>Unpaid Fee</p>
            </li>
            <li class="flex lg:px-4 md:px-2 py-1">
                <span class="w-4 h-4 bg-gray-300 mx-1 inline-block"></span>
                <p>Unassigned Fee</p>
            </li>
            <li class="flex lg:px-4 md:px-2 py-1">
                <span class="text-red-500 font-medium mx-2">*</span>
                <p> - Concession Applied</p>
            </li>
        </ul>
    </div>
    <div class="flex flex-wrap custom-table mx-3 my-3">
        <table class="w-full">
            <thead class="bg-grey-light">
                <tr class="border-b">
                    <th class="text-left text-sm px-2 py-2 text-grey-darker">Fee Type</th>
                    <th class="text-left text-sm px-2 py-2 text-grey-darker">Title</th>
                    <th class="text-left text-sm px-2 py-2 text-grey-darker">Term</th>
                    <th class="text-left text-sm px-2 py-2 text-grey-darker">Amount</th>
                    <th class="text-left text-sm px-2 py-2 text-grey-darker">Paid On</th>
                    <th class="text-left text-sm px-2 py-2 text-grey-darker">Payment Type</th>
                    <th class="text-left text-sm px-2 py-2 text-grey-darker">Notify Parents</th>
                    <th class="text-left text-sm px-2 py-2 text-grey-darker">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($fees as $fee)
                    <tr class="border-b {{ $fee['bg_class'] }}" wire:key="fee-{{ $fee['id'] }}">
                        <td class="py-3 px-2"><p class="font-semibold text-xs">{{ $fee['fee_type'] }}</p></td>
                        <td class="py-3 px-2">
                            <a href="{{ url('/admin/feedetail/show/'.$fee['id']) }}" class="font-semibold text-xs">
                                {{ $fee['name'] }}
                                @if($fee['concession_applied'] == 1)
                                    <span class="text-red-500 font-semibold text-xs">*</span>
                                @endif
                            </a>
                        </td>
                        <td class="py-3 px-2"><p class="font-semibold text-xs">{{ $fee['term'] }}</p></td>
                        <td class="py-3 px-2"><p class="font-semibold text-xs">{{ $fee['amount'] }}</p></td>
                        <td class="py-3 px-2"><p class="font-semibold text-xs">{{ $fee['paid_on'] }}</p></td>
                        <td class="py-3 px-2"><p class="font-semibold text-xs">{{ $fee['payment_type'] }}</p></td>
                        <td class="py-3 px-2"><p class="font-semibold text-xs">{{ $fee['notify_parent'] }}</p></td>
                        <td class="py-3 px-2">
                            <div class="flex items-center">
                                @if($fee['feePayment_id'] !== null && (int) $fee['status'] === 0)
                                    <a href="#" wire:click.prevent="openEditAmount({{ $fee['id'] }})" title="Edit" class="mx-1">Edit</a>
                                @endif

                                @if($fee['feePayment_id'] === null)
                                    <a href="#" wire:click.prevent="assignFee({{ $fee['id'] }})" title="Assign" class="mx-1">Assign</a>
                                @endif

                                @if($fee['feePayment_id'] !== null && (int) $fee['status'] === 0)
                                    <a href="#" wire:click.prevent="resetFee({{ $fee['feePayment_id'] }})" wire:confirm="Reset this fee assignment?" title="Reset" class="mx-1">Reset</a>
                                @endif

                                @if($fee['status'] !== null)
                                    <a href="#" wire:click.prevent="openPaymentDetail({{ $fee['id'] }}, {{ $fee['feePayment_id'] }})" title="Update Payment Details" class="mx-1">Payment</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr class="border-b">
                        <td colspan="8"><p class="font-semibold text-s" style="text-align: center">No Records Found</p></td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="w-full mt-2">
            {{ $fees->links() }}
        </div>
    </div>

    @if($showModal)
        <div class="modal modal-mask" style="position: fixed; z-index: 9998; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,.5);">
            <div class="modal-wrapper px-4" style="display: table-cell; vertical-align: middle;">
                <div class="modal-container w-full max-w-md px-8 mx-auto" style="margin: 0 auto; padding: 20px 30px; background-color: #fff; border-radius: 2px; box-shadow: 0 2px 8px rgba(0,0,0,.33);">
                    <div class="modal-header flex justify-between items-center">
                        <h2>{{ $modalType === 'edit_amount' ? 'Edit Fees' : 'Add Fees Payment Detail' }}</h2>
                        <button type="button" class="modal-default-button text-2xl py-1" wire:click="closeModal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <ul class="flex flex-col lg:flex-row md:flex-row list-reset leading-loose my-2 text-sm">
                            <li class="flex lg:px-4 md:px-2 py-1"><span class="text-gray-700 font-medium mx-2">Fee Group :</span><p>{{ $displayFeeGroup }}</p></li>
                            <li class="flex pr-4 py-1"><span class="text-gray-700 font-medium mx-2">Title :</span><p>{{ $displayTitle }}</p></li>
                        </ul>
                        <ul class="flex flex-col lg:flex-row md:flex-row list-reste leading-loose my-2 text-sm">
                            <li class="flex lg:px-4 md:px-2 py-1"><span class="text-gray-700 font-medium mx-2">Start Date :</span><p>{{ $displayStartDate }}</p></li>
                            <li class="flex lg:px-4 md:px-2 py-1"><span class="text-gray-700 font-medium mx-2">Term :</span><p>{{ $displayTerm }}</p></li>
                        </ul>
                        <ul class="flex flex-col lg:flex-row md:flex-row list-reset leading-loose my-2 text-sm">
                            <li class="flex lg:px-4 md:px-2 py-1"><span class="text-gray-700 font-medium mx-2">End Date :</span><p>{{ $displayEndDate }}</p></li>
                            @if($modalType === 'edit_payment')
                                <li class="flex lg:px-4 md:px-2 py-1"><span class="text-gray-700 font-medium mx-2">Amount :</span><p>{{ $displayPaidAmount }}</p></li>
                            @endif
                        </ul>
                    </div>

                    @if($modalType === 'edit_amount')
                        <div class="modal-body">
                            <div class="flex items-center">
                                <div class="w-full lg:w-1/4"><label class="tw-form-label">Amount</label></div>
                                <div class="my-2 w-full lg:w-3/4">
                                    <input type="text" wire:model="amount" class="tw-form-control w-full" placeholder="Enter Amount">
                                    @error('amount') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div class="flex items-center">
                                <div class="w-full lg:w-1/4"><label class="tw-form-label">Comments</label></div>
                                <div class="my-2 w-full lg:w-3/4">
                                    <input type="text" wire:model="comments" class="tw-form-control w-full" placeholder="Enter Comments">
                                    @error('comments') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="my-6">
                            <button type="button" wire:click="updateFee" wire:loading.attr="disabled" wire:target="updateFee" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium">Update</button>
                        </div>
                    @else
                        <div class="modal-body">
                            <div class="flex">
                                <div class="w-full lg:w-1/4"><label class="tw-form-label">Paid On</label></div>
                                <div class="w-full lg:w-3/4">
                                    <input type="date" wire:model="paid_on" class="tw-form-control w-full">
                                    @error('paid_on') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div class="flex">
                                <div class="w-full lg:w-1/4"><label class="tw-form-label">Payment Type</label></div>
                                <div class="w-full lg:w-3/4">
                                    <select wire:model="payment_type" class="tw-form-control w-full">
                                        <option value="">Select payment</option>
                                        <option value="cash">Cash</option>
                                        <option value="bank">Bank</option>
                                    </select>
                                    @error('payment_type') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div class="flex items-center">
                                <div class="w-6"><input type="checkbox" wire:model="notify_parent" class="tw-form-control w-full"></div>
                                <div class="mx-1"><label class="tw-form-label">Notify Parent</label></div>
                            </div>
                        </div>
                        <div class="my-6">
                            <button type="button" wire:click="addPaymentDetail" wire:loading.attr="disabled" wire:target="addPaymentDetail" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium">Submit</button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
