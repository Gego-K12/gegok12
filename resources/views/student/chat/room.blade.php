@extends('layouts.student.layout')

@section('content')
<div class="container"> 

   <h1 class="admin-h1 my-3 flex items-center">
      <a href="{{ url('/student/chat') }}" title="Back" class="rounded-full bg-gray-100 p-2">
          <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 492 492" xml:space="preserve" width="512px" height="512px" class="w-3 h-3 fill-current text-gray-700"><g><g><g><path d="M464.344,207.418l0.768,0.168H135.888l103.496-103.724c5.068-5.064,7.848-11.924,7.848-19.124    c0-7.2-2.78-14.012-7.848-19.088L223.28,49.538c-5.064-5.064-11.812-7.864-19.008-7.864c-7.2,0-13.952,2.78-19.016,7.844    L7.844,226.914C2.76,231.998-0.02,238.77,0,245.974c-0.02,7.244,2.76,14.02,7.844,19.096l177.412,177.412    c5.064,5.06,11.812,7.844,19.016,7.844c7.196,0,13.944-2.788,19.008-7.844l16.104-16.112c5.068-5.056,7.848-11.808,7.848-19.008    c0-7.196-2.78-13.592-7.848-18.652L134.72,284.406h329.992c14.828,0,27.288-12.78,27.288-27.6v-22.788    C492,219.198,479.172,207.418,464.344,207.418z" data-original="#000000" fill="" class="active-path"></path></g></g></g></svg>
      </a>
      <span class="mx-3">{{$room->title}}</span>
    </h1>

    <div class="row">
        <div class="col-md-2">
     <livewire:chat.users :room="$room" />
        </div>

          <div class="col-md-10">
     <livewire:chat.messages :room="$room" :messages=$messages />
     <livewire:chat.new-message :room=$room />
        </div>
    </div>
</div>


  <div class="bg-white my-5 px-3 py-3">
  <h1 class="admin-h1 mb-3 flex items-center">Group Members ({{ $memberCount }})</h1>
  <div class="flex flex-wrap">
  @foreach($roomlinks as $roomlink)
    <div class="w-full lg:w-1/3 md:w-1/3 my-2 px-1">
        <div class="shadow-md">
          <div class="flex p-2">
            <img class="card-img-top w-16 h-16" src="{{ $roomlink->user->userprofile->AvatarPath }}">
            <div class="px-3 w-full">
            <div class="flex justify-between items-center">
              <p class="font-bold text-base text-gray-700 capitalize">
                {{-- <a href="/admin/member/show/{{ $roomlink->user->name }}">{{ $roomlink->user->name }}</a> --}}
                <a href="#">{{ $roomlink->user->name }}</a>
              </p>
              {{-- <div class="flex items-center">
              <a href="#" rel="{{url('/admin/room/removeMember/'.$roomlink->id)}}" id="remove_member" class="left-auto delete-member">
                <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512.001 512.001" xml:space="preserve" class="w-2 h-2 m-1 fill-current text-gray-700"><g><g><path d="M284.286,256.002L506.143,34.144c7.811-7.811,7.811-20.475,0-28.285c-7.811-7.81-20.475-7.811-28.285,0L256,227.717
      L34.143,5.859c-7.811-7.811-20.475-7.811-28.285,0c-7.81,7.811-7.811,20.475,0,28.285l221.857,221.857L5.858,477.859
      c-7.811,7.811-7.811,20.475,0,28.285c3.905,3.905,9.024,5.857,14.143,5.857c5.119,0,10.237-1.952,14.143-5.857L256,284.287
      l221.857,221.857c3.905,3.905,9.024,5.857,14.143,5.857s10.237-1.952,14.143-5.857c7.811-7.811,7.811-20.475,0-28.285
      L284.286,256.002z"></path></g></g></svg>
              </a>
              </div> --}}
              </div>
              <p class="font-bold text-base text-gray-700">
                <span class="font-medium text-sm text-gray-600 flex items-center">{{ $roomlink->user->email}}</span>
              </p>
              <p class="font-bold text-base text-gray-700 capitalize">
                <span class="font-medium text-sm text-gray-600 capitalize flex items-center">{{ $roomlink->user->usergroup->name }}</span>
              </p>  
            </div>
          </div>
        </div>
      </div>
  @endforeach
  </div>
   {{ $roomlinks->links() }}
  </div>
@endsection

