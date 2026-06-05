@extends('layouts.admin.layout')

@section('content')
    <div class="">
       @livewire('admin.groups.group-members', ['id' => $id])
    </div>
@endsection