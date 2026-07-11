@extends('layouts.admin.layout')

@section('content')
   <list-purchase url="{{ url('/') }}" mode="admin" ></list-purchase>
@endsection