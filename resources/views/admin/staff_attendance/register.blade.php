@extends('layouts.admin.layout')

@section('content')
    <div class="relative">
        <div class="my-3 flex items-center justify-between">
            <h1 class="admin-h1 flex items-center">
                <span>Staff Attendance Register</span>
            </h1>
            <a href="{{ url('/admin/attendance/staff/add') }}" class="btn btn-submit blue-bg text-white rounded px-3 py-1 text-sm font-medium">Record Attendance</a>
        </div>
        @include('partials.message')
        <staff-attendance-register url="{{ url('/') }}" mode="admin" academic-year-start="{{ $academicYearStart }}" today="{{ $today }}"></staff-attendance-register>
    </div>
@endsection
