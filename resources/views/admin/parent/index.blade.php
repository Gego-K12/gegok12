@extends('layouts.admin.layout')

@section('content')
    <div class="relative">
        <div id="parent_index"></div>
        @include('partials.message')
        <parent-list
            url="{{ url('/') }}"
            searchquery="{{ $query }}"
            :standardlinklist="{{ json_encode($standardLinklist ? $standardLinklist->toArray(request()) : []) }}"
        ></parent-list>
    </div>
@endsection
