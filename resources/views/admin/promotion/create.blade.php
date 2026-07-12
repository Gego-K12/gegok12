@extends('layouts.admin.settings-layout')

@section('content')


<div class="relative">
	<div class="flex flex-wrap lg:flex-row justify-between">
		<div class="">
		   <h1 class="admin-h1 my-3">Promotion</h1>
		</div>
	</div>
	@include('partials.message')
	<create-promotion url="{{ url('/') }}"></create-promotion>  

</div>

@endsection



