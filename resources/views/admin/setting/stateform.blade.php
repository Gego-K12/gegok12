@extends('layouts.admin.layout')
@section('content')
<div class="w-full main-content flex h-auto">
<div class="flex flex-col lg:flex-row w-full p-6 gap-6">
@include('layouts.admin.settingsbar')
    <div class="relative w-full">
        <livewire:admin.setting.state-form  :id="$id" />
    </div>
</div>
</div>
@endsection