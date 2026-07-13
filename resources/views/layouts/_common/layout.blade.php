{{--
    Shared base layout for every portal. Each portal's own layout.blade.php
    reduces to two lines: set a portalKey variable, then include this file.

    Extends layouts.app itself here (rather than in each portal's file) so
    base-navigation/base-sidebar/base-content are always defined
    unconditionally -- this is what fixes accountant's old bug, where those
    sections were only ever defined inside usergroup_id conditionals with no
    fallback, leaving a blank page for any other usergroup.

    Also extends the Plugin::withBeforeContentFor/withAfterContentFor hooks
    (previously wired up for admin/student/teacher only) to every portal.
--}}
@extends('layouts.app')

@php
    $portal = \App\Support\PortalConfig::for($portalKey);
@endphp

@section('base-navigation')
    @include('layouts._common.navigation', ['portal' => $portal])
@endsection

@section('base-sidebar')
    @include('layouts._common.sidebar', ['portal' => $portal, 'portalKey' => $portalKey])
@endsection

@section('base-content')
    @isset($portal['flashMessagePartial'])
        @include($portal['flashMessagePartial'])
    @endisset

    @foreach(\App\Models\Plugin::cachedHook('withBeforeContentFor', $portalKey) as $__pluginBeforeContent)
        @includeIf($__pluginBeforeContent->beforeContentViewName($portalKey))
    @endforeach

    @yield('content')

    @foreach(\App\Models\Plugin::cachedHook('withAfterContentFor', $portalKey) as $__pluginAfterContent)
        @includeIf($__pluginAfterContent->afterContentViewName($portalKey))
    @endforeach
@endsection
