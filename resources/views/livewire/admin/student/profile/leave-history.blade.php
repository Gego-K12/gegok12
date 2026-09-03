<div class="px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto">
    <h3 class="font-semibold text-base text-gray-800 capitalize mx-1 my-2">Leave History</h3>
    <div class="flex flex-wrap custom-table my-3">
        <table class="w-full">
            <thead class="bg-grey-light">
                <tr class="border-b">
                    <th class="text-left text-sm px-2 py-2 text-grey-darker" width="20%">From</th>
                    <th class="text-left text-sm px-2 py-2 text-grey-darker" width="20%">To</th>
                    <th class="text-left text-sm px-2 py-2 text-grey-darker" width="10%">Reason</th>
                    <th class="text-left text-sm px-2 py-2 text-grey-darker" width="40%">Remarks</th>
                    <th class="text-left text-sm px-2 py-2 text-grey-darker" width="10%">Approved By</th>
                    <th class="text-left text-sm px-2 py-2 text-grey-darker" width="10%">Approved On</th>
                    <th class="text-left text-sm px-2 py-2 text-grey-darker" width="20%">Comments</th>
                    <th class="text-left text-sm px-2 py-2 text-grey-darker" width="10%">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leaves as $leave)
                    <tr class="border-b">
                        <td class="py-3 px-2"><p class="font-semibold text-xs">{{ $leave['from_date'] }}</p></td>
                        <td class="py-3 px-2"><p class="font-semibold text-xs">{{ $leave['to_date'] }}</p></td>
                        <td class="py-3 px-2"><p class="font-semibold text-xs">{{ $leave['reason'] }}</p></td>
                        <td class="py-3 px-2"><p class="font-semibold text-xs">{{ $leave['remarks'] }}</p></td>
                        <td class="py-3 px-2"><p class="font-semibold text-xs">{{ $leave['approved_by'] ?? '--' }}</p></td>
                        <td class="py-3 px-2"><p class="font-semibold text-xs">{{ $leave['approved_on'] ?? '--' }}</p></td>
                        <td class="py-3 px-2"><p class="font-semibold text-xs">{{ $leave['comments'] ?? '--' }}</p></td>
                        <td class="py-3 px-2"><p class="font-semibold text-xs">{{ $leave['status'] }}</p></td>
                    </tr>
                @empty
                    <tr class="border-b">
                        <td colspan="8"><p class="font-semibold text-s" style="text-align: center">No Records Found</p></td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="w-full mt-2">
            {{ $leaves->links() }}
        </div>
    </div>
</div>
