@extends('layouts.admin.layout')

@section('content')
    <edit-sales url="{{ url('/') }}" salesid="{{ $salesorder->id }}" mode="admin"></edit-sales>
 
 @endsection