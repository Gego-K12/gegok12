@extends('layouts.admin.layout')

@section('content')
    <product-list url="{{ url('/') }}" mode="admin"  ></product-list>
 
 @endsection