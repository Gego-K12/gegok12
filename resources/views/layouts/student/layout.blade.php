@extends('layouts.app')

@section('base-navigation')
  @include('layouts.student.navigation')
@endsection

@section('base-sidebar')
  @include('layouts.student.sidebar')
@endsection

@section('base-content')
  @foreach(\App\Models\Plugin::withBeforeContentFor('student')->get() as $__pluginBeforeContent)
    @includeIf($__pluginBeforeContent->beforeContentViewName('student'))
  @endforeach

  @yield('content')

  @foreach(\App\Models\Plugin::withAfterContentFor('student')->get() as $__pluginAfterContent)
    @includeIf($__pluginAfterContent->afterContentViewName('student'))
  @endforeach
@endsection
