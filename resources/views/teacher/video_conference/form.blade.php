@extends('layouts.teacher.layout')
@section('content')
   <!--  <div class="w-full">
        <div>
            <h1 class="admin-h1 my-3 flex items-center">
                <a href="{{ url('/teacher/video-conference') }}" title="Back" class="rounded-full bg-gray-100 p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 492 492" xml:space="preserve" width="512px" height="512px" class="w-3 h-3 fill-current text-gray-700"><g><g><g><path d="M464.344,207.418l0.768,0.168H135.888l103.496-103.724c5.068-5.064,7.848-11.924,7.848-19.124 c0-7.2-2.78-14.012-7.848-19.088L223.28,49.538c-5.064-5.064-11.812-7.864-19.008-7.864c-7.2,0-13.952,2.78-19.016,7.844    L7.844,226.914C2.76,231.998-0.02,238.77,0,245.974c-0.02,7.244,2.76,14.02,7.844,19.096l177.412,177.412 c5.064,5.06,11.812,7.844,19.016,7.844c7.196,0,13.944-2.788,19.008-7.844l16.104-16.112c5.068-5.056,7.848-11.808,7.848-19.008 c0-7.196-2.78-13.592-7.848-18.652L134.72,284.406h329.992c14.828,0,27.288-12.78,27.288-27.6v-22.788    C492,219.198,479.172,207.418,464.344,207.418z" data-original="#000000" fill="" class="active-path"></path></g></g></g></svg>
                </a>
                <span class="mx-3">Create Room</span>
            </h1>
        </div>
        <div class="bg-white shadow px-4 py-3">
            @include('partials.message')
            <form method="post" action="{{ url('teacher/video-conference/save') }}" id="stream" enctype="multipart/form-data">
                @csrf
                <div class="my-3">
                    <div class="">
                        <div class="w-full lg:w-1/4">
                            <label for="name" class="tw-form-label">Title</label>
                        </div>
                        <div class="w-full lg:w-2/5 my-2">
                            <input type="text" name="name" id="name" class="tw-form-control w-full" value="{{old('name')}}" placeholder="Title">
                            <span class="text-danger text-xs">{{$errors->first('name')}}</span>
                        </div>
                    </div>
                </div>
                <div class="my-3">
                    <div class="">
                        <div class="w-full lg:w-1/4">
                            <label for="description" class="tw-form-label">Description</label>
                        </div>
                        <div class="w-full lg:w-2/5 my-2">
                            <textarea type="textarea" name="description" id="description" class="tw-form-control w-full" placeholder="Description">{{old('description')}}</textarea>
                            <span class="text-danger text-xs">{{$errors->first('description')}}</span>
                        </div>
                    </div>
                </div>
                 <div class="my-3">
                    <div class="">
                        <div class="w-full lg:w-1/4">
                            <label for="joining_date" class="tw-form-label">Schedule date</label>
                        </div>
                        <div class="w-full lg:w-2/5 my-2">
                            <input type="datetime-local" name="joining_date" class="tw-form-control w-full " value="{{old('joining_date')}}" placeholder="Joining date">
                            <span class="text-danger text-xs">{{$errors->first('joining_date')}}</span>
                        </div>
                    </div>
                </div>
                <div class="my-3">
                    <div class="">
                        <div class="w-full lg:w-1/4">
                            <label for="duration" class="tw-form-label">Duration(mins)</label>
                        </div>
                        <div class="w-full lg:w-2/5 my-2">
                            <input type="text" name="duration" class="tw-form-control w-full " value="{{old('duration')}}" placeholder="Duration">
                            <span class="text-danger text-xs">{{$errors->first('duration')}}</span>
                        </div>
                    </div>
                </div>
                <div class="my-3">
                    <div class="">
                        <div class="w-full lg:w-1/4">
                            <label for="description" class="tw-form-label">Select Class</label>
                        </div>
                        <div class="w-full lg:w-2/5 my-2">
                            <select name="standard" class="mdb-select md-form" id="standard">
                                <option value="" selected>Choose class</option>
                                @if(count($standardLink)>0)
                                    @foreach($standardLink as $standard)
                                        <option value="{{$standard->id}}" {{ $standard->id == old('standard') ? 'selected' : '' }}>{{ $standard->StandardSection }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <span class="text-danger text-xs">{{$errors->first('standard')}}</span>
                        </div>
                    </div>
                    <div class="">
                        <div class="w-full lg:w-1/4">
                            <label for="description" class="tw-form-label">Select subject</label>
                        </div>
                        <div class="w-full lg:w-2/5 my-2">
                            <select name="subject" class="mdb-select md-form" id="subject">
                                <option value="" selected>Choose subject</option>
                            </select>
                            <span class="text-danger text-xs">{{$errors->first('standard')}}</span>
                        </div>
                    </div>
                </div>
                <div class="my-3">
                    <div class="">
                        <div class="w-full lg:w-2/5 my-2" id="student_info" style="display: none;">
                            <div class="flex items-center text-sm">
                                <div class="px-3 border-r">
                                    <span id="slx">0</span> students selected
                                </div>
                                <div class="px-3 border-r relative">
                                    <input class="opacity-0 absolute w-full h-full cursor-pointer" type="checkbox" id="selectall"><span>Select All</span>
                                </div>
                                <div class="px-3 relative" id="unselect" style="display: none;">
                                    <input class="opacity-0 absolute w-full h-full cursor-pointer" id="unselectall" type="checkbox"><span>Select None</span>
                                </div>
                            </div> 
                            <div class="flex flex-wrap" id="student_block">
                            </div>
                            <span class="text-danger text-xs">{{$errors->first('students')}}</span>
                        </div>
                    </div>
                </div>
                <div class="mt-6 mb-4">
                    <button class="btn btn-primary blue-bg text-white rounded px-3 py-1 text-sm font-medium" id="submit">Submit</button>
                </div>
            </form>
        </div>
    </div> -->
    <create-teacher-conference url="{{ url('/') }}" date="{{ date('d-m-Y H:i:s') }}" ></create-teacher-conference>
@endsection

@push('scripts')

    {{-- <script type="text/javascript">
        $(function(){
            var studentid = [];
            @if(count(old('students'))>0)
                @foreach(old('students') as $student)
                    var idx = '{{$student}}';
                    studentid.push(idx);
                @endforeach
            @endif
            var standard = $('#standard').val();
            if (typeof standard != 'undefined' && standard) {
                ajaxdata(standard, studentid);
            }

            $(document).on('click','#selectall',function(){
                $(".check_cls").slice(0,50).prop('checked', $(this).prop('checked'));
                $('#unselect').show();
                var numberOfChecked = $('input[name="students[]"]:checked').length;
                $("#slx").text(numberOfChecked);
            });

            $(document).on('click','#unselectall',function(){
                $(".check_cls").prop('checked', false);
                $("#slx").text('0');
            });

            $(document).on('click','.check_cls',function(){
                var numberOfChecked = $('input[name="students[]"]:checked').length;
                if(numberOfChecked>50){
                    return false;
                }
                $("#slx").text(numberOfChecked);
            });

            $(document).on('change','#standard',function(){
                var standard = $(this).val();
                ajaxdata(standard, studentid);
                ajaxdatasuject(standard);
            });
        });

        function ajaxdata(standard, studentid){
            return  $.ajax({
                url: '{{url("teacher/video-conference/student-list")}}',
                type: "POST",
                data: 'standard='+standard,
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success:function(response){
                    if(response.success){
                        $('#student_info').show();
                        $("#student_block").empty();
                        $("#slx").text('0');
                        $.each(response.data, function( index, value ) {
                            $('#student_block').append('<div class="w-full lg:w-1/2 md:w-1/2 my-2 relative" v-for="user in users"><div class="flex justify-between member-list"><div class="flex items-center  student_select"><input class="w-5 h-5 check_cls" type="checkbox" name="students[]" value="'+value['id']+'" id="student_'+value['id']+'"><label></label></div><div class="flex p-2 active w-full"><div class="px-2"><h2 class="font-bold text-base text-gray-700">'+value['fullname']+'</h2></div></div></div></div>')
                        });
                    }
                },complete:function(response){
                    $.each(studentid, function(index, value){
                        $('#student_'+value).prop('checked', true);
                    });
                }
            });

        }

        function ajaxdatasuject(standard){
            return  $.ajax({
                url: '{{url("teacher/video-conference/subject-list")}}',
                type: "POST",
                data: 'standard='+standard,
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success:function(response){
                    if(response.success){
                        $("#subject").empty();
                        //$("#subject").append('<option>Choose subject</option>');
                        $.each(response.data,function(key,value){
                        $("#subject").append('<option value="'+value['subject_id']+'">'+value['subject_name']+'</option>');
                        });
                       console.log(response);
                    }
                    else
                    {
                        $("#subject").empty();
                        //$("#subject").append('<option>Choose subject</option>');
                    }
                },complete:function(response){
                   
                }
            });
            
        }
    </script> --}}
@endpush