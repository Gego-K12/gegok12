@extends('layouts.admin.layout')

@section('content')
    <edit-vehicle url="{{ url('/') }}" vehicleid={{$id}} ></edit-vehicle>
 
 @endsection