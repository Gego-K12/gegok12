@extends('layouts.app')

@section('base-navigation')
  @include('layouts.teacher.navigation')
@endsection

@section('base-sidebar')
  @include('layouts.teacher.sidebar')
@endsection

@section('base-content')
    @foreach(\App\Models\Plugin::withBeforeContentFor('teacher')->get() as $__pluginBeforeContent)
        @includeIf($__pluginBeforeContent->beforeContentViewName('teacher'))
    @endforeach

    @yield('content')

    @foreach(\App\Models\Plugin::withAfterContentFor('teacher')->get() as $__pluginAfterContent)
        @includeIf($__pluginAfterContent->afterContentViewName('teacher'))
    @endforeach
@endsection
