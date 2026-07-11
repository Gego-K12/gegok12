@extends('layouts.admin.layout')

@section('content')
    <vehicle-list url="{{ url('/') }}" ></vehicle-list>
 
 @endsection