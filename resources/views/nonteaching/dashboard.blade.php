@extends('layouts.nonteaching.layout')

@section('content')

    <div class="">
        <div>
            <h1 class="admin-h1 font-plex my-3">Dashboard</h1>
        </div>
        @include('partials.message')

        <div class="flex flex-wrap my-2">
            <div class="w-full lg:w-1/3 md:w-1/2 px-1 my-3">
                <div class="bg-white custom-shadow px-3 py-2 border">
                    <div>
                        <h1 class="text-gray-800 font-semibold text-lg border-b mx-2 py-1 pb-3">Notice Board</h1>
                    </div>
                    <div class="notice-box">
                        @if(count($dashboard['noticeboard']) > 0)
                            @foreach($dashboard['noticeboard'] as $noticeboard)
                                <div class="notice-box-list py-3 mx-3 border-b">
                                    <div class="bg-teal-500 text-xs rounded-full inline-block text-white px-2 py-1 my-1 mb-2">
                                        <p>{{ $noticeboard->title }}</p>
                                    </div>
                                    <div class="bg-purple-500 text-xs rounded-full inline-block text-white px-2 py-1 my-1 mb-2">
                                        <p>{{ date('d M Y',strtotime($noticeboard->publish_date)) }}</p>
                                    </div>
                                    <div class="bg-orange-500 text-xs rounded-full inline-block text-white px-2 py-1 my-1 mb-2">
                                        <p>
                                            @if($noticeboard->type == 'class')
                                                {{ $noticeboard->standardLink->StandardSection }}
                                            @else
                                                {{ ucwords($noticeboard->type) }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="my-1">
                                        <p class="text-sm text-gray-900 font-semibold">{!! $noticeboard->description !!}</p>
                                    </div>
                                    <div class="text-sm my-1">
                                        <p class="text-gray-500">
                                            <span class="text-gray-500">{{ $noticeboard->created_at->diffForHumans() }}</span>
                                        </p>
                                        @if($noticeboard->attachment_file != '')
                                            <div class="flex justify-end">
                                                <a href="{{ $noticeboard->AttachmentPath }}" target="_blank">
                                                    <svg class="w-5 h-5 fill-current mr-1 text-gray-600" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve"><g><g><path d="M472,313v139c0,11.028-8.972,20-20,20H60c-11.028,0-20-8.972-20-20V313H0v139c0,33.084,26.916,60,60,60h392 c33.084,0,60-26.916,60-60V313H472z"/></g></g><g><g><polygon points="352,235.716 276,311.716 276,0 236,0 236,311.716 160,235.716 131.716,264 256,388.284 380.284,264"/></g></g></svg>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="notice-box-list py-3 mx-3 border-b">
                                <p class="text-sm text-gray-900 font-semibold" style="text-align: center;">No Notice Found</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
