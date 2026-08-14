@extends('layouts.admin.layout')

@section('content')

<div class="">
    <h1 class="admin-h1 my-3 flex items-center">
        <span class="mx-3">Student Attendance</span>
    </h1>
    @include('partials.message')
    <list-attendance
        url="{{ url('/') }}"
        mode="admin"
        :standards="{{ json_encode($standardlist) }}"
        :students="{{ json_encode($studentlist) }}"
        :absent-reasons="{{ json_encode($absentReasonlist) }}"
    ></list-attendance>
</div>
@endsection
