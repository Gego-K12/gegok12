@extends('layouts.teacher.layout') 

@section('content')
<div class=" container mx-auto py-3">
<div class="flex justify-between mb-4">
    <h2 class="text-lg my-2">Video Room</h2>
    <a href="{{ url('video-conference/create') }}" class="btn bg-green-600 text-white px-4 py-2 rounded">
    Create Video Chat Room</a>
</div>
@if(Session::has('message'))
	<div class="alert alert-success flex flex-col">
		<strong>Success!</strong> 
        {!! Session::get('message') !!}
	</div>
@endif

@if(Session::has('error'))
	<div class="alert alert-danger flex flex-col">
		<strong>Error!</strong> 
        {!! Session::get('error') !!}
	</div>
@endif

@if(count($getStream)>0)

@php 
    $i = ($getStream->currentPage() - 1) * $getStream->perPage() + 1; 
@endphp

@foreach($getStream as $stream)

<div class="flex flex-col bg-white border shadow p-3 mb-4">
    <div class="flex justify-between">
        <div class="uppercase tracking-wide text-sm text-indigo-600 font-bold">
            {{ $stream->name }} <span class="mx-3 text-xs bg-indigo-600 text-white p-2 rounded">{{ $stream->status }}</span>
        </div>
        <div class="">
            <a href="#" class="btn bg-green-600 text-white rounded px-3 py-1">Edit</a>
            <a href="#" class="btn bg-red-600 text-white rounded px-3 py-1">Delete</a>
        </div>
    </div>
    <div class="my-2 flex flex">
        <div class="">Created By : {{ $stream->user_id }} on {{ $stream->created_at->diffForHumans() }}</div>
    </div>
    <div class="my-2">
        {{ $stream->description }}
    </div>
    <div class="flex justify-start">
        <a class="block text-center btn px-4 py-1 rounded bg-green-600 text-white" href="{{url('video-conference/'.$stream->slug)}}">
            Go to Room
        </a>
        <a class="ml-4 block text-center btn px-4 py-1 rounded bg-green-600 text-white" href="#">
            Manage Invitees
        </a>
    </div>
</div>
@endforeach

@else
    <div class="md:flex bg-white border shadow p-3">
        <div class="my-2">
            No records found
        </div>
    </div>
@endif


{{$getStream->links('layouts.pagination',array('search'=>$build))}}






</div>

@endsection

@push('scripts')

@endpush