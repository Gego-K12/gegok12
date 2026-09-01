@extends('layouts.admin.settings-layout')

@section('content')
    <h1 class="admin-h1 my-3 flex items-center">
        <span class="mx-3">Admission Settings</span>
    </h1>
    @livewire('admin.admission.admission-settings')
@endsection
