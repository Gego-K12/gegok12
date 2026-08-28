@extends('layouts.admission')
@section('content')
    <div class="w-full lg:mx-2">
        <div class="bg-white shadow py-3">
            <div class="container mx-auto px-3 lg:px-0">
                <div>
                    <a href="{{ url('/') }}">
                        <img src="{{ $logo }}" class="inline-block" style="height:55px;">
                    </a>
                </div>
            </div>
        </div>
        @if($admission_open['meta_value']=="1")
            <h1 class="admin-h1 my-3 flex items-center">       
                <span class="mx-3">Admission Form</span>
            </h1>
            @include('partials.message')
            <!-- multistep form -->
            <div id="msform" class="w-full lg:w-1/2 mx-auto">
                <livewire:admission.admission-form :slug="$slug" />
            </div>

        @elseif($admission_open['meta_value']=="0")
            <h1 class="admin-h1 my-3 flex items-center">       
                <span class="mx-3">{{ $closedetails['meta_value'] }}</span>
            </h1>
        @else
        <h1 class="admin-h1 my-3 flex items-center">       
                <span class="mx-3">Admission Not Opened</span>
            </h1>    
        @endif
    </div>
@endsection