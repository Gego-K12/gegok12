@extends('layouts.app')

@section('base-navigation')
  @include('layouts.nonteaching.navigation')
@endsection

@section('base-sidebar')
  @include('layouts.nonteaching.sidebar')
@endsection

@section('base-content')
  @yield('content')
@endsection
