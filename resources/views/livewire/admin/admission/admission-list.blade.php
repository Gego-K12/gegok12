<div class="relative">
    @if (session()->has('success'))
        <div class="alert alert-success mt-2">{{ session('success') }}</div>
    @endif

    <div class="flex flex-wrap lg:flex-row justify-between items-center">
        <div class="">
            <h1 class="admin-h1 my-3">Admission</h1>
        </div>
    </div>

    <div class="flex flex-wrap gap-3 my-3">
        <div class="bg-white shadow px-4 py-3 flex-1 min-w-[180px]">
            <p class="text-xs text-gray-500">Total Application Fees</p>
            <p class="text-lg font-bold text-gray-800">&#8377;{{ number_format($totalFees, 2) }}</p>
        </div>
        <div class="bg-white shadow px-4 py-3 flex-1 min-w-[180px]">
            <p class="text-xs text-gray-500">Payment Code</p>
            <p class="text-lg font-bold text-gray-800">&#8377;{{ number_format($applicationCodeTotal, 2) }}</p>
        </div>
        <div class="bg-white shadow px-4 py-3 flex-1 min-w-[180px]">
            <p class="text-xs text-gray-500">Razorpay</p>
            <p class="text-lg font-bold text-gray-800">&#8377;{{ number_format($razorpayTotal, 2) }}</p>
        </div>
    </div>

    <div class="bg-white shadow px-4 py-3 my-3">
        <div class="flex flex-wrap items-end gap-3">
            <div>
                <label class="tw-form-label text-xs">From Date</label>
                <input type="date" wire:model.live="fromDate" class="tw-form-control">
            </div>
            <div>
                <label class="tw-form-label text-xs">To Date</label>
                <input type="date" wire:model.live="toDate" class="tw-form-control">
            </div>
            <div>
                <label class="tw-form-label text-xs">Payment Status</label>
                <select wire:model.live="statusFilter" class="tw-form-control">
                    <option value="">All</option>
                    <option value="paid">Paid</option>
                    <option value="pending">Pending</option>
                </select>
            </div>
            <div>
                <label class="tw-form-label text-xs">Payment Mode</label>
                <select wire:model.live="modeFilter" class="tw-form-control">
                    <option value="">All</option>
                    <option value="application_code">Application Code</option>
                    <option value="razorpay">Razorpay</option>
                </select>
            </div>
            <div>
                <button type="button" wire:click="resetFilters" class="text-sm border bg-gray-100 text-grey-darkest py-2 px-4">Reset</button>
            </div>
        </div>
    </div>

    <div class="">
        <div class="flex flex-wrap custom-table my-3 overflow-auto">
            <table class="w-full">
                <thead class="bg-grey-light">
                    <tr class="border-b">
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">Application Number</th>
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">Name</th>
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">Class Applied For</th>
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">Application Date</th>
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">Application Status</th>
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">Payment Mode</th>
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">Payment Status</th>
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($admissions as $admission)
                        <tr class="border-b" wire:key="admission-{{ $admission->id }}">
                            <td class="py-3 px-2">
                                <p class="font-semibold text-xs">{{ $admission->application_no }}</p>
                            </td>
                            <td class="py-3 px-2">
                                <p class="font-semibold text-xs">{{ $admission->name }}</p>
                            </td>
                            <td class="py-3 px-2">
                                <p class="font-semibold text-xs">{{ strtoupper(optional($admission->standard)->name ?? '') }}</p>
                            </td>
                            <td class="py-3 px-2">
                                <p class="font-semibold text-xs">{{ $admission->created_at?->format('d M Y') }}</p>
                            </td>
                            <td class="py-3 px-2">
                                <p class="font-semibold text-xs">{{ $admission->application_status }}</p>
                            </td>
                            <td class="py-3 px-2">
                                <p class="font-semibold text-xs">
                                    @if ($admission->payment_mode === 'razorpay') Razorpay
                                    @elseif ($admission->payment_mode === 'application_code') Application Code
                                    @else - @endif
                                </p>
                            </td>
                            <td class="py-3 px-2">
                                <span class="inline-block px-2 py-0.5 rounded text-xs font-semibold {{ $admission->application_payment_status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ ucfirst($admission->application_payment_status ?: 'pending') }}
                                </span>
                                @if ($admission->application_payment_status !== 'paid')
                                    <button type="button" wire:click="openApplyCodeModal({{ $admission->id }})" class="block text-blue-600 hover:underline text-xs mt-1">
                                        Apply Code
                                    </button>
                                @endif
                            </td>
                            <td class="py-3 px-2">
                                <div class="flex items-center">
                                    <a href="{{ url('/admin/admission/view/' . $admission->id) }}" class="cursor-pointer" title="View">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-4 h-4 fill-current text-black-500 mx-1"><path d="M12 5c-7.633 0-11.5 6.68-11.5 6.68a1 1 0 0 0 0 .64S4.367 19 12 19s11.5-6.68 11.5-6.68a1 1 0 0 0 0-.64S19.633 5 12 5zm0 12c-5.351 0-8.615-4.443-9.463-5.68C3.385 10.083 6.65 6 12 6s8.615 4.443 9.463 5.68c-.848 1.237-4.112 5.32-9.463 5.32z"></path><path d="M12 8.5a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7zm0 5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"></path></svg>
                                    </a>

                                    <a href="{{ url('/admin/admission/edit/' . $admission->id) }}" class="cursor-pointer" title="Edit">
                                        <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 477.873 477.873" xml:space="preserve" class="w-4 h-4 fill-current text-black-500 mx-1"><g><g><path d="M392.533,238.937c-9.426,0-17.067,7.641-17.067,17.067V426.67c0,9.426-7.641,17.067-17.067,17.067H51.2 c-9.426,0-17.067-7.641-17.067-17.067V85.337c0-9.426,7.641-17.067,17.067-17.067H256c9.426,0,17.067-7.641,17.067-17.067 S265.426,34.137,256,34.137H51.2C22.923,34.137,0,57.06,0,85.337V426.67c0,28.277,22.923,51.2,51.2,51.2h307.2 c28.277,0,51.2-22.923,51.2-51.2V256.003C409.6,246.578,401.959,238.937,392.533,238.937z"></path></g></g> <g><g><path d="M458.742,19.142c-12.254-12.256-28.875-19.14-46.206-19.138c-17.341-0.05-33.979,6.846-46.199,19.149L141.534,243.937 c-1.865,1.879-3.272,4.163-4.113,6.673l-34.133,102.4c-2.979,8.943,1.856,18.607,10.799,21.585 c1.735,0.578,3.552,0.873,5.38,0.875c1.832-0.003,3.653-0.297,5.393-0.87l102.4-34.133c2.515-0.84,4.8-2.254,6.673-4.13 l224.802-224.802C484.25,86.023,484.253,44.657,458.742,19.142z M434.603,87.419L212.736,309.286l-66.287,22.135l22.067-66.202 L390.468,43.353c12.202-12.178,31.967-12.158,44.145,0.044c5.817,5.829,9.095,13.72,9.12,21.955 C443.754,73.631,440.467,81.575,434.603,87.419z"></path></g></g></svg>
                                    </a>

                                    <a href="#" wire:click.prevent="delete({{ $admission->id }})" wire:confirm="Do you want to delete this admission?" title="Delete">
                                        <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" xml:space="preserve" class="w-4 h-4 fill-current text-black-500 mx-1"><g><g><g><polygon points="353.574,176.526 313.496,175.056 304.807,412.34 344.885,413.804"></polygon><rect x="235.948" y="175.791" width="40.104" height="237.285"></rect><polygon points="207.186,412.334 198.497,175.049 158.419,176.52 167.109,413.804"></polygon> <path d="M17.379,76.867v40.104h41.789L92.32,493.706C93.229,504.059,101.899,512,112.292,512h286.74 c10.394,0,19.07-7.947,19.972-18.301l33.153-376.728h42.464V76.867H17.379z M380.665,471.896H130.654L99.426,116.971h312.474 L380.665,471.896z"></path></g></g></g> <g><g><path d="M321.504,0H190.496c-18.428,0-33.42,14.992-33.42,33.42v63.499h40.104V40.104h117.64v56.815h40.104V33.42 C354.924,14.992,339.932,0,321.504,0z"></path></g></g></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="border-b">
                            <td colspan="8">
                                <p class="font-semibold text-s" style="text-align: center">No Records Found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $admissions->links() }}
        </div>
    </div>

    @if ($applyCodeAdmissionId)
        <div class="modal modal-mask" style="position: fixed; z-index: 9998; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,.5);">
            <div class="modal-wrapper px-4" style="display: table-cell; vertical-align: middle;">
                <div class="modal-container w-full max-w-md px-8 mx-auto" style="margin: 0 auto; padding: 20px 30px; background-color: #fff; border-radius: 2px; box-shadow: 0 2px 8px rgba(0,0,0,.33);">
                    <div class="modal-header flex justify-between items-center">
                        <h2>Apply Payment Code</h2>
                        <button type="button" class="modal-default-button text-2xl py-1" wire:click="closeApplyCodeModal">&times;</button>
                    </div>

                    <div class="modal-body my-3">
                        <label class="tw-form-label"><h6 class="text-sm font-bold mb-3">Payment Code</h6></label>
                        <input type="text" wire:model="applyCode" placeholder="Payment Code" class="tw-form-control w-full my-1 py-2">
                        @error('applyCode') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                        @if ($applyCodeError)
                            <p class="text-red-500 text-xs font-semibold mt-1">{{ $applyCodeError }}</p>
                        @endif
                    </div>

                    <div class="my-6">
                        <button type="button" wire:click="applyPaymentCode" wire:loading.attr="disabled" wire:target="applyPaymentCode" class="btn btn-primary submit-btn">Apply</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
