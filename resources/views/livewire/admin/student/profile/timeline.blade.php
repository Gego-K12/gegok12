<div class="overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3">
    <div class="flex flex-wrap custom-table mx-3 my-3">
        <table class="w-full">
            <thead class="bg-grey-light">
                <tr class="border-b">
                    <th class="text-left text-sm px-2 py-2 text-grey-darker">Date and Time</th>
                    <th class="text-left text-sm px-2 py-2 text-grey-darker">Action</th>
                    <th class="text-left text-sm px-2 py-2 text-grey-darker">Description</th>
                    <th class="text-left text-sm px-2 py-2 text-grey-darker">IP</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr class="border-b">
                        <td class="py-3 px-2"><p class="font-semibold text-xs">{{ $log['created_at'] }}</p></td>
                        <td class="py-3 px-2"><p class="font-semibold text-xs">{{ $log['log_name'] }}</p></td>
                        <td class="py-3 px-2"><p class="font-semibold text-xs">{{ $log['description'] }}</p></td>
                        <td class="py-3 px-2"><p class="font-semibold text-xs">{{ $log['ip'] }}</p></td>
                    </tr>
                @empty
                    <tr class="border-b">
                        <td colspan="4"><p class="font-semibold text-s" style="text-align: center">No Records Found</p></td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="w-full mt-2">
            {{ $logs->links() }}
        </div>
    </div>
</div>
