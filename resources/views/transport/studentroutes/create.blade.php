@extends('layouts.admin.layout')
@section('content')
    <div class="relative">
        <div class="flex items-center justify-between my-3">
            <h1 class="admin-h1">Add Students for a Route</h1>
        </div>
        <livewire:transport.student-route />
    </div>
@endsection