@extends('layouts.app')

@section('base-navigation')
    @include('layouts.alumni.navigation')
@endsection

@section('base-sidebar')
    @include('layouts.alumni.sidebar')
@endsection

@section('base-content')
    @yield('content')
@endsection

@section('base-content')
    @yield('content')
@endsection