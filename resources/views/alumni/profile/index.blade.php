@extends('layouts.alumni.layout')

@section('content')
    <div class="relative">
        <div class="flex flex-wrap lg:flex-row justify-between my-3">
            <div id="alumni_count"></div>
            <!-- <div class="">
                <h1 class="admin-h1 my-3">Alumni ( {{ $count }} )</h1>
            </div> -->
            <div class="w-full lg:w-2/4">
   	            <div id="batch-filter"></div>
   	            <div id="teacherfilter"></div>
            </div>
        </div>
        @include('partials.message')
        <alumni-profile-list url="{{ url('/') }}" searchquery="{{ $query }}"></alumni-profile-list>
        <alumni-profile-batch-filter url="{{ url('/') }}" searchquery="{{ $query }}" batch="{{ $batch }}"></alumni-profile-batch-filter>
    </div>
@endsection