<div class="bg-white shadow px-4 py-3 my-4 max-w-md">
    <h2 class="font-semibold text-base text-gray-700 mb-3">Work Permissions</h2>
    @if(Auth::user()->hasRole('leave_checker'))
        <p class="text-sm text-gray-600 mb-2">Pending approvals: <workpermission-pending-count></workpermission-pending-count></p>
        <a href="{{ url('/teacher/workpermissions') }}" class="text-blue-600 text-sm">Review requests &rarr;</a>
    @else
        <p class="text-sm text-gray-600 mb-2">Need to step out during work hours? Apply for a work permission.</p>
        <a href="{{ url('/teacher/workpermission/add') }}" class="text-blue-600 text-sm">Apply now &rarr;</a>
    @endif
</div>
