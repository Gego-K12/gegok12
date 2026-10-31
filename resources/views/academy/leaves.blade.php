@extends($layout)
@section('content')
<h1 class="admin-h1 my-4">{{ $role === 5 ? 'My leave requests' : 'Teacher leave requests' }}</h1>
@include('academy.messages')
@if($role === 5)
<details class="bg-white rounded p-4 my-3"><summary class="font-semibold cursor-pointer">Request leave</summary>
<form method="POST" action="{{ url('/learning/academy/teacher/leaves') }}" class="my-3">@csrf
    <label class="block my-2">From <input type="date" required name="from_date" value="{{ old('from_date') }}" class="tw-form-control"></label>
    <label class="block my-2">To <input type="date" required name="to_date" value="{{ old('to_date') }}" class="tw-form-control"></label>
    <label class="block my-2">Reason <textarea required name="remarks" minlength="3" maxlength="2000" class="tw-form-control block w-full">{{ old('remarks') }}</textarea></label>
    <button class="custom-green text-white px-4 py-2 rounded">Send request</button>
</form></details>
@endif
<form method="GET" class="my-3"><label>Status <select name="status" class="tw-form-control"><option value="">All</option>@foreach(['pending','approved','rejected','cancelled'] as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>@endforeach</select></label><button class="blue-bg text-white px-3 py-2 rounded">Filter</button></form>
@forelse($leaves as $leave)
<section class="bg-white rounded p-4 my-3">
    <h2 class="font-semibold">{{ $leave->firstname }} {{ $leave->lastname }} · {{ substr($leave->from_date, 0, 10) }} → {{ substr($leave->to_date, 0, 10) }}</h2>
    <p class="my-2">Status: <strong>{{ ucfirst($leave->academy_decision ?: ($leave->status === 'cancelled' ? 'withdrawn / cancelled' : $leave->status)) }}</strong></p>
    <p style="white-space: pre-wrap">{{ $leave->remarks }}</p>
    @if($leave->comments)<p class="my-2">Administrator: {{ $leave->comments }}</p>@endif
    @if($leave->approved_on)<p>Reviewed: {{ $leave->approved_on }}</p>@endif
    @if($leave->status === 'pending' && $role === 3)
    <form class="my-3" method="POST" action="{{ url('/learning/academy/teacher/leaves/'.$leave->id.'/review') }}">@csrf
        <label class="block my-2">Decision <select name="decision" required class="tw-form-control"><option value="approved">Approve</option><option value="rejected">Reject</option></select></label>
        <label class="block my-2">Comment (required when rejecting)<textarea name="comments" maxlength="2000" class="tw-form-control block w-full"></textarea></label>
        <button class="blue-bg text-white px-3 py-2 rounded">Save decision</button>
    </form>
    @elseif($leave->status === 'pending')<form class="my-3" method="POST" action="{{ url('/learning/academy/teacher/leaves/'.$leave->id.'/withdraw') }}">@csrf<button class="border px-3 py-2 rounded">Withdraw request</button></form>@endif
</section>
@empty<p class="bg-white rounded p-4">No leave requests found.</p>@endforelse
{{ $leaves->links() }}
@endsection
