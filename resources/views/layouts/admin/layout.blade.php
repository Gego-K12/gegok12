@extends('layouts.app')


@section('base-navigation')
  @include('layouts.partials.navigation')
@endsection


@section('base-sidebar')
  @include('layouts.admin.sidebar')
@endsection

@section('base-content')
  @foreach(\App\Models\Plugin::withBeforeContentFor('admin')->get() as $__pluginBeforeContent)
    @includeIf($__pluginBeforeContent->beforeContentViewName('admin'))
  @endforeach

  @yield('content')

  @foreach(\App\Models\Plugin::withAfterContentFor('admin')->get() as $__pluginAfterContent)
    @includeIf($__pluginAfterContent->afterContentViewName('admin'))
  @endforeach
@endsection