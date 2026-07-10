@extends('layouts.teacher.layout')

@section('content')
	<div>
		<h1 class="admin-h1 my-3 flex items-center">
			<a href="{{ url('/teacher/quiz/'.$test->quiztopic->id.'/show') }}" title="Back" class="rounded-full bg-gray-100 p-2">
				<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 492 492" xml:space="preserve" width="512px" height="512px" class="w-3 h-3 fill-current text-gray-700"><g><g><g><path d="M464.344,207.418l0.768,0.168H135.888l103.496-103.724c5.068-5.064,7.848-11.924,7.848-19.124    c0-7.2-2.78-14.012-7.848-19.088L223.28,49.538c-5.064-5.064-11.812-7.864-19.008-7.864c-7.2,0-13.952,2.78-19.016,7.844    L7.844,226.914C2.76,231.998-0.02,238.77,0,245.974c-0.02,7.244,2.76,14.02,7.844,19.096l177.412,177.412    c5.064,5.06,11.812,7.844,19.016,7.844c7.196,0,13.944-2.788,19.008-7.844l16.104-16.112c5.068-5.056,7.848-11.808,7.848-19.008    c0-7.196-2.78-13.592-7.848-18.652L134.72,284.406h329.992c14.828,0,27.288-12.78,27.288-27.6v-22.788    C492,219.198,479.172,207.418,464.344,207.418z" data-original="#000000" fill="" class="active-path"></path></g></g></g></svg>
			</a> 
			<span class="mx-3">Online Assessment</span>
		</h1>
	</div>
   	<div class="bg-white shadow my-5 ">
		<h2 class="mr-60 p-2">Result</h2>
		<div class="flex border shadow p-4 text-white justify-between">
			<input type="hidden" name="" id="test_id" value="{{ $test->test_id }}">
			<div class="flex flex-col leading-relaxed">
				<p class="text-gray-700 font-medium">Online Assessment :<span class="text-gray-700 font-medium"> {{ ucfirst($test->quiztopic->name) }}</span></p>
				<p class="text-gray-700 font-medium"> Taken By:<span class="text-gray-700 font-medium"> {{ $test->user->FullName }}</span></p>
				<p class="text-gray-700 font-medium"> Taken On:<span class="text-gray-700 font-medium"> {{ date('d-m-Y H:i:s',strtotime($test->attempt_at)) }}</span></p>
			</div>
			<div class="">
				<!-- <p>Score</p> -->
				<div class="flex">
					<p id="score" class="text-gray-700 text-4xl">{{ $test->score }}</p>
					<span class="text-gray-700 text-4xl">/{{ count($test->quiztestanswers) }}</span>
				</div>
			</div>
		</div>
	</div>
  	<div class="bg-white shadow p-3 my-5">
   		{{-- <test-details url="{{ url('/') }}" test_id="{{ $test->id }}" question_count="{{ count($test->quizquestions) }}"></test-details> --}}  

   		<test-details url="{{ url('/') }}" test_id="{{ $test->id }}"></test-details>       
    </div>
@endsection