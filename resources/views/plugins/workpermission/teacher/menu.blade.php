<li class="py-3 px-3 hover:bg-purple-900 {{Request::segment ('2') == 'workpermissions' ? 'active':''}} || {{Request::segment ('2') == 'workpermission' ? 'active':''}}">
    <a href="{{ url('teacher/workpermissions') }}" class="flex items-center">
        <svg class="w-5 h-5 fill-current text-white" id="Layer_1" enable-background="new 0 0 512 512" height="512" viewBox="0 0 512 512" width="512" xmlns="http://www.w3.org/2000/svg"><g><path d="m205.182 143.679h95.259c8.284 0 15-6.716 15-15s-6.716-15-15-15h-95.259c-8.284 0-15 6.716-15 15s6.715 15 15 15z"/><path d="m366.03 0h-351.03c-8.284 0-15 6.716-15 15v482c0 8.284 6.716 15 15 15h351.03c8.284 0 15-6.716 15-15v-482c0-8.284-6.716-15-15-15zm-15 482h-321.03v-452h321.03z"/></g></svg>
        @if(Auth::user()->hasRole('leave_checker'))
            <span class="mx-2 whitespace-no-wrap">Permission Approvals <workpermission-pending-count></workpermission-pending-count></span>
        @else
            <span class="mx-3 whitespace-no-wrap">Work Permissions</span>
        @endif
    </a>
</li>

@if(Auth::user()->hasRole('leave_checker'))
    <li class="py-3 px-3 hover:bg-purple-900 {{Request::segment ('2') == 'mypermissions' ? 'active':''}}">
        <a href="{{ url('teacher/mypermissions') }}" class="flex items-center">
            <svg class="w-5 h-5 fill-current text-white" id="Layer_1" enable-background="new 0 0 512 512" height="512" viewBox="0 0 512 512" width="512" xmlns="http://www.w3.org/2000/svg"><g><path d="m205.182 143.679h95.259c8.284 0 15-6.716 15-15s-6.716-15-15-15h-95.259c-8.284 0-15 6.716-15 15s6.715 15 15 15z"/><path d="m366.03 0h-351.03c-8.284 0-15 6.716-15 15v482c0 8.284 6.716 15 15 15h351.03c8.284 0 15-6.716 15-15v-482c0-8.284-6.716-15-15-15zm-15 482h-321.03v-452h321.03z"/></g></svg>
            <span class="mx-3 whitespace-no-wrap">My Permissions</span>
        </a>
    </li>
@endif
