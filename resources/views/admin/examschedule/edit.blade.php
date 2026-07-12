@extends('layouts.admin.layout')

@section('content')

<div class="">
    <h1 class="admin-h1 mb-5 flex items-center">
      <a href="{{ url('/admin/examschedule') }}" title="Back" class="rounded-full bg-gray-300 p-2">
          <img src="{{asset('uploads/icons/back.svg')}}" class="w-3 h-3">
      </a>
      <span class="mx-3">Edit Exam Schedule</span>
    </h1>
    @include('partials.message')
    <edit-examschedule id="{{$schedule->id}}" url="{{ url('/') }}"></edit-examschedule>  
</div>

@endsection



