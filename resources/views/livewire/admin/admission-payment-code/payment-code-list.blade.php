<div>
    <div class="bg-white rounded shadow p-4">

        @if (session()->has('success'))
        <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
        @endif

        <div class="flex flex-wrap justify-between items-center mb-4 gap-2">
            <a
                href="{{ url('admin/admission-payment-codes/create') }}"
                class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">
                Generate Payment Codes
            </a>

            <div class="flex flex-wrap items-center gap-2">
                <input
                    type="text"
                    wire:model.live.debounce.500ms="search"
                    placeholder="Search code..."
                    class="border rounded px-3 py-2 text-sm">

                <select wire:model.live="status" class="border rounded px-3 py-2 text-sm">
                    <option value="">All Status</option>
                    <option value="unused">Unused</option>
                    <option value="used">Used</option>
                </select>

                <input type="date" wire:model.live="fromDate" class="border rounded px-3 py-2 text-sm">
                <span class="text-gray-500 text-sm">to</span>
                <input type="date" wire:model.live="toDate" class="border rounded px-3 py-2 text-sm">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border px-4 py-2 text-left">Code</th>
                        <th class="border px-4 py-2 text-left">Amount</th>
                        <th class="border px-4 py-2 text-left">Status</th>
                        <th class="border px-4 py-2 text-left">Generated On</th>
                        <th class="border px-4 py-2 text-left">Used By</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($codes as $code)
                    <tr>
                        <td class="border px-4 py-2 font-mono">{{ $code->code }}</td>
                        <td class="border px-4 py-2">{{ number_format($code->amount, 2) }}</td>
                        <td class="border px-4 py-2">
                            <span class="inline-block px-2 py-0.5 rounded text-xs {{ $code->status === 'used' ? 'bg-gray-200 text-gray-700' : 'bg-green-100 text-green-700' }}">
                                {{ ucfirst($code->status) }}
                            </span>
                        </td>
                        <td class="border px-4 py-2">{{ $code->created_at?->format('d M Y, H:i') }}</td>
                        <td class="border px-4 py-2">


                            @if ($code->status === 'used' && $code->entity_type === 'admission' && isset($admissionNames[$code->entity_id]))
                            <a href="{{ url('admin/admission/view/' . $code->entity_id) }}" class="text-blue-600 hover:underline">
                                {{ $admissionNames[$code->entity_id] }}
                            </a>
                            @else
                            <span class="text-gray-400">&mdash;</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="border px-4 py-3 text-center text-gray-500">
                            No payment codes found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $codes->links() }}
        </div>
    </div>
</div>