@extends('layouts.app')

@section('base-navigation')
  @include('layouts.siteadmin.navigation')
@endsection

@section('base-sidebar')
  @include('layouts.siteadmin.sidebar')
@endsection

@section('base-content')
  @include('partials.message')
  @yield('content')
@endsection
