@extends('layouts.admin.layout')

@section('content')
   <!--  <div class="w-full">
        <div>
            <h1 class="admin-h1 my-3 flex items-center">
                <a href="{{ url('/admin/video-conference') }}" title="Back" class="rounded-full bg-gray-100 p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 492 492" xml:space="preserve" width="512px" height="512px" class="w-3 h-3 fill-current text-gray-700"><g><g><g><path d="M464.344,207.418l0.768,0.168H135.888l103.496-103.724c5.068-5.064,7.848-11.924,7.848-19.124 c0-7.2-2.78-14.012-7.848-19.088L223.28,49.538c-5.064-5.064-11.812-7.864-19.008-7.864c-7.2,0-13.952,2.78-19.016,7.844    L7.844,226.914C2.76,231.998-0.02,238.77,0,245.974c-0.02,7.244,2.76,14.02,7.844,19.096l177.412,177.412 c5.064,5.06,11.812,7.844,19.016,7.844c7.196,0,13.944-2.788,19.008-7.844l16.104-16.112c5.068-5.056,7.848-11.808,7.848-19.008 c0-7.196-2.78-13.592-7.848-18.652L134.72,284.406h329.992c14.828,0,27.288-12.78,27.288-27.6v-22.788    C492,219.198,479.172,207.418,464.344,207.418z" data-original="#000000" fill="" class="active-path"></path></g></g></g></svg>
                </a>
                <span class="mx-3">Edit Room</span>
            </h1>
        </div>
        <div class="bg-white shadow px-4 py-3">
            @include('partials.message')
            <form method="post" action="{{url('admin/video-conference/update/'.$conference->id)}}" id="stream" enctype="multipart/form-data">
                @csrf
                @if(old('name')!='')
                    @php $name = old('name'); @endphp
                @else
                    @php $name = $conference->name; @endphp
                @endif

                @if(old('description')!='')
                    @php $description = old('description'); @endphp
                @else
                    @php $description = $conference->description; @endphp
                @endif
                <div class="my-3">
                    <div class="">
                        <div class="w-full lg:w-1/4">
                            <label for="name" class="tw-form-label">Name</label>
                        </div>
                        <div class="w-full lg:w-2/5 my-2">
                            <input type="text" name="name" id="name" class="tw-form-control w-full" value="{{$name}}" readonly="">
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
                            <textarea type="textarea" name="description" id="description" class="tw-form-control w-full">{{$description}}</textarea>
                            <span class="text-danger text-xs">{{$errors->first('description')}}</span>
                        </div>
                    </div>
                </div>
                <div class="my-3">
                    <div class="">
                        <div class="w-full lg:w-1/4">
                            <label for="students" class="tw-form-label">Select Staff</label>
                        </div>
                        <div class="w-full lg:w-2/5 my-2">
                            <div class="border rounded">
                                <select name="students[]" class="mdb-select md-form w-full" multiple>
                                    <option value="">Choose staff</option>
                                    @if(count($student)>0)
                                        @foreach($student as $userinfo)
                                            @php $checkExist = ''; @endphp
                                            @if(in_array($userinfo->id, $multipleUsers))
                                                @php $checkExist = 'selected="selected"'; @endphp
                                            @endif
                                            <option value="{{$userinfo->id}}" {{$checkExist}}>{{ $userinfo->FullName.'  ( '.$userinfo->email.' )'}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <span class="text-danger text-xs">{{$errors->first('students.*')}}</span>
                        </div>
                    </div>
                </div>
                <div class="mt-6 mb-4">
                    <button class="btn btn-primary blue-bg text-white rounded px-3 py-1 text-sm font-medium" id="submit">Submit</button>
                </div>
            </form>
        </div>
    </div> -->
    <edit-conference url="{{ url('/') }}" date="{{ date('d-m-Y H:i:s') }}" conference_id="{{$conference->id}}"  ></edit-conference>
@endsection