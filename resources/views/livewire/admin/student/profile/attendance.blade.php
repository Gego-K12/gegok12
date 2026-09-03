<div class="px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3">
    <div class="flex flex-wrap custom-table mx-3 my-3">
        <table class="w-full overflow-x-auto">
            <thead class="bg-grey-light">
                <tr class="border-b">
                    <th class="text-left text-sm px-2 py-2 text-grey-darker">Date</th>
                    <th class="text-left text-sm px-2 py-2 text-grey-darker">Session</th>
                    <th class="text-left text-sm px-2 py-2 text-grey-darker">Reason For Absent</th>
                    <th class="text-left text-sm px-2 py-2 text-grey-darker">Remarks</th>
                    <th class="text-left text-sm px-2 py-2 text-grey-darker">Recorded By</th>
                    <th class="text-left text-sm px-2 py-2 text-grey-darker">Recorded On</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $attendance)
                    <tr class="border-b">
                        <td class="py-3 px-2"><p class="font-semibold text-xs">{{ $attendance['date'] }}</p></td>
                        <td class="py-3 px-2"><p class="font-semibold text-xs">{{ $attendance['session'] }}</p></td>
                        <td class="py-3 px-2"><p class="font-semibold text-xs">{{ $attendance['reason'] }}</p></td>
                        <td class="py-3 px-2"><p class="font-semibold text-xs">{{ $attendance['remarks'] }}</p></td>
                        <td class="py-3 px-2"><p class="font-semibold text-xs"><a href="{{ url('/admin/teacher/show/'.$attendance['recorded_by_name']) }}">{{ $attendance['recorded_by'] }}</a></p></td>
                        <td class="py-3 px-2"><p class="font-semibold text-xs">{{ $attendance['recorded_on'] }}</p></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6"><p class="font-semibold text-s" style="text-align: center">No Records Found</p></td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
