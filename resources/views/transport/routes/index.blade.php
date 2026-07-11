@extends('layouts.accountant.layout')

@section('content')

 <!-- <div class="flex flex-wrap lg:flex-row justify-between items-center">
      <div class="">
        <h1 class="admin-h1 my-3">Route</h1>
      </div>
      <div class="relative flex items-center w-8/12 lg:w-1/4 md:w-1/4 justify-end">
        <div class="flex items-center">
          <a href="{{url('/transport/route/create')}}" class="no-underline text-white px-4 my-3 mx-1 flex items-center custom-green py-1 justify-center cursor-pointer">
            <span class="mx-1 text-sm font-semibold">Add </span>
            <svg data-v-2a22d6ae="" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 409.6 409.6" xml:space="preserve" class="w-3 h-3 fill-current text-white"><g data-v-2a22d6ae=""><g data-v-2a22d6ae=""><path data-v-2a22d6ae="" d="M392.533,187.733H221.867V17.067C221.867,7.641,214.226,0,204.8,0s-17.067,7.641-17.067,17.067v170.667H17.067 C7.641,187.733,0,195.374,0,204.8s7.641,17.067,17.067,17.067h170.667v170.667c0,9.426,7.641,17.067,17.067,17.067 s17.067-7.641,17.067-17.067V221.867h170.667c9.426,0,17.067-7.641,17.067-17.067S401.959,187.733,392.533,187.733z"></path></g></g></svg>
          </a> 
        </div>
      </div>
    </div>
<div class="">
      <div class="flex flex-wrap custom-table my-3 overflow-auto">
        <table class="w-full">
          <thead class="bg-grey-light">
            <tr class="border-b">
              
              <th class="text-left text-sm px-2 py-2 text-grey-darker">Route</th>
               <th class="text-left text-sm px-2 py-2 text-grey-darker" width="40%">
               <div class="flex items-center justify-between">
                 <p class="w-1/2"> Stop Name </p>
                 <p class="w-1/2"> Pickup Time</p>
                  <p class="w-1/2"> Drop time</p>
               </div>
               </th>
                <th class="text-left text-sm px-2 py-2 text-grey-darker">Vehicle</th>
              
               <th class="text-left text-sm px-2 py-2 text-grey-darker text-center" width="5%">Status</th>
               <th class="text-left text-sm px-2 py-2 text-grey-darker " width="7%">Actions</th>
            
            </tr>
          </thead>   
          <tbody >
          @foreach($routes as $route)
            <tr class="border-b"  >
            
              <td class="py-3 px-2" >
                  <p class="font-semibold text-xs">{{$route->name}}</p>
              </td>
                <td class="px-0 py-0" >
                       
                          <table class="w-full subject-table">
                          	 @foreach($route->routestoppages as $stop)
                               <tr>
                                <td width="30%"> {{ $stop->stop->name }}  </td>
                                 <td width="30%"> {{ date('g:i a', strtotime($stop->pickup_time)) }}  </td>
                                 <td width="30%"> {{ date('g:i a', strtotime($stop->drop_time)) }}</td>
                              </tr>
                              @endforeach
                           </table>
                     </td>
              <td class="py-3 px-2" >
                  <p class="font-semibold text-xs">{{$route->vehiclecount()}}</p>
              </td>
             <td class="py-3 px-2" >
             	@if($route->status==0)
              <p class="font-semibold text-xs">Inactive</p>
              @else
                 <p class="font-semibold text-xs">Active</p>
                 @endif
               </td>
              <td class="py-3 px-2">
                <div class="flex items-center">                
                  <a href="{{url('/transport/route/'.$route->id.'/edit')}}" class="cursor-pointer" title="Edit">
                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 477.873 477.873" xml:space="preserve" class="w-4 h-4 fill-current text-gray-500 mx-1"><g><g><path d="M392.533,238.937c-9.426,0-17.067,7.641-17.067,17.067V426.67c0,9.426-7.641,17.067-17.067,17.067H51.2 c-9.426,0-17.067-7.641-17.067-17.067V85.337c0-9.426,7.641-17.067,17.067-17.067H256c9.426,0,17.067-7.641,17.067-17.067 S265.426,34.137,256,34.137H51.2C22.923,34.137,0,57.06,0,85.337V426.67c0,28.277,22.923,51.2,51.2,51.2h307.2 c28.277,0,51.2-22.923,51.2-51.2V256.003C409.6,246.578,401.959,238.937,392.533,238.937z"></path></g></g> <g><g><path d="M458.742,19.142c-12.254-12.256-28.875-19.14-46.206-19.138c-17.341-0.05-33.979,6.846-46.199,19.149L141.534,243.937 c-1.865,1.879-3.272,4.163-4.113,6.673l-34.133,102.4c-2.979,8.943,1.856,18.607,10.799,21.585 c1.735,0.578,3.552,0.873,5.38,0.875c1.832-0.003,3.653-0.297,5.393-0.87l102.4-34.133c2.515-0.84,4.8-2.254,6.673-4.13 l224.802-224.802C484.25,86.023,484.253,44.657,458.742,19.142z M434.603,87.419L212.736,309.286l-66.287,22.135l22.067-66.202 L390.468,43.353c12.202-12.178,31.967-12.158,44.145,0.044c5.817,5.829,9.095,13.72,9.12,21.955 C443.754,73.631,440.467,81.575,434.603,87.419z"></path></g></g></svg>
                  </a>

                  	<a href="#" rel="{{url('/transport/route/'.$route->id.'/delete')}}"   class="cursor-pointer delete" title="Delete">
                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" xml:space="preserve" class="w-4 h-4 fill-current text-gray-500 mx-1"><g><g><g><polygon points="353.574,176.526 313.496,175.056 304.807,412.34 344.885,413.804"></polygon><rect x="235.948" y="175.791" width="40.104" height="237.285"></rect><polygon points="207.186,412.334 198.497,175.049 158.419,176.52 167.109,413.804"></polygon> <path d="M17.379,76.867v40.104h41.789L92.32,493.706C93.229,504.059,101.899,512,112.292,512h286.74 c10.394,0,19.07-7.947,19.972-18.301l33.153-376.728h42.464V76.867H17.379z M380.665,471.896H130.654L99.426,116.971h312.474 L380.665,471.896z"></path></g></g></g> <g><g><path d="M321.504,0H190.496c-18.428,0-33.42,14.992-33.42,33.42v63.499h40.104V40.104h117.64v56.815h40.104V33.42 C354.924,14.992,339.932,0,321.504,0z"></path></g></g></svg>
                  </a>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
          @if(count($routes)<=0)
          <tbody >
            <tr class="border-b">
              <td colspan="9">
                <p class="font-semibold text-s" style="text-align: center">No Records Found</p>
              </td>
            </tr>
          </tbody>
          @endif
        </table>
      </div>
    </div> -->
    <route-list url="{{ url('/') }}" ></route-list>
 
 @endsection
  <!-- @push('scripts')

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript">


   $(document).ready(function(){
      $('.delete').on('click', function(){
         var link = $(this).attr('rel');
         swal({
            icon: "info",
            text: "Do you want to delete this Route ?",
            buttons: {
               cancel: true,
               confirm: true,
            },
            allowOutsideClick: false,
         }).then((willChange) => {
            if (willChange) 
            {
               $.ajax({
                  url: link,
                  type: "delete",
                  headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                  success:function(data)
                  {
                     swal({
                        icon: "success",
                        text: "Route Deleted Successfully",
                     }).then(function(){
                        window.location.assign('/transport/route');
                     });
                  }
               })
            }
            else 
            {
               swal("Cancelled");
            } 
         });
      });
   });
</script>

@endpush  -->