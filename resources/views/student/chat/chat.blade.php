@extends('layouts.student.layout')

@section('content')
    <div class="relative">
        <div class="flex justify-between mb-4 items-center">
            <h1 class="admin-h1 my-3">Rooms</h1>
            <form action="{{ url('/student/chats') }}" enctype="multipart form-data">
                <div class="">
                    <div class="flex items-center mx-2">
                        <div class="search relative mx-2">
                            <input type="text" name="search" class="border px-10 py-1 text-sm border-gray-400 w-full rounded bg-white shadow" placeholder="Search" value="{{ old('search') }}">  
                            <span class="input-group-btn absolute left-0 px-3 py-2 top-0">                  
                                <button type="submit">
                                    <svg class="w-4 h-4 fill-current text-gray-600" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="30.239px" height="30.239px" viewBox="0 0 30.239 30.239" style="enable-background:new 0 0 30.239 30.239;" xml:space="preserve"><g><path d="M20.194,3.46c-4.613-4.613-12.121-4.613-16.734,0c-4.612,4.614-4.612,12.121,0,16.735 c4.108,4.107,10.506,4.547,15.116,1.34c0.097,0.459,0.319,0.897,0.676,1.254l6.718,6.718c0.979,0.977,2.561,0.977,3.535,0 c0.978-0.978,0.978-2.56,0-3.535l-6.718-6.72c-0.355-0.354-0.794-0.577-1.253-0.674C24.743,13.967,24.303,7.57,20.194,3.46z M18.073,18.074c-3.444,3.444-9.049,3.444-12.492,0c-3.442-3.444-3.442-9.048,0-12.492c3.443-3.443,9.048-3.443,12.492,0 C21.517,9.026,21.517,14.63,18.073,18.074z"/></g></svg>
                                </button>
                            </span>
                        </div>
                        <div class="date-select date-select_none dashboard-reset mx-1 lg:mx-0 md:mx-0">
                            <a href="{{ url('/student/chats') }}" id="do-reset" class="text-sm border bg-gray-100 text-grey-darkest py-1 px-4">Reset</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="container">
        <div class="flex flex-wrap">
            @if(count($rooms) > 0)
                @foreach($rooms as $room)
                    <div class="w-1/2 lg:w-1/6 px-2 my-5">
                        <div class="w-full">
                            <div class="">
                                <div class="shadow-md">
                                    <a href="{{ url('/student/chat',$room) }}">
                                        <img class="card-img-top h-40 w-full" src="{{ $room->CoverImagePath }}" alt="{{ $room->title }}" id="name">
                                    </a>
                                </div>
                            </div>
                        </div> 

                        <div class="leading-loose my-1">
                            <div class="text-sm font-semibold text-gray-800 capitalize flex items-center justify-between">
                                <a href="{{url('/student/chat',$room)}}">{{ $room->title }} </a>
                                @if($room->status == 1)
                                    <div class="bg-green-500 px-2 rounded blink_me text-xs text-white">Active</div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="w-full bg-white shadow my-5 py-2 text-sm">
                    <p class="text-center">No Records Found</p>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <style>
        .blink_me {
            animation: blinker 1s linear infinite;
        }

        @keyframes blinker {
            0%{   color: green;   }
            50% {
                opacity: 0;
            }
        }
    </style>
@endpush