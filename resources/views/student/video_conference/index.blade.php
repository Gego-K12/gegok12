@extends('layouts.student.layout')
@section('content')
    <div class="py-3">
        <div class="flex justify-between mb-4 items-center">
            <h2 class="text-lg">Video Room</h2>
            <form action="{{ url('/student/video-conference') }}" enctype="multipart form-data">
                <div class="">
                    <div class="flex items-center mx-2">
                        <div class="search relative mx-2">
                            <input type="text" name="search" class="border px-10 py-1 text-sm border-gray-400 rounded bg-white shadow" placeholder="Search" value="{{ old('search') }}">  
                            <span class="input-group-btn absolute left-0 px-3 py-2 top-0">                  
                                <button type="submit">
                                    <svg class="w-4 h-4 fill-current text-gray-600" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="30.239px" height="30.239px" viewBox="0 0 30.239 30.239" style="enable-background:new 0 0 30.239 30.239;" xml:space="preserve"><g><path d="M20.194,3.46c-4.613-4.613-12.121-4.613-16.734,0c-4.612,4.614-4.612,12.121,0,16.735 c4.108,4.107,10.506,4.547,15.116,1.34c0.097,0.459,0.319,0.897,0.676,1.254l6.718,6.718c0.979,0.977,2.561,0.977,3.535,0 c0.978-0.978,0.978-2.56,0-3.535l-6.718-6.72c-0.355-0.354-0.794-0.577-1.253-0.674C24.743,13.967,24.303,7.57,20.194,3.46z M18.073,18.074c-3.444,3.444-9.049,3.444-12.492,0c-3.442-3.444-3.442-9.048,0-12.492c3.443-3.443,9.048-3.443,12.492,0 C21.517,9.026,21.517,14.63,18.073,18.074z"/></g></svg>
                                </button>
                            </span>
                        </div>
                        <div class="date-select date-select_none dashboard-reset mx-1 lg:mx-0 md:mx-0">
                            <a href="{{ url('/student/video-conference') }}" id="do-reset" class="text-sm border bg-gray-100 text-grey-darkest py-1 px-4">Reset</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        @include('partials.message')

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
                    </div>
                    <div class="my-2 flex flex">
                        <div class="">Created By : @if(isset($stream->userInfo->FullName)){{ $stream->userInfo->FullName }}@else - @endif </div>
                    </div>
                    <div class="my-2">
                        {{ $stream->description }}
                    </div>
                     <div class="my-2 flex flex-col">
                        <div class="flex">Subject :{{ ucfirst($stream->subject->name) }}</div>
                        <div class="flex">Schedule :{{ date('d M Y h:i A',strtotime($stream->joining_date)) }} - {{ ucfirst($stream->duration) }} mins</div>
                    </div>
                    <div class="flex justify-start">
                       <!--  @if($stream->status!='stop')
                            <a class="block text-center btn px-4 py-1 rounded bg-green-600 text-white" href="{{url('student/video-conference/'.$stream->slug)}}">Go to Room</a>
                        @endif -->
                         @if($stream->class_link!='' && $stream->class_link!=null)
                            <a class="block text-center btn px-4 py-1 rounded bg-green-600 text-white" href="{{$stream->class_link}}" target="_blank">Class link</a>
                        @endif

                        @if($stream->class_link=='' || $stream->class_link==null)
                        @if($stream->status!='stop')
                            <a class="block text-center btn px-4 py-1 rounded bg-green-600 text-white" href="{{url('student/video-conference/'.$stream->slug)}}">Go to Room</a>
                        @endif
                        @endif
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
