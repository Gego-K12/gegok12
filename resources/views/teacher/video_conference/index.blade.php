@extends('layouts.teacher.layout')
@section('content')
    <div class="py-3">
        <div class="flex justify-between mb-4 items-center">
            <h2 class="text-lg">Video Room</h2>
            <form action="{{ url('/teacher/video-conference') }}" enctype="multipart form-data">
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

                        <div class="flex flex-wrap items-center mx-3">
                            <select  name="type" id="type" class="tw-form-control text-xs">
                                <option  value="by_me" @if ($type == null ) {{ 'selected' }} @endif >Assigned By Me</option>
                                <option  value="to_me" @if ($type == "to_me") {{ 'selected' }} @endif>Assigned To Me</option>
                            </select>
                        </div>

                        <div class="date-select date-select_none dashboard-reset mx-1 lg:mx-0 md:mx-0">
                            <a href="{{ url('/teacher/video-conference') }}" id="do-reset" class="text-sm border bg-gray-100 text-grey-darkest py-1 px-4">Reset</a>
                        </div>
                    </div>
                </div>
            </form>
            <a href="{{url('/teacher/video-conference/create')}}" class="no-underline text-white  px-4 my-3 mx-1 flex items-center custom-green py-1 justify-center">
                <span class="mx-1 text-sm font-semibold">Add</span>
                <svg class="w-3 h-3 fill-current text-white" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 409.6 409.6" style="enable-background:new 0 0 409.6 409.6;" xml:space="preserve"><g><g><path d="M392.533,187.733H221.867V17.067C221.867,7.641,214.226,0,204.8,0s-17.067,7.641-17.067,17.067v170.667H17.067 C7.641,187.733,0,195.374,0,204.8s7.641,17.067,17.067,17.067h170.667v170.667c0,9.426,7.641,17.067,17.067,17.067 s17.067-7.641,17.067-17.067V221.867h170.667c9.426,0,17.067-7.641,17.067-17.067S401.959,187.733,392.533,187.733z"/></g></g></svg>
            </a>
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
                        <div class="">
                            @if($stream->user_id==Auth::user()->id && $stream->status!='stop')
                                <a href="{{url('teacher/video-conference/edit/'.$stream->id)}}" class="btn bg-green-600 text-white rounded px-3 py-1">Edit</a>
                                <a href="javascript:void(0);" class="btn bg-red-600 text-white rounded px-3 py-1 delete" data-id="{{$stream->id}}">Delete</a>
                            @endif
                        </div>
                    </div>
                    <div class="my-2 flex flex">
                        <div class="">Created By : @if(isset($stream->userInfo->FullName)){{ $stream->userInfo->FullName }}@else - @endif on {{ $stream->created_at->diffForHumans() }}</div>
                    </div> 
                    <div class="my-2">
                       <a href="{{url('teacher/video-conference/show/'.$stream->id)}}" target="blank">{{ $stream->description }}</a>
                    </div>
                     <div class="my-2 flex flex-col">
                        <div class="flex">Class :{{ $stream->standardlink->StandardSection }} {{ ucfirst($stream->subject->name ?? '') }}</div>
                        <div class="flex">Schedule :{{ date('d M Y h:i A',strtotime($stream->joining_date)) }} - {{ ucfirst($stream->duration) }} mins</div>
                    </div>
                    <div class="flex justify-start">
                       <!--  @if($stream->status!='stop')
                            <a class="block text-center btn px-4 py-1 rounded bg-green-600 text-white" href="{{url('teacher/video-conference/'.$stream->slug)}}">Go to Room</a>
                        @endif -->
                         @if($stream->class_link!='' && $stream->class_link!=null)
                            <a class="block text-center btn px-4 py-1 rounded bg-green-600 text-white" href="{{$stream->class_link}}" target="_blank">Class link</a>
                        @endif

                        @if($stream->class_link=='' || $stream->class_link==null)
                        @if($stream->status!='stop')
                            <a class="block text-center btn px-4 py-1 rounded bg-green-600 text-white" href="{{url('teacher/video-conference/'.$stream->slug)}}">Go to Room</a>
                        @endif
                        @endif
                       <!--  @if($stream->compose_status=='available')
                            <a class="block text-center btn px-4 py-1 rounded bg-green-600 text-white" href="{{url('teacher/video-conference/recordings/'.$stream->id)}}">Go to Recordings</a>
                        @endif -->
                        @if($stream->user_id==Auth::user()->id && $stream->status!='stop')
                            <a class="ml-4 block text-center btn px-4 py-1 rounded bg-green-600 text-white" href="{{url('teacher/video-conference/manage-invites/'.$stream->id)}}">Manage Invitees</a>
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

@push('scripts')
<script type="text/javascript">
    $(function(){
        $(document).on('click', '.delete', function(){
            var r = confirm("Are you sure to delete?");
            if (r == true) {
                var getid = $(this).data('id');
                window.location.href = '{{url("teacher/video-conference/remove")}}/'+getid;
            }
        });
    });

     $(document).on('change', '#type', function(){
           var type=$(this).val();
           //alert(type);
           if(type=="to_me")
           {
            window.location.href = '{{url("teacher/video-conference?type=")}}'+type;
           }
           else{
             window.location.href = '{{url("teacher/video-conference")}}';
         }
           
        
        });
</script>

@endpush