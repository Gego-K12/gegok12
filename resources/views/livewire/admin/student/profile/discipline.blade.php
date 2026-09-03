<div class="px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3">
    <div class="custom-table">
        <table class="w-full overflow-x-auto">
            <thead>
                <tr>
                    <th>Incident Details</th>
                    <th>Teacher</th>
                    <th>Incident date</th>
                    <th>Attachment</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($disciplines as $discipline)
                    <tr>
                        <td><a href="{{ url('/admin/discipline/show/'.$discipline['id']) }}">{{ $discipline['incident_detail'] }}</a></td>
                        <td><a href="{{ url('/admin/teacher/show/'.$discipline['teacher_username']) }}">{{ $discipline['teacher_name'] }}</a></td>
                        <td>{{ $discipline['incident_date'] }}</td>
                        <td>
                            @if($discipline['attachment'] !== null)
                                <a href="{{ $discipline['attachment'] }}" target="_blank">View</a>
                            @else
                                --
                            @endif
                        </td>
                        <td>
                            <a href="{{ url('/admin/discipline/edit/'.$discipline['id']) }}" class="text-blue-600">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5"><p class="font-semibold text-s" style="text-align: center">No Records Found</p></td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
