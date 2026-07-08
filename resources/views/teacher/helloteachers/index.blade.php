@extends('layouts.teacher.layout')

@section('content')
    <div class="relative">
        <h1 class="admin-h1 my-3">Motivation</h1>
        <hello-teachers-quote url="{{ url('/') }}"></hello-teachers-quote>
    </div>
@endsection
