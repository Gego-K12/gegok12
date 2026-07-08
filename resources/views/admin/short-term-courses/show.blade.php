@extends('layouts.admin.layout')

@section('content')
<div class="relative">
    <livewire:short-term-courses.admin.course-show :course-id="$course->id" />
</div>
@endsection
