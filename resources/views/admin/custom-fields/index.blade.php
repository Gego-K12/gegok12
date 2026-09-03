@extends('layouts.admin.layout')

@section('content')
    <div class="">
        <h1 class="admin-h1 my-3 flex items-center">
            <span class="mx-3">Custom Fields</span>
        </h1>
        @livewire('admin.custom-fields.custom-field-manager')
    </div>
@endsection
