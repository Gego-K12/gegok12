@extends('layouts.admin.layout')
@section('content')
    <div class="relative">
        <div class="flex items-center justify-between my-3">
            <h1 class="admin-h1">Students Attendance List</h1>
        </div>
        <livewire:transport.students-attendance-list />
    </div>
@endsection