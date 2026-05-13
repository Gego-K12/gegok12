@extends('layouts.teacher.layout')

@section('content')
    <task-show
        url="{{ url('/') }}"
        mode="{{ $mode }}"
        task-id="{{ $taskId }}">
    </task-show>
@endsection