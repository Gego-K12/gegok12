@extends('layouts.video')
@section('content')

<!-- start -->
<div class="">
     <div class="video-collaboration-wrapper h-full">
     	<div class="">
     		<div class="bg-gray-300 border border-white relative h-full">
     		  <div class="absolute"><p class="bg-gray-100 px-2 py-1 text-gray-700 text-xs">Demouser-1</p></div>
     			<div class="video-collaboration w-full h-full" style="background-image: url('images/video_chat.jpg');background-repeat: no-repeat;background-position: center;background-size: cover;"></div>
     		

               <!-- start -->
                 <div class="absolute z-40 w-full" style="bottom: 65px;">
      <div class="flex justify-center my-2 w-11/12 lg:w-1/2 overflow-x-auto mx-auto">
     
               <div class="bg-red-200 border border-white mx-1 my-1 relative">
                 <div class="absolute bottom-0"><p class="bg-gray-100 px-1 text-gray-700 text-xs">Demouser-2</p></div>
                    <div class="w-20 h-20 lg:w-32 lg:h-32"><img src="{{url('images/user.jpeg')}}" class="w-full h-full"></div>
               </div>
               <div class="bg-red-200 border border-white mx-1 my-1 relative">
                 <div class="absolute bottom-0"><p class="bg-gray-100 px-1 text-gray-700 text-xs">Demouser-3</p></div>
                    <div class="w-20 h-20 lg:w-32 lg:h-32"><img src="{{url('images/user1.jpg')}}" class="w-full h-full"></div>
               </div>
               <div class="bg-red-200 border border-white mx-1 my-1 relative">
                 <div class="absolute bottom-0"><p class="bg-gray-100 px-1 text-gray-700 text-xs">Demouser-4</p></div>
                    <div class="w-20 h-20 lg:w-32 lg:h-32"><img src="{{url('images/user.jpeg')}}" class="w-full h-full"></div>
               </div>
                <div class="bg-red-200 border border-white mx-1 my-1 relative">
                 <div class="absolute bottom-0"><p class="bg-gray-100 px-1 text-gray-700 text-xs">Demouser-5</p></div>
                    <div class="w-20 h-20 lg:w-32 lg:h-32"><img src="{{url('images/user1.jpg')}}" class="w-full h-full"></div>
               </div>
          </div>
     </div>  
               <!-- end -->
               </div>
     	</div>
     </div>  
    
</div>
<!-- end -->
@endsection

