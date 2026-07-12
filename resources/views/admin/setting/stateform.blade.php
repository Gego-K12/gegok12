@extends('layouts.siteadmin.layout')
@section('content')
    <div class="relative">
        <livewire:admin.setting.state-form  :id="$id" />
    </div>
@endsection