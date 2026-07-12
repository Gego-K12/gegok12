@extends('layouts.app')

@if(Auth::user()->usergroup_id==3)
  @section('base-navigation')
    @include('layouts.partials.navigation')
  @endsection
  @section('base-sidebar')
    @include('layouts.admin.sidebar')
  @endsection
@else
  {{-- usergroup 11 (accountant) is the expected case here; anything else
       falls back to accountant's own nav/sidebar rather than rendering
       nothing, since app.blade.php has no default for an unset section. --}}
  @section('base-navigation')
    @include('layouts.accountant.navigation')
  @endsection
  @section('base-sidebar')
    @include('layouts.accountant.sidebar')
  @endsection
@endif

@section('base-content')
  @yield('content')
@endsection
