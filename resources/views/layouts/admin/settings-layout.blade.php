{{--
    Dedicated layout for the whole admin Settings section (School Details,
    Academic Years, Admissions, Holidays, Promotions, Leave Master, SMS
    Templates, Countries/States/Cities, General/SEO/Maintenance Settings,
    Standards). "Settings" is a single flat link in the main admin sidebar
    (resources/views/layouts/admin/_menu.blade.php) rather than a
    dropdown-with-children -- clicking it lands here instead, where this
    dedicated settings-menu.blade.php sidebar replaces the main admin
    sidebar for the whole section.
--}}
@extends('layouts.app')

@section('base-navigation')
    @include('layouts.partials.navigation')
@endsection

@section('base-sidebar')
    @include('layouts.admin.settings-menu')
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
