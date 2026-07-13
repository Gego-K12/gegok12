@extends('layouts.admin.layout')
@section('content')
<div class="w-full main-content flex h-auto">
<div class="flex flex-col lg:flex-row w-full p-1 gap-6">
<!-- settings sidebar start -->
@include('layouts.admin.settingsbar')
@include('admin.settings.maintenance_settings')
</div>
</div>
@endsection