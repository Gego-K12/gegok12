@extends($layout)
@section('content')
<h1 class="admin-h1 my-4">{{ $role === 5 ? 'My attendance' : 'Teacher attendance' }}</h1>
@include('academy.messages')
<p class="bg-white rounded p-3 my-3">Recorded sessions: {{ $counts->present_count ?? 0 }} present · {{ $counts->absent_count ?? 0 }} absent. A full-day entry contains an AM and a PM session; days with no record are not counted as absences.</p>
@if($role === 3)
<form method="GET" class="my-3"><label>Teacher <select name="teacher_id" class="tw-form-control"><option value="">All teachers</option>@foreach($teachers as $teacher)<option value="{{ $teacher->id }}" @selected((int) request('teacher_id') === (int) $teacher->id)>{{ $teacher->email }}</option>@endforeach</select></label><button class="blue-bg text-white px-3 py-2 rounded">Filter</button></form>
<details class="bg-white rounded p-4 my-3"><summary class="font-semibold cursor-pointer">Record teacher attendance</summary>
<form method="POST" action="{{ url('/learning/academy/teacher/attendance') }}">@csrf
    <label class="block my-2">Teacher <select required name="teacher_id" class="tw-form-control"><option value="">Choose teacher</option>@foreach($teachers as $teacher)<option value="{{ $teacher->id }}">{{ $teacher->email }}</option>@endforeach</select></label>
    <label class="block my-2">Date <input required type="date" name="date" max="{{ now('Africa/Cairo')->toDateString() }}" value="{{ now('Africa/Cairo')->toDateString() }}" class="tw-form-control"></label>
    <label class="block my-2">Session <select name="session" class="tw-form-control"><option value="day">Full day (AM + PM)</option><option value="forenoon">AM</option><option value="afternoon">PM</option></select></label>
    <label class="block my-2">Status <select name="status" class="tw-form-control"><option value="1">Present</option><option value="0">Absent</option></select></label>
    <label class="block my-2">Notes <textarea name="remarks" maxlength="2000" class="tw-form-control block w-full"></textarea></label>
    <button class="custom-green text-white px-3 py-2 rounded">Save attendance</button>
</form></details>
@endif
<div class="bg-white rounded p-4 overflow-x-auto"><table class="w-full text-sm"><thead><tr class="text-left"><th class="p-2">Teacher</th><th class="p-2">Date</th><th class="p-2">Session</th><th class="p-2">Status</th><th class="p-2">Notes</th></tr></thead><tbody>
@forelse($records as $record)<tr class="border-b"><td class="p-2">{{ $record->firstname }} {{ $record->lastname }}</td><td class="p-2">{{ substr($record->date, 0, 10) }}</td><td class="p-2">{{ $record->session === 'forenoon' ? 'AM' : 'PM' }}</td><td class="p-2">{{ $record->status ? 'Present' : 'Absent' }}</td><td class="p-2">{{ $record->remarks }}</td></tr>@empty<tr><td class="p-2" colspan="5">No attendance recorded.</td></tr>@endforelse
</tbody></table></div>
{{ $records->links() }}
@endsection
