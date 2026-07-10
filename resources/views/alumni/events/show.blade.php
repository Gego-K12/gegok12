@extends('layouts.alumni.layout')

@section('content')
    <div class="">
        <div>
            <h1 class="admin-h1 mb-3 flex items-center">
                <a href="{{ url('/alumni/events') }}" class="rounded-full bg-gray-300 p-2" title="Back"><img src="{{asset('uploads/icons/back.svg')}}" class="w-3 h-3"></a>
                <span class="mx-3">Event Details</span>
            </h1>
        </div>
        <div class="flex flex-col lg:flex-row md:flex-row">
            <div class="w-full lg:w-1/5 md:w-1/5 py-3">
                <div>
                    <img src="{{ $event->ImagePath }}" class="h-40">
                </div>
            </div>

            <input type="hidden" name="event_id" value="{{ $event->id }}" id="event_id">

            <div class="w-full lg:w-4/5 md:w-4/5 lg:mx-6 md:mx-6 relative">
                <div>
                    <h3 class="font-semibold text-3xl text-gray-700">{{ $event->title }}</h3>
                    <div class="">
                        <div class="flex items-start mt-5">
                            <div class="py-1"><img src="{{ asset('uploads/icons/microphone.svg') }}" class="w-4 h-4"></div>
                            <div class="text-sm mx-2 flex items-center">
                                <div class="w-24">
                                    <p class="text-gray-700 font-semibold">Organised By:</p>
                                </div>
                                <div class="text-sm tracking-wider">
                                    <p class="text-gray-600">{{ $event->organised_by }}</p>
                                </div>
                            </div>
                        </div>

                        @if($event->select_type=='class')
                            <div class="flex items-start mt-5">
                                <div class="py-1">
                                    <img src="{{ asset('uploads/icons/microphone.svg') }}" class="w-4 h-4">
                                </div>
                                <div class="text-sm mx-2 flex items-center">
                                    <div class="w-24">
                                        <p class="text-gray-700 font-semibold">Standard:</p>
                                    </div>
                                    <div class="text-sm tracking-wider">
                                        <p class="text-gray-600">{{ $event->standardlink->StandardSection }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="flex items-start mt-5">
                            <div class="py-1">
                                <img src="{{ asset('uploads/icons/event-clock.svg') }}" class="w-4 h-4">
                            </div>
                            <div class="text-sm mx-2 flex items-center">
                                <div class="w-20">
                                    <p class="text-gray-700 font-semibold">Start Date:</p>
                                    <p class="text-gray-700 font-semibold">End Date:</p>
                                </div>
                                <div class="text-sm tracking-wider">
                                    <p class="text-gray-600">{{ date('d-m-Y h:i A',strtotime($event->start_date))}}</p>
                                    <p class="text-gray-600">{{ date('d-m-Y h:i A',strtotime($event->end_date))}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-start mt-5">
                            <div class="py-1">
                                <img src="{{ asset('uploads/icons/event-location.svg') }}" class="w-4 h-4">
                            </div>
                            <div class="text-sm mx-2">
                                <p class="text-gray-600">{{ $event->location }}</p>
                            </div>
                        </div>
                    </div>
                </div>  
            </div>
        </div>

        @if($event->category!='exam')
            <div class="bg-white shadow my-5">
                <event-tab url="{{url('/')}}" id="{{$event->id}}" event_id="{{$event->event_id}}" entity_id="{{ $event->id }}" entity_name="event" school_id="{{ $event->school_id }}"></event-tab>
                <div id="events"></div>
            </div>
        @endif
    </div>
@endsection