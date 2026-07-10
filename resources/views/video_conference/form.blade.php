@if ( auth()->user() === 5 )
    @extends('layouts.teacher.layout')
@elseif ( auth()->user() === 6 ) 
    @extends('layouts.student.layout')
@elseif ( auth()->user() === 3 )
     @extends('layouts.admin.layout') 
@endif
@section('content')
<div class="py-3">

<h2 class="text-lg my-2">Create Room</h2>
<form method="post" action="{{url('video-conference/save')}}" id="stream" enctype="multipart/form-data">
       @csrf

         <div class="my-3">
         <div class="">
          <div class="w-full lg:w-1/4">
            <label for="name" class="tw-form-label">Name</label>
            </div>
            <div class="w-full lg:w-2/5 my-2">
              <input type="text" name="name" id="name" class="tw-form-control w-full" value="{{old('name')}}">
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
               <textarea type="textarea" name="description" id="description" class="tw-form-control w-full">{{old('description')}}</textarea>
         <span class="text-danger text-xs">{{$errors->first('description')}}</span>
             </div>
    </div>
    </div>
    <div class="my-3">
         <div class="">
          <div class="w-full lg:w-1/4">
            <label for="description" class="tw-form-label">Select Students</label>
            </div>
            <div class="w-full lg:w-2/5 my-2">
              <select name="students[]" class="mdb-select md-form" multiple>
			  <option value="" selected>Choose students</option>
			  @if(count($student)>0)
			  @foreach($student as $userinfo)
			  <option value="{{$userinfo->id}}">{{$userinfo->name.' '.($userinfo->email)}}</option>
			  @endforeach
			  @endif
			</select>
         <span class="text-danger text-xs">{{$errors->first('students')}}</span>
             </div>
    </div>
    </div>
   <div class="mt-6 mb-4">
       <button class="btn btn-primary blue-bg text-white rounded px-3 py-1 text-sm font-medium" id="submit">Submit</button>
   </div>

</form>

</div>

@endsection

@push('scripts')

@endpush