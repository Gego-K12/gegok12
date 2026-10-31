@php
    $clock = !empty($value) ? \DateTimeImmutable::createFromFormat('!H:i', substr($value, 0, 5)) : false;
    $hour = old($prefix.'_hour', $clock ? $clock->format('g') : '');
    $minute = old($prefix.'_minute', $clock ? $clock->format('i') : '00');
    $period = old($prefix.'_period', $clock ? $clock->format('A') : 'AM');
@endphp
<div class="flex flex-wrap items-center my-2">
    <label class="mr-2">Hour <select class="tw-form-control" name="{{ $prefix }}_hour" @required($required ?? false)><option value="">—</option>@foreach(range(1,12) as $h)<option value="{{ $h }}" @selected((int) $hour === $h)>{{ $h }}</option>@endforeach</select></label>
    <label class="mr-2">Minute <input type="number" min="0" max="59" name="{{ $prefix }}_minute" value="{{ $minute }}" class="tw-form-control" style="width:75px"></label>
    <label>AM / PM <select name="{{ $prefix }}_period" class="tw-form-control"><option value="AM" @selected($period === 'AM')>AM</option><option value="PM" @selected($period === 'PM')>PM</option></select></label>
</div>
