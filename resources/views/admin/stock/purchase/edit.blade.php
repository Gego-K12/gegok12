@extends('layouts.admin.layout')

@section('content')
    <edit-purchase url="{{ url('/') }}" purchaseid="{{$purchaseorder->id}}" mode="admin" ></edit-purchase>
 
 @endsection