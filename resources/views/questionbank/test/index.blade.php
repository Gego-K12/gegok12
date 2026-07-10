@extends('layouts.admin.layout')

@section('content')
	<div class="relative">
    
      @include('partials.message')
      <list-test url="{{ url('/') }}" today="{{$today}}"  mode="admin"></list-test>
   </div>
@endsection