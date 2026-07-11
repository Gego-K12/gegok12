@extends('layouts.admin.layout')

@section('content')
    <add-purchase url="{{ url('/') }}" product_id="{{$product_id}}" ></add-purchase>
 
 @endsection