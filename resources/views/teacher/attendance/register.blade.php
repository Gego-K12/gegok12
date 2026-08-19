@extends('layouts.teacher.layout')

@section('content')
    <div class="relative">
        <div class="my-3 flex items-center justify-between">
            <h1 class="admin-h1 flex items-center">
                <span>Student Attendance Register - {{ $standardName }}</span>
            </h1>
            <a href="{{ url('/teacher/attendance/add?standardLink_id='.$standardlink_id) }}" class="btn btn-submit blue-bg text-white rounded px-3 py-1 text-sm font-medium">Record Attendance</a>
        </div>
        @include('partials.message')
        <student-attendance-register url="{{ url('/') }}" mode="teacher" academic-year-start="{{ $academicYearStart }}" today="{{ $today }}" standardlink_id="{{$standardlink_id }}"></student-attendance-register>
    </div>
@endsection
