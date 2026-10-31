@extends($layout)
@section('content')
<h1 class="admin-h1 my-4">{{ $record->group_name }}</h1>
<a class="underline" href="{{ url('/learning/academy/groups') }}">Back to groups</a>
@include('academy.messages')
<div class="bg-white rounded p-4 my-3">
    <p class="font-semibold">Level {{ $record->level ?: 'not set' }} · Teacher: {{ trim(($teacher->firstname ?? '').' '.($teacher->lastname ?? '')) ?: 'Not assigned' }}</p>
    <p class="my-2">@include('academy.schedule', ['group' => $record])</p>
    @if($record->schedule)<p>{{ $record->schedule }}</p>@endif
</div>
@if(!$session)
<section class="bg-white rounded p-4 my-3">
    <h2 class="font-semibold text-lg">Students in this group ({{ $roster->count() }})</h2>
    @forelse($roster as $student)
    <article class="border-b py-3">
        <a class="underline font-semibold" href="{{ url('/learning/academy/students/'.$student->id) }}">{{ trim(($student->firstname ?? '').' '.($student->lastname ?? '')) ?: $student->name }}</a>
        @include('academy.lesson-summary', ['summary' => $summaries->get($student->id)])
        @if($role === 5)<a class="underline" href="{{ url('/learning/academy/students/'.$student->id).'#teacher-feedback' }}">Send student & parent feedback</a>@endif
    </article>
    @empty<p class="my-2">No students added yet.</p>@endforelse
</section>
@if($role === 3)
<section class="bg-white rounded p-4 my-3">
    <h2 class="font-semibold text-lg">Add existing students</h2>
    <form method="GET" class="my-3"><label>Search students <input name="q" value="{{ request('q') }}" class="tw-form-control" placeholder="Name or email"></label><select name="assignment" class="tw-form-control"><option value="">All existing students</option><option value="assigned" @selected(request('assignment') === 'assigned')>Assigned students</option><option value="unassigned" @selected(request('assignment') === 'unassigned')>Unassigned students</option></select><button class="blue-bg text-white px-3 py-2 rounded">Search</button></form>
    <form method="POST" action="{{ url('/learning/academy/groups/'.$record->id.'/students') }}">@csrf
        @foreach($candidates as $student)
        <label class="block my-2"><input type="checkbox" name="students[]" value="{{ $student->id }}"> {{ trim(($student->firstname ?? '').' '.($student->lastname ?? '')) }} · {{ $student->email }} · {{ $student->level ? 'Level '.$student->level : 'Placement not set' }} · {{ $student->assigned_group ? 'Currently assigned: '.$student->assigned_group : 'Not assigned to a group' }}</label>
        @endforeach
        <label class="block my-3"><input type="checkbox" name="agree_starting_level" value="1"> For students awaiting placement, I agree to start them at this group’s level, Unit 1. No placement test is claimed.</label>
        <p class="my-2">Students with an agreed level must match this group. Adding a student moves their main enrollment from their previous group and keeps its history.</p>
        <button class="custom-green text-white px-3 py-2 rounded">Add selected students</button>
    </form>
    <div class="my-3">{{ $candidates->links() }}</div>
</section>
@else
<section class="bg-white rounded p-4 my-3">
    <h2 class="font-semibold text-lg">Record a lesson</h2>
    <form method="POST" action="{{ url('/learning/academy/groups/'.$record->id.'/lessons') }}">@csrf
        <label class="block my-2">Lesson date <input type="date" required name="lesson_date" class="tw-form-control" max="{{ now('Africa/Cairo')->toDateString() }}" value="{{ old('lesson_date', now('Africa/Cairo')->toDateString()) }}"></label>
        <fieldset class="my-3"><legend>Start time (Cairo)</legend>@include('academy.clock-fields', ['prefix' => 'start_time', 'value' => $record->start_time, 'required' => true])</fieldset>
        <label class="block my-2">Topic <input name="topic" maxlength="255" class="tw-form-control" value="{{ old('topic') }}"></label>
        <label class="block my-2">Homework for this lesson <select name="homework_assigned" class="tw-form-control"><option value="0">No homework assigned</option><option value="1">Homework assigned — track completion</option></select></label>
        <button class="blue-bg text-white px-3 py-2 rounded">Open attendance & homework sheet</button>
    </form>
</section>
@endif
@else
<section class="bg-white rounded p-4 my-3">
    <h2 class="font-semibold text-lg">{{ $session->lesson_date }} · {{ \App\Support\AcademyClock::time($session->start_time) }} Cairo · {{ $session->topic }}</h2>
    <p class="my-2">This lesson keeps its original student roster. Unrecorded statuses are not counted as absences or missed homework.</p>
    <form method="POST" action="{{ url('/learning/academy/groups/'.$record->id.'/lessons/'.$session->id) }}">@csrf
        <div class="overflow-x-auto"><table class="w-full text-sm"><thead><tr class="text-left"><th class="p-2">Student</th><th class="p-2">Attendance</th><th class="p-2">Homework</th></tr></thead><tbody>
        @foreach($marks as $i => $mark)
        <tr class="border-b"><td class="p-2">{{ trim(($mark->firstname ?? '').' '.($mark->lastname ?? '')) ?: $mark->name }}<input type="hidden" name="students[{{ $i }}][student_id]" value="{{ $mark->student_id }}"></td>
            <td class="p-2"><select name="students[{{ $i }}][attendance]" class="tw-form-control" @disabled($role !== 5)>@foreach(['pending' => 'Not recorded', 'present' => 'Present', 'absent' => 'Absent', 'excused' => 'Excused'] as $value => $label)<option value="{{ $value }}" @selected($mark->attendance === $value)>{{ $label }}</option>@endforeach</select></td>
            <td class="p-2">@if($session->homework_assigned)<select name="students[{{ $i }}][homework]" class="tw-form-control" @disabled($role !== 5)>@foreach(['pending' => 'Awaiting review', 'done' => 'Done', 'missing' => 'Not done'] as $value => $label)<option value="{{ $value }}" @selected($mark->homework === $value)>{{ $label }}</option>@endforeach</select>@else No homework assigned<input type="hidden" name="students[{{ $i }}][homework]" value="not_assigned">@endif</td>
        </tr>
        @endforeach
        </tbody></table></div>
        @if($role === 5)<button class="custom-green text-white px-3 py-2 rounded my-3">Save attendance & homework</button>@endif
    </form>
    <a class="underline" href="{{ url('/learning/academy/groups/'.$record->id) }}">Back to current group roster</a>
</section>
@endif
<section class="bg-white rounded p-4 my-3">
    <h2 class="font-semibold text-lg">Lesson history</h2>
    @forelse($history as $item)<p class="my-2"><a class="underline" href="{{ url('/learning/academy/groups/'.$record->id.'/lessons/'.$item->id) }}">{{ $item->lesson_date }} · {{ \App\Support\AcademyClock::time($item->start_time) }} Cairo · {{ $item->topic }}</a></p>@empty<p class="my-2">No lessons recorded yet.</p>@endforelse
    {{ $history->links() }}
</section>
@endsection
