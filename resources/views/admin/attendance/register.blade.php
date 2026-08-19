@extends('layouts.admin.layout')

@section('content')
    <div class="relative">
        <div class="my-3 flex items-center justify-between">
            <h1 class="admin-h1 flex items-center">
                <span>Student Attendance Register</span>
            </h1>
        </div>
        @include('partials.message')
        <admin-student-attendance-register
            url="{{ url('/') }}"
            mode="admin"
            academic-year-start="{{ $academicYearStart }}"
            today="{{ $today }}"
            standardlink_id="{{ $standardlink_id }}"
            :standardlist="{{ json_encode($standardlist) }}"
        ></admin-student-attendance-register>
    </div>
@endsection
