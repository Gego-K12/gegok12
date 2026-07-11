@extends('layouts.admin.layout')

@section('content')
    <show-return-order url="{{ url('/') }}" mode="admin"></show-return-order>
@endsection