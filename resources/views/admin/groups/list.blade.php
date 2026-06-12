@extends('layouts.admin.layout')

@section('content')
    <div class="">
        <h1 class="admin-h1 my-3 flex items-center">
            <span class="mx-3">Groups</span>
        </h1>
        @livewire('admin.groups.group-list')
    </div>
@endsection