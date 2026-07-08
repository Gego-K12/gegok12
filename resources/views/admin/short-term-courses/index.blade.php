@extends('layouts.admin.layout')

@section('content')
<div class="relative">
    <div class="my-3 flex items-center justify-between">
        <h1 class="admin-h1 flex items-center"><span>Short Term Courses</span></h1>
    </div>

    <livewire:short-term-courses.admin.course-manager />
</div>
@endsection
