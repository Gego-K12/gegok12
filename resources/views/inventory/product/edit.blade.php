@extends('layouts.admin.layout')

@section('content')
    <editproduct url="{{ url('/') }}" productid={{$id}} ></editproduct>
 
 @endsection