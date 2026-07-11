@extends('layouts.admin.layout')

@section('content')
    <add-return-order url="{{ url('/') }}" product_id="{{$product_id}}" mode="admin"  ></add-return-order>
 
 @endsection