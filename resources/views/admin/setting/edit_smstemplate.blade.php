@extends('layouts.admin.settings-layout')
@section('content')
    <div class="relative">
        <livewire:admin.setting.edit-sms-template :id="$id" />
    </div>
@endsection