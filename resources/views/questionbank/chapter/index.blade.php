@extends('layouts.admin.layout')

@section('content')
	<div class="relative">
    
      @include('partials.message')
      <list-chapter url="{{ url('/') }}"  mode="admin"></list-chapter>
   </div>
@endsection