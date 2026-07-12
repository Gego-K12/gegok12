@extends('layouts.siteadmin.layout')
@section('content')
    <div class="relative">
        <livewire:admin.setting.city-form  :id="$id" />
    </div>
@endsection