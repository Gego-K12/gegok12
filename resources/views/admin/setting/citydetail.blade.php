@extends('layouts.admin.settings-layout')
@section('content')
    <div class="relative">
        <livewire:admin.setting.city-detail  :id="$id" />
    </div>
@endsection