@extends('layouts.student.layout')

@section('content')
	<div class="relative">
      <div class="flex flex-wrap lg:flex-row justify-between">
         <div>
            <h1 class="admin-h1 my-3 flex items-center">
               <span class="mx-3">Online Assessment</span>
            </h1>
         </div>
      </div>
      @include('partials.message')
      <quiz-question url="{{ url('/') }}" test_id="{{ $test->id }}" topic="{{ $topic->id }}" timing="{{ $topic->timer }}" total_count="{{ $count }}" completed_score="{{ $test->score }}" total_answered="{{ $attend }}"></quiz-question>
   </div>
@endsection