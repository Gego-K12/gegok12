@extends('layouts.admin.settings-layout')
@section('content')
    <div class="relative">
        <livewire:admin.setting.country-detail  :id="$id" />
    </div>
@endsection