@extends($layout)
@section('content')
<div class="flex flex-wrap justify-between items-center my-4">
    <div><h1 class="admin-h1">{{ $role === 5 ? 'My groups' : 'Groups & teachers' }}</h1><p class="my-2">{{ $role === 3 ? 'Choose a level and teacher to organize your group list.' : 'Your assigned groups, students and lesson registers.' }}</p></div>
</div>
@include('academy.messages')
<form method="GET" class="bg-white rounded p-4 my-3 flex flex-wrap items-end">
    <label class="block mr-3 my-2">Level <select name="level" class="tw-form-control block" @required($role === 3)><option value="">{{ $role === 3 ? 'Choose level' : 'All my levels' }}</option>@foreach(range(1,6) as $level)<option value="{{ $level }}" @selected((int) request('level') === $level)>Level {{ $level }}</option>@endforeach<option value="unconfigured" @selected(request('level') === 'unconfigured')>Needs level setup</option></select></label>
    @if($role === 3)<label class="block mr-3 my-2">Teacher <select name="teacher_id" required class="tw-form-control block"><option value="">Choose teacher</option>@foreach($teachers as $teacher)<option value="{{ $teacher->id }}" @selected((int) request('teacher_id') === (int) $teacher->id)>{{ trim(($teacher->firstname ?? '').' '.($teacher->lastname ?? '')) }} · {{ $teacher->email }}</option>@endforeach</select></label>@endif
    <button class="blue-bg text-white px-4 py-2 rounded my-2">Show groups</button>
</form>
@if($role === 3)
<details class="bg-white rounded p-4 my-3" @if($errors->any()) open @endif><summary class="font-semibold cursor-pointer">＋ Create a new English group</summary>
    <form method="POST" action="{{ url('/learning/academy/groups') }}" class="my-3">@csrf
        @include('academy.group-fields', ['group' => null])
        <button class="custom-green text-white px-4 py-2 rounded">Create group</button>
    </form>
</details>
@endif
@if(!$filtersReady)
<div class="bg-white rounded p-6 my-3"><h2 class="font-semibold text-lg">Select a level and teacher first</h2><p class="my-2">Groups stay hidden until both filters are selected.</p></div>
@else
<p class="my-3">{{ $groups->total() }} group(s) found</p>
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,320px),1fr));gap:16px;align-items:start" class="my-4">
@forelse($groups as $group)
<article class="bg-white rounded border p-4">
    <div class="flex justify-between items-center"><h2 class="font-semibold text-lg"><a class="text-purple-700 underline" href="{{ url('/learning/academy/groups/'.$group->id) }}">{{ $group->group_name }}</a></h2><span class="border rounded px-2 py-1 text-sm">Level {{ $group->level ?: 'not set' }}</span></div>
    <p class="my-3 font-semibold">@include('academy.schedule')</p>
    <p class="my-2">Teacher: {{ $group->teacher_name ?: 'Not assigned' }}</p>
    @if($group->schedule)<p class="text-sm my-2" style="overflow-wrap:anywhere">{{ $group->schedule }}</p>@endif
    <details class="my-3" @if($role === 5) open @endif><summary class="cursor-pointer">Assigned students ({{ $groupStudents->get($group->id, collect())->count() }})</summary>
        @forelse($groupStudents->get($group->id, collect()) as $student)<p class="my-2"><a class="underline" href="{{ url('/learning/academy/students/'.$student->id) }}">{{ trim(($student->firstname ?? '').' '.($student->lastname ?? '')) ?: $student->name }}</a></p>@empty<p class="my-2">No students yet.</p>@endforelse
    </details>
    <a class="blue-bg text-white inline-block px-3 py-2 rounded my-2" href="{{ url('/learning/academy/groups/'.$group->id) }}">{{ $role === 5 ? 'Open lesson register' : 'Manage students' }}</a>
    @if($role === 3)
    <details class="my-3"><summary class="cursor-pointer underline">Edit group</summary>
        <form method="POST" action="{{ url('/learning/academy/groups/'.$group->id) }}" class="my-3">@csrf @include('academy.group-fields')<button class="blue-bg text-white px-3 py-2 rounded">Save group</button></form>
        <form method="POST" action="{{ url('/learning/academy/groups/'.$group->id.'/archive') }}">@csrf<p class="my-2 text-sm">Move enrolled students before archiving. History is retained.</p><button class="border px-3 py-2 rounded">Archive empty group</button></form>
    </details>
    @endif
</article>
@empty<p class="bg-white rounded p-4">No groups match this selection.</p>@endforelse
</div>
{{ $groups->links() }}
@endif
@endsection
