@php($meetingDays = json_decode($group->meeting_days ?? '[]', true) ?: [])
@if($meetingDays && $group->start_time)
    {{ implode(', ', $meetingDays) }} · {{ \App\Support\AcademyClock::time($group->start_time) }}@if($group->end_time)–{{ \App\Support\AcademyClock::time($group->end_time) }}@endif · Cairo time
@else
    Meeting day/time not set
@endif
