@extends('layouts.admin.layout')

@section('content')
    <div class="relative">
        <div id="add_fees"></div>
        @include('partials.message')
        <fee-list-tab url="{{ url('/') }}" mode="admin"></fee-list-tab>
        <div id="list_fee"></div>
    </div>
@endsection