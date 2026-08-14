@extends('layouts.admin.layout')

@section('content')

<div class="">
    <h1 class="admin-h1 my-3 flex items-center">
        <span class="mx-3">Staff Attendance</span>
    </h1>
    @include('partials.message')
    <list-staff-attendance
        url="{{ url('/') }}"
        mode="admin"
        :stafflist="{{ json_encode($stafflist) }}"
        :absent-reasons="{{ json_encode($absentReasonlist) }}"
    ></list-staff-attendance>
</div>
@endsection
