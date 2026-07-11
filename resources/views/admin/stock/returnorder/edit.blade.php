@extends('layouts.admin.layout')

@section('content')
    <edit-return-order url="{{ url('/') }}" returnorderid="{{ $returnorder->id }}" mode="admin"></edit-return-order>
 
 @endsection