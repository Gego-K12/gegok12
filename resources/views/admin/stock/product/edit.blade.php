@extends('layouts.admin.layout')

@section('content')
    <edit-stock-product url="{{ url('/') }}" productid={{$id}} mode="admin"></edit-stock-product>
 
 @endsection