@extends('layouts.admin.layout')

@section('content')

<div class="">
    <h1 class="admin-h1 my-3 flex items-center">
      <!-- <a href="{{ url('/admin/dashboard') }}" title="Back" class="rounded-full bg-gray-300 p-2">
          <img src="http://school-plus.test/uploads/icons/back.svg" class="w-3 h-3">
      </a> -->
      <span class="">Exam Rules</span>
    </h1>
    @include('partials.message')
    <create-examrules url="{{ url('/') }}"></create-examrules>  
</div>

@endsection



