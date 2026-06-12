@extends('layouts.video')
@section('content')

<!-- start -->
<div class="w-full h-full relative">
    
      <div class="w-32 lg:w-48 absolute right-0 z-40">
      <div class="flex flex-col justify-center items-center h-full my-24">
      <div class="overflow-y-auto" style="height: 600px;">
               <div class="bg-red-200 border border-white mx-1 mt-1 relative">
                 <div class="absolute bottom-0"><p class="bg-gray-100 px-1 text-gray-700 text-xs">Demouser-2</p></div>
                    <div class="w-20 h-20 lg:w-40 lg:h-40"><img src="{{url('images/user1.jpg')}}" class="w-full h-full"></div>
               </div>
               <div class="bg-red-200 border border-white mx-1 mt-1 relative">
                 <div class="absolute bottom-0"><p class="bg-gray-100 px-1 text-gray-700 text-xs">Demouser-3</p></div>
                    <div class="w-20 h-20 lg:w-40 lg:h-40"><img src="{{url('images/user.jpeg')}}" class="w-full h-full"></div>
               </div>
               <div class="bg-red-200 border border-white mx-1 mt-1 relative">
                 <div class="absolute bottom-0"><p class="bg-gray-100 px-1 text-gray-700 text-xs">Demouser-4</p></div>
                    <div class="w-20 h-20 lg:w-40 lg:h-40"><img src="{{url('images/user1.jpg')}}" class="w-full h-full"></div>
               </div>
               <div class="bg-red-200 border border-white mx-1 mt-1 relative">
                 <div class="absolute bottom-0"><p class="bg-gray-100 px-1 text-gray-700 text-xs">Demouser-4</p></div>
                    <div class="w-20 h-20 lg:w-40 lg:h-40"><img src="{{url('images/user.jpeg')}}" class="w-full h-full"></div>
               </div>
               <div class="bg-red-200 border border-white mx-1 mt-1 relative">
                 <div class="absolute bottom-0"><p class="bg-gray-100 px-1 text-gray-700 text-xs">Demouser-4</p></div>
                    <div class="w-20 h-20 lg:w-40 lg:h-40"><img src="{{url('images/user1.jpg')}}" class="w-full h-full"></div>
               </div>
               <div class="bg-red-200 border border-white mx-1 mt-1 relative">
                 <div class="absolute bottom-0"><p class="bg-gray-100 px-1 text-gray-700 text-xs">Demouser-4</p></div>
                    <div class="w-20 h-20 lg:w-40 lg:h-40"><img src="{{url('images/user.jpeg')}}" class="w-full h-full"></div>
               </div>
               </div>
          </div>
     </div>  
      <div class="w-full">
      <div class="">
        <div class="bg-gray-400 relative">
          <div class="absolute"><p class="bg-gray-100 px-2 py-1 text-gray-700 text-xs">Demouser-1</p></div>
          <div class="w-full h-full" style="background-image: url('images/video_chat.jpg');background-repeat: no-repeat;background-position: center;background-size: cover;"></div>
         <!--  <div class="w-full h-full"><img src="{{url('images/video_chat.jpg')}}" class="w-full h-full"></div> -->
        </div>
      </div>
     </div>  
</div>
<!-- end -->
@endsection

