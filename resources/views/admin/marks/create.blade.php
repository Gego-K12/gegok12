@extends('layouts.admin.layout')

@section('content')

<div class="">
    <h1 class="admin-h1 mb-5 flex items-center">
      <!-- <a href="{{ url('/admin/dashboard') }}" title="Back" class="rounded-full bg-gray-300 p-2">
          <img src="http://school-plus.test/uploads/icons/back.svg" class="w-3 h-3">
      </a> -->
      <span class="mx-3">Mark Sheet</span>
    </h1>
    @include('partials.message')
    <create-mark user_id="{{ $user_id }}" standard_id="{{ $standard_id }}"></create-mark>  
</div>

@endsection



