@extends('layouts.admin.layout')

@section('content')
    <add-sales url="{{ url('/') }}" product_id="{{$product_id}}" mode="admin" ></add-sales>
 
 @endsection