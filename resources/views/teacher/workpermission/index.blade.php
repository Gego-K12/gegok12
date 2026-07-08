@extends('layouts.teacher.layout')

@section('content')
    @include('partials.message')
    @if($user_type == 'apply')
        <workpermission-teacher-list url="{{ url('/') }}" type="apply"></workpermission-teacher-list>
    @elseif($user_type == 'check')
        <workpermission-teacher-list url="{{ url('/') }}" type="check"></workpermission-teacher-list>
    @endif
@endsection
