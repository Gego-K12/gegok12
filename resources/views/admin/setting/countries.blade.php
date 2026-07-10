@extends('layouts.admin.layout')
@section('content')
<div class="w-full main-content flex h-auto">
<div class="flex flex-col lg:flex-row w-full">
@include('layouts.admin.settingsbar')
    <div class="relative w-full">
        <livewire:admin.setting.countries />
    </div>
</div>
</div>
@endsection