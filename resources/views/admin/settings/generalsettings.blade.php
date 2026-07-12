@extends('layouts.admin.settings-layout')
@section('content')
<div class="w-full main-content flex h-auto ">
<div class="flex  lg:flex-row w-full">
<!-- settings sidebar start -->
{{--@include('layouts.admin.settingsbar')--}}
@include('admin.settings.general_settings')
</div>
</div>
@endsection