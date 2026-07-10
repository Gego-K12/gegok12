@extends('layouts.student.layout')
@section('content')
	<div class="flex flex-col leading-relaxed">
		<p class="text-gray-700 font-medium admin-h1 my-3">Online Assessment :<span class="text-gray-700 font-medium">{{ucfirst($test->quiztopic->name)}}</span></p>
	</div>
   	<test-review url="{{ url('/') }}" test_id="{{ $test->id }}"></test-review>
@endsection