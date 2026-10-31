@extends($layout)
@section('content')
<h1 class="admin-h1 my-4">Add {{ $type }}</h1>
@include('academy.messages')
<form method="POST" action="{{ url('/learning/academy/accounts/new/'.$type) }}" class="bg-white rounded p-4" style="max-width: 650px">
    @csrf
    @foreach(['firstname' => 'First name', 'lastname' => 'Last name', 'email' => 'Login email', 'mobile_no' => 'Phone (optional)'] as $field => $label)
    <label class="block my-3">{{ $label }}<input class="tw-form-control block w-full" name="{{ $field }}" type="{{ $field === 'email' ? 'email' : 'text' }}" value="{{ old($field) }}" @required(in_array($field, ['firstname', 'email'])) maxlength="{{ $field === 'mobile_no' ? 30 : 100 }}"></label>
    @endforeach
    @if($type === 'student')<label class="block my-3">Birthday (optional, ages 6–15)<input type="date" name="date_of_birth" class="tw-form-control block" value="{{ old('date_of_birth') }}"></label>@endif
    @if($type === 'student') @include('academy.student-links-fields') @endif
    <label class="block my-3">Initial password (at least 12 characters)<input name="password" type="password" autocomplete="new-password" minlength="12" required class="tw-form-control block w-full"></label>
    <p class="my-3">{{ $type === 'student' ? 'Next: record placement, link a parent, then assign a group. No school admission paperwork is required.' : 'This account can sign in using the email and password above. Share the credentials privately.' }}</p>
    <button class="custom-green text-white px-4 py-2 rounded" type="submit">Create {{ $type }}</button>
    <a class="mx-3 underline" href="{{ url('/learning/academy') }}">Cancel</a>
</form>
@endsection
