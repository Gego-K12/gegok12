@extends('layouts.admin.layout')

@section('content')

<div class="">
    <h1 class="admin-h1 mb-5 flex items-center">
      <a href="{{ url('/admin/exam') }}" title="Back" class="rounded-full bg-gray-300 p-2">
          <img src="{{asset('uploads/icons/back.svg')}}" class="w-3 h-3">
      </a>
      <span class="mx-3">Edit Exam</span>
    </h1>
    @include('partials.message')
    <edit-exam id="{{$exam->id}}" url="{{ url('/') }}"></edit-exam>  
</div>

@endsection



