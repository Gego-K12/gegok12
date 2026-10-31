@extends($layout)
@section('content')
<div class="my-4">
    <h1 class="admin-h1 my-3">{{ $role === 7 ? 'My children' : ($role === 6 ? 'My learning' : 'Students & progress') }}</h1>
    @include('academy.messages')
    @if($role === 3 && !config('academy_backend.test_only'))
    <div class="flex flex-wrap my-3">
        @foreach(['student' => 'Add student', 'parent' => 'Add parent', 'teacher' => 'Add teacher'] as $type => $label)
        <a class="custom-green text-white px-4 py-2 mr-2 mb-2 rounded" href="{{ url('/learning/academy/accounts/new/'.$type) }}">{{ $label }}</a>
        @endforeach
        <a class="blue-bg text-white px-4 py-2 mb-2 rounded" href="{{ url('/learning/academy/groups') }}">Manage groups</a>
    </div>
    @endif
    <form method="GET" class="flex flex-wrap items-center my-3">
        <label class="mr-2">Search <input name="q" value="{{ request('q') }}" class="tw-form-control" placeholder="Name or email"></label>
        <label class="mr-2">Next action <select name="stage" class="tw-form-control">
            <option value="">All students</option>
            @foreach(['placement' => 'Needs placement', 'group' => 'Waiting for a group', 'completed' => 'Course completed'] as $value => $label)
            <option value="{{ $value }}" @selected(request('stage') === $value)>{{ $label }}</option>
            @endforeach
        </select></label>
        <label class="mr-2">Group assignment <select name="assignment" class="tw-form-control"><option value="">All students</option><option value="assigned" @selected(request('assignment') === 'assigned')>Assigned students</option><option value="unassigned" @selected(request('assignment') === 'unassigned')>Unassigned students</option></select></label>
        <button class="blue-bg text-white px-3 py-2 rounded" type="submit">Search</button>
    </form>
    <div class="bg-white rounded overflow-x-auto">
        <table class="w-full text-sm"><thead><tr class="border-b text-left">
            @foreach(['Student', 'Placement', 'Progress', 'Group', 'Responsible teacher', 'Next step'] as $heading)<th class="p-3">{{ $heading }}</th>@endforeach
        </tr></thead><tbody>
        @forelse($students as $student)
        <tr class="border-b">
            <td class="p-3"><a class="text-purple-700 underline" href="{{ url('/learning/academy/students/'.$student->id) }}">{{ trim(($student->firstname ?? '').' '.($student->lastname ?? '')) ?: $student->name }}</a><br>{{ $student->email }}
@include('academy.lesson-summary', ['summary' => $summaries->get($student->id)])</td>
            <td class="p-3">{{ ucfirst($student->placement_status ?? 'pending') }}</td>
            <td class="p-3">{{ $student->completed_at ? 'Course completed' : ($student->level ? 'Level '.$student->level.' · Unit '.$student->unit : 'Not set') }}</td>
            <td class="p-3">{{ $student->group_name ?: 'Not assigned' }}@if($student->group_name)<br>@include('academy.schedule', ['group' => $student])@endif</td>
            <td class="p-3">{{ $student->teacher_name ?: ($student->class_teacher_id ? 'Teacher #'.$student->class_teacher_id : 'Not assigned') }}</td>
            <td class="p-3">{{ $student->completed_at ? 'Completed' : (!in_array($student->placement_status, ['completed', 'waived']) ? 'Placement' : ((!$student->standardLink_id || (int) $student->group_level !== (int) $student->level) ? 'Assign group' : 'Open learning record')) }}</td>
        </tr>
        @empty
        <tr><td colspan="6" class="p-4">{{ $role === 5 ? 'No students assigned to you yet. Ask the administrator to assign a group.' : ($role === 7 ? 'No children linked yet. Ask the administrator to link your child to this parent account.' : 'No students found.') }}</td></tr>
        @endforelse
        </tbody></table>
    </div>
    <div class="my-3">{{ $students->links() }}</div>
</div>
@endsection
