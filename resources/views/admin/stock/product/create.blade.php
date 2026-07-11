@extends('layouts.admin.layout')

@section('content')
    <add-stock-product url="{{ url('/') }}" mode="admin" ></add-stock-product>
 
 @endsection