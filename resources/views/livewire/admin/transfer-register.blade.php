<div class="space-y-4">
    <!-- Heading and Actions Row -->
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-900">Bank In/Out Records</h2>

        <div class="flex gap-2">
            <input type="text" wire:model.live="search" placeholder="Search by description, reference..."
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-80">
            <button wire:click="openForm" class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                <i class="fas fa-plus"></i>
                <span>Add Transfer</span>
            </button>
        </div>
    </div>

    <!-- Transfer Table -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Date</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">From Account</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">To Account</th>
                        <th class="px-6 py-3 text-right text-sm font-semibold text-gray-700">Amount</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Description</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($transfers as $transfer)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $transfer->transaction_date->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $transfer->account->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $transfer->toAccount->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 text-right font-semibold">₹{{ number_format($transfer->amount, 2) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $transfer->description ?? '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                <button wire:click="edit({{ $transfer->id }})" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Edit</button>
                                <span class="text-gray-300 mx-2">|</span>
                                <button wire:click="delete({{ $transfer->id }})" wire:confirm="Delete this transfer?" class="text-red-600 hover:text-red-800 text-sm font-medium">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center">
                                <div class="text-gray-500">
                                    <i class="fas fa-inbox text-3xl mb-2"></i>
                                    <p class="mt-2">No transfers found</p>
                                    <p class="text-sm text-gray-400">Click "Add Transfer" to record your first transfer</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($transfers->hasPages())
        <div class="mt-4">
            {{ $transfers->links() }}
        </div>
    @endif

    <!-- Modal Form -->
    @if($showForm)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4" style="z-index: 60;">
            <div class="bg-white rounded-lg shadow-lg max-w-2xl w-full max-h-[75vh] flex flex-col">
                <div class="px-6 py-4 border-b border-gray-200 flex-shrink-0">
                    <h3 class="text-lg font-bold text-gray-900">{{ $editingId ? 'Edit Transfer' : 'Add Transfer' }}</h3>
                </div>

                <form wire:submit="save" class="flex-1 flex flex-col">
                    <!-- Scrollable Content -->
                    <div class="flex-1 overflow-y-auto p-6 space-y-6" style="min-height: 0;">
                        <!-- Row 1: From Account, To Account -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">From Account <span class="text-red-500">*</span></label>
                                <select wire:model="account_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Select Account</option>
                                    @foreach($accounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->name }}</option>
                                    @endforeach
                                </select>
                                @error('account_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">To Account <span class="text-red-500">*</span></label>
                                <select wire:model="to_account_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Select Account</option>
                                    @foreach($accounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->name }}</option>
                                    @endforeach
                                </select>
                                @error('to_account_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Row 2: Date, Amount -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Transaction Date <span class="text-red-500">*</span></label>
                                <input type="date" wire:model="transaction_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('transaction_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Amount <span class="text-red-500">*</span></label>
                                <input type="number" step="0.01" wire:model="amount" placeholder="0.00" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Row 3: Payment Method -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method <span class="text-red-500">*</span></label>
                                <select wire:model="payment_method" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="bank_transfer">Bank Transfer</option>
                                    <option value="cash">Cash</option>
                                    <option value="cheque">Cheque</option>
                                    <option value="upi">UPI</option>
                                    <option value="other">Other</option>
                                </select>
                                @error('payment_method') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Reference Number</label>
                                <input type="text" wire:model="reference_number" placeholder="Reference #" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('reference_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        @if($payment_method === 'cheque')
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Cheque Number</label>
                                <input type="text" wire:model="cheque_number" placeholder="Cheque #" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('cheque_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        <!-- Row 4: Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea wire:model="description" placeholder="Add notes..." class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none" rows="2"></textarea>
                            @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Sticky Footer -->
                    <div class="px-6 py-4 border-t border-gray-200 flex-shrink-0 bg-white flex gap-3 justify-end">
                        <button type="button" wire:click="closeForm" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 text-sm font-medium">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
                            {{ $editingId ? 'Update' : 'Create' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
