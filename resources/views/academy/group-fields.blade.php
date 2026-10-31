<label class="block my-2">Group name <input required class="tw-form-control" name="group_name" maxlength="100" value="{{ $group->group_name ?? old('group_name') }}"></label>
<label class="block my-2">Course level <select required name="level" class="tw-form-control"><option value="">Choose level</option>
    @foreach(range(1,6) as $level)<option value="{{ $level }}" @selected((int) ($group->level ?? old('level')) === $level)>Level {{ $level }}</option>@endforeach
</select></label>
<label class="block my-2">Responsible teacher <select required name="teacher_id" class="tw-form-control"><option value="">Choose teacher</option>
    @foreach($teachers as $teacher)<option value="{{ $teacher->id }}" @selected((int) ($group->class_teacher_id ?? old('teacher_id')) === (int) $teacher->id)>{{ trim(($teacher->firstname ?? '').' '.($teacher->lastname ?? '')) }} · {{ $teacher->email }}</option>@endforeach
</select></label>
<label class="block my-2">Lesson link / additional notes (optional)<input name="schedule" class="tw-form-control w-full" maxlength="500" value="{{ $group->schedule ?? old('schedule') }}"></label>

<input type="hidden" name="structured_schedule" value="1">
@php($savedDays = old('meeting_days', json_decode($group->meeting_days ?? '[]', true) ?: []))
<fieldset class="my-3"><legend>Meeting days (Cairo time)</legend>
@foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
<label class="inline-block mr-3 my-1"><input type="checkbox" name="meeting_days[]" value="{{ $day }}" @checked(in_array($day, $savedDays))> {{ $day }}</label>
@endforeach
</fieldset>
<fieldset class="my-3"><legend>Start time (Cairo)</legend>@include('academy.clock-fields', ['prefix' => 'start_time', 'value' => $group->start_time ?? null, 'required' => true])</fieldset>
<fieldset class="my-3"><legend>End time (optional, Cairo)</legend>@include('academy.clock-fields', ['prefix' => 'end_time', 'value' => $group->end_time ?? null, 'required' => false])</fieldset>
