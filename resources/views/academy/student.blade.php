@extends($layout)
@section('content')
@php($studentName = trim(($profile->firstname ?? '').' '.($profile->lastname ?? '')) ?: $person->name)
<h1 class="admin-h1 my-4">{{ $studentName }}</h1>
<a class="underline" href="{{ url('/learning/academy') }}">Back to students</a>
@include('academy.messages')
<div class="bg-white rounded p-4 my-3">
    <h2 class="font-semibold text-lg">Learning record</h2>
    <p class="my-2">Placement: <strong>{{ ucfirst($journey->placement_status) }}</strong> · {{ $journey->completed_at ? 'Course completed' : ($journey->level ? 'Level '.$journey->level.' / 6 · Unit '.$journey->unit.' / 2' : 'Starting level not set') }}</p>
    <p>Birthday: {{ $profile->date_of_birth ?? 'Not recorded' }}</p>
    @forelse($enrollments->whereNull('deleted_at') as $enrollment)
    <p class="my-2">Group: <strong>{{ $enrollment->group_name }}</strong> · Responsible teacher: {{ $enrollment->teacher_name ?: 'Teacher #'.$enrollment->class_teacher_id }}</p>
    @empty<p class="my-2">No group assigned yet.</p>@endforelse
    @foreach($groups as $group)
        <p class="my-2">{{ $group->group_name }}: @include('academy.schedule')</p>
        @if($group->schedule)<p>{{ $group->group_name }}: {{ $group->schedule }}</p>@endif
    @endforeach
</div>
<section class="bg-white rounded p-4 my-3">
    <h2 class="font-semibold text-lg">Learning links</h2>
    @foreach(['homework_drive_url' => 'Upload homework to Drive', 'recordings_url' => 'Watch lecture recordings', 'report_card_url' => 'Open report cards / progress sheet'] as $field => $label)
        @if($journey->$field)<p class="my-3"><a class="underline text-purple-700" href="{{ $journey->$field }}" target="_blank" rel="noopener noreferrer">{{ $label }} ↗</a></p>@endif
    @endforeach
    @if(!$journey->homework_drive_url && !$journey->recordings_url && !$journey->report_card_url)<p class="my-2">The administrator has not added learning links yet.</p>@endif
</section>
@if($role === 3)
<details class="bg-white rounded p-4 my-3"><summary class="font-semibold cursor-pointer">Edit student details</summary>
    <form method="POST" action="{{ url('/learning/academy/students/'.$person->id.'/details') }}">@csrf
        @foreach(['firstname' => 'First name', 'lastname' => 'Last name', 'date_of_birth' => 'Birthday'] as $field => $label)
        <label class="block my-2">{{ $label }}<input class="tw-form-control" name="{{ $field }}" type="{{ $field === 'date_of_birth' ? 'date' : 'text' }}" value="{{ old($field, $profile->$field ?? '') }}" @required($field === 'firstname')></label>
        @endforeach
        <label class="block my-2">Login email <input type="email" required name="email" class="tw-form-control" value="{{ old('email', $account->email) }}"></label>
        <label class="block my-2">Phone <input name="mobile_no" class="tw-form-control" value="{{ old('mobile_no', $account->mobile_no) }}"></label>
        @include('academy.student-links-fields')
        <button class="blue-bg text-white px-3 py-2 rounded">Save details</button>
    </form>
</details>
<div class="bg-white rounded p-4 my-3">
    <h2 class="font-semibold text-lg">1. Placement & starting point</h2>
    <form method="POST" action="{{ url('/learning/academy/students/'.$person->id.'/placement') }}">
        @csrf
        <label class="block my-2">Placement status <select class="tw-form-control" name="placement_status">
            @foreach(['pending' => 'Not done', 'scheduled' => 'Scheduled', 'completed' => 'Completed', 'waived' => 'Not needed — starting level agreed'] as $value => $label)
            <option value="{{ $value }}" @selected(old('placement_status', $journey->placement_status) === $value)>{{ $label }}</option>@endforeach
        </select></label>
        <label class="block my-2">Placement date <input class="tw-form-control" type="date" name="placement_date" value="{{ old('placement_date', $journey->placement_date) }}"></label>
        <label class="block my-2">Level <select class="tw-form-control" name="level"><option value="">Choose</option>@foreach(range(1,6) as $level)<option value="{{ $level }}" @selected((int) old('level', $journey->level) === $level)>{{ $level }}</option>@endforeach</select></label>
        <label class="block my-2">Unit <select class="tw-form-control" name="unit">@foreach([1,2] as $unit)<option value="{{ $unit }}" @selected((int) old('unit', $journey->unit) === $unit)>{{ $unit }}</option>@endforeach</select></label>
        <label class="block my-2">Placement notes (administration only)<textarea class="tw-form-control block w-full" name="placement_notes" maxlength="4000">{{ old('placement_notes', $journey->placement_notes) }}</textarea></label>
        <button class="blue-bg text-white px-3 py-2 rounded">Save placement</button>
    </form>
</div>
<div class="bg-white rounded p-4 my-3">
    <h2 class="font-semibold text-lg">2. Parent account</h2>
    @foreach($parents as $parent)
    <div class="my-2">{{ trim(($parent->firstname ?? '').' '.($parent->lastname ?? '')) }} · {{ $parent->email }}
        <form method="POST" action="{{ url('/learning/academy/students/'.$person->id.'/parent/unlink') }}">@csrf<input type="hidden" name="parent_id" value="{{ $parent->id }}"><button class="underline">Remove access to this child</button></form>
    </div>
    @endforeach
    <form class="my-3" method="POST" action="{{ url('/learning/academy/students/'.$person->id.'/parent') }}">@csrf
        <label>Parent <select required name="parent_id" class="tw-form-control"><option value="">Choose existing parent</option>@foreach($parentOptions as $parent)<option value="{{ $parent->id }}">{{ $parent->email }}</option>@endforeach</select></label>
        <button class="blue-bg text-white px-3 py-2 rounded">Link parent</button>
        @unless(config('academy_backend.test_only'))<a class="underline mx-2" href="{{ url('/learning/academy/accounts/new/parent') }}">Create parent account</a>@endunless
    </form>
    <h2 class="font-semibold text-lg">3. Assign or move group</h2>
    <form class="my-3" method="POST" action="{{ url('/learning/academy/students/'.$person->id.'/group') }}">@csrf
        <label>Group <select required name="group_id" class="tw-form-control"><option value="">Choose a group at the current level</option>
        @foreach($groups->where('level', $journey->level) as $group)<option value="{{ $group->id }}">{{ $group->group_name }} · Level {{ $group->level }}</option>@endforeach</select></label>
        <button class="custom-green text-white px-3 py-2 rounded">Assign group</button>
    </form>
    <p>Complete placement first. Existing groups need a course level set in <a class="underline" href="{{ url('/learning/academy/groups') }}">Groups & teachers</a>.</p>
</div>
@endif
@if($role === 5)
<form id="teacher-feedback" class="bg-white rounded p-4 my-3" method="POST" action="{{ url('/learning/academy/students/'.$person->id.'/message') }}">
    @csrf<h2 class="font-semibold text-lg">Send feedback message</h2>
    <label class="block my-2">Message for student & parent<textarea name="message" required minlength="3" maxlength="5000" class="tw-form-control block w-full">{{ old('message') }}</textarea></label>
    <button class="custom-green text-white px-3 py-2 rounded">Send feedback message</button>
</form>
@endif
<section class="bg-white rounded p-4 my-3">
    <h2 class="font-semibold text-lg">Teacher messages</h2>
    @forelse($teacherMessages as $message)<article class="border-b py-3"><p class="font-semibold">{{ $message->teacher_name ?: 'Teacher' }} · {{ $message->created_at }}</p><p style="white-space: pre-wrap">{{ $message->message }}</p></article>@empty<p class="my-2">No teacher messages yet.</p>@endforelse
    {{ $teacherMessages->links() }}
</section>
@if($role === 5 && $journey->level && !$journey->completed_at)
<form id="unit-feedback" class="bg-white rounded p-4 my-3" method="POST" action="{{ url('/learning/academy/students/'.$person->id.'/feedback') }}">
    @csrf
    <h2 class="font-semibold text-lg">Teacher feedback · Level {{ $journey->level }}, Unit {{ $journey->unit }}</h2>
    <input type="hidden" name="expected_level" value="{{ $journey->level }}"><input type="hidden" name="expected_unit" value="{{ $journey->unit }}">
    <label class="block my-2">Feedback for student & parent<textarea required minlength="3" maxlength="5000" name="feedback" class="tw-form-control block w-full">{{ old('feedback') }}</textarea></label>
    <label class="block my-2">Action <select name="advance" class="tw-form-control"><option value="0">Share feedback only</option><option value="1">Complete this unit and advance</option></select></label>
    <p class="my-2">No pass/fail or repeating. Completing Unit 2 moves to the next level; the administrator then assigns the next group.</p>
    <button class="custom-green text-white px-3 py-2 rounded">Share feedback</button>
</form>
@endif
<section class="bg-white rounded p-4 my-3">
    <h2 class="font-semibold text-lg">Teacher feedback</h2>
    @forelse($feedback as $item)
    <article class="border-b py-3">
        <p class="font-semibold">Level {{ $item->level }} · Unit {{ $item->unit }} · {{ $item->teacher_name ?: 'Teacher' }} · {{ $item->created_at }}</p>
        <p class="my-2" style="white-space: pre-wrap">{{ $item->feedback }}</p>
        @if($item->advances)<p>Unit completed.</p>@endif
        @if($item->parent_read_at)<p>Read by a linked parent: {{ $item->parent_read_at }}</p>
        @elseif($role === 7)<form method="POST" action="{{ url('/learning/academy/students/'.$person->id.'/feedback/'.$item->id.'/read') }}">@csrf<button class="underline">Mark as read</button></form>@endif
    </article>
    @empty<p class="my-3">No feedback shared yet.</p>@endforelse
    {{ $feedback->links() }}
</section>
<section class="bg-white rounded p-4 my-3">
    <h2 class="font-semibold text-lg">Attendance & homework summary</h2>
    @include('academy.lesson-summary', ['summary' => $lessonSummary])
    <p class="my-2">Only statuses recorded by the teacher are counted. These totals cover recorded group lessons; earlier legacy attendance remains below.</p>
    <div class="overflow-x-auto"><table class="w-full text-sm"><thead><tr class="text-left"><th class="p-2">Lesson</th><th class="p-2">Group</th><th class="p-2">Attendance</th><th class="p-2">Homework</th></tr></thead><tbody>
    @forelse($lessonRecords as $row)<tr class="border-b"><td class="p-2">{{ $row->lesson_date }} · {{ \App\Support\AcademyClock::time($row->start_time) }} Cairo<br>{{ $row->topic }}</td><td class="p-2">{{ $row->group_name }}</td><td class="p-2">{{ ['pending' => 'Not recorded', 'present' => 'Present', 'absent' => 'Absent', 'excused' => 'Excused'][$row->attendance] }}</td><td class="p-2">{{ ['not_assigned' => 'No homework assigned', 'pending' => 'Awaiting review', 'done' => 'Done', 'missing' => 'Not done'][$row->homework] }}</td></tr>@empty<tr><td colspan="4" class="p-2">No group lessons recorded yet.</td></tr>@endforelse
    </tbody></table></div>
    {{ $lessonRecords->links() }}
</section>
<section class="bg-white rounded p-4 my-3">
    <h2 class="font-semibold text-lg">Recent homework</h2>
    @forelse($homework['data'] ?? [] as $work)<article class="border-b py-2"><p>{{ $work['date'] }}</p><p>{{ strip_tags($work['description']) }}</p></article>@empty<p class="my-2">No published homework for the current group.</p>@endforelse
</section>
<section class="bg-white rounded p-4 my-3">
    <h2 class="font-semibold text-lg">Recent attendance</h2>
    @forelse($attendance as $record)<p class="my-2">{{ $record->date }} · {{ $record->session }} · {{ (int) $record->status === 1 ? 'Present' : 'Absent' }}</p>@empty<p class="my-2">No attendance recorded yet.</p>@endforelse
</section>
@if($role === 3)
<section class="bg-white rounded p-4 my-3">
    <h2 class="font-semibold text-lg">Group history</h2>
    @foreach($enrollments as $record)<p class="my-2">{{ $record->group_name }} · {{ $record->teacher_name }} · {{ $record->created_at }} · {{ $record->deleted_at ? 'Previous group' : 'Current group' }}</p>@endforeach
    <h2 class="font-semibold text-lg mt-4">Student activity log</h2>
    @foreach($events as $event)<p class="my-2">{{ $event->created_at }} · {{ str_replace('_', ' ', $event->event) }} · {{ $event->details }}</p>@endforeach
</section>
@endif
@endsection
