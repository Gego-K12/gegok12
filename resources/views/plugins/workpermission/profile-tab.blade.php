@php
    $school_id = \Illuminate\Support\Facades\Auth::user()->school_id;
    $academic_year = \App\Helpers\SiteHelper::getAcademicYear($school_id);
    $permissions = \Gegok12\WorkPermission\Models\WorkPermission::with('approvedUser')
        ->where([
            ['user_id', $entityId],
            ['school_id', $school_id],
            ['academic_year_id', $academic_year->id],
        ])
        ->orderBy('date', 'DESC')
        ->paginate(10, ['*'], 'workpermission_page');
@endphp

<div class="custom-table overflow-x-auto">
    <table class="table table-bordered borderTable">
        <thead class="bg-grey-light">
            <tr>
                <th>Date</th>
                <th>From</th>
                <th>To</th>
                <th>Type</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Approved By</th>
            </tr>
        </thead>
        <tbody>
            @if(count($permissions) != 0)
                @foreach($permissions as $permission)
                    <tr>
                        <td>{{ date('d M Y', strtotime($permission->date)) }}</td>
                        <td>{{ date('h:i A', strtotime($permission->from_time)) }}</td>
                        <td>{{ date('h:i A', strtotime($permission->to_time)) }}</td>
                        <td>{{ ucfirst($permission->type) }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($permission->reason, 40) }}</td>
                        <td>{{ ucfirst($permission->status) }}</td>
                        <td>{{ $permission->approvedUser->FullName ?? '-' }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="7"><p class="font-semibold text-s" style="text-align: center">No Records Found</p></td>
                </tr>
            @endif
        </tbody>
    </table>
    {{ $permissions->links() }}
</div>
