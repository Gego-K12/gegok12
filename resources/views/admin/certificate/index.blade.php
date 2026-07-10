@extends('layouts.admin.layout')

@section('content')
	<div class="relative">
    
      @include('partials.message')
      <list-certificate url="{{ url('/') }}" mode="admin"></list-certificate>
   </div>
@endsection