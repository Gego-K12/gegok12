@extends('layouts.admin.layout')

@section('content')
    <div class="relative">
        <div class="flex flex-wrap lg:flex-row justify-between">
            <div class="">
                <h1 class="admin-h1 my-3">Exam Grade</h1>
            </div>
        </div>
        @include('partials.message')
       
        <list-grade url="{{ url('/') }}" mode="admin"></list-grade>
    </div>
@endsection