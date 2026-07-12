@extends('layouts.admin.settings-layout')
@section('content')
    <div class="relative">
        <livewire:admin.setting.state-detail  :id="$id" />
    </div>
@endsection