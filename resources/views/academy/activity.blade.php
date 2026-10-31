@extends($layout)
@section('content')
<h1 class="admin-h1 my-4">Learning activity log</h1>
<div class="bg-white rounded p-4 overflow-x-auto">
<table class="w-full text-sm"><thead><tr class="text-left border-b"><th class="p-2">When</th><th class="p-2">By</th><th class="p-2">Student</th><th class="p-2">Action</th><th class="p-2">Details</th></tr></thead><tbody>
@forelse($events as $event)
<tr class="border-b"><td class="p-2">{{ $event->created_at }}</td><td class="p-2">{{ $event->actor_name ?: 'Account #'.$event->actor_id }}</td><td class="p-2">@if($event->student_id)<a class="underline" href="{{ url('/learning/academy/students/'.$event->student_id) }}">Student #{{ $event->student_id }}</a>@else—@endif</td><td class="p-2">{{ str_replace('_', ' ', $event->event) }}</td><td class="p-2">{{ $event->details }}</td></tr>
@empty<tr><td colspan="5" class="p-3">No academy changes recorded yet. Existing application audit logs are retained.</td></tr>@endforelse
</tbody></table>
</div>
<div class="my-3">{{ $events->links() }}</div>
@endsection
