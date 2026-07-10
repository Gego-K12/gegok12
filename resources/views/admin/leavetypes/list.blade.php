<div class="relative">
   <div class="flex flex-row justify-between custom-table  overflow-x-auto">
      <table class="w-full">
         <thead class="bg-grey-light">
            <tr class="border-t-2 border-b-2">
               <th class="text-left text-sm px-2 py-2 text-grey-darker">Name</th>
               <th class="text-left text-sm px-2 py-2 text-grey-darker">Max No. Of Days</th>
               <th class="text-left text-sm px-2 py-2 text-grey-darker text-center">Status</th>
               <th class="text-left text-sm px-2 py-2 text-grey-darker" width="14%">Actions</th>
            </tr>
         </thead>
         @if(count($leavetypes) > 0)
            <tbody class="bg-grey-light">
               @foreach($leavetypes as $leavetype)
                  <tr class="border-t-2 border-b-1">    
                     <td class="py-3 px-2">{{ $leavetype->name }}</td>
                     <td class="py-3 px-2">{{ $leavetype->max_no_of_days }} Days</td>
                     <td class="py-3 px-2">
                        @if($leavetype->status == 1)
                        <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" xml:space="preserve" class="w-5 h-5 fill-current text-green-600 mx-auto"><g><g><path d="M383.841,171.838c-7.881-8.31-21.02-8.676-29.343-0.775L221.987,296.732l-63.204-64.893
         c-8.005-8.213-21.13-8.393-29.35-0.387c-8.213,7.998-8.386,21.137-0.388,29.35l77.492,79.561
         c4.061,4.172,9.458,6.275,14.869,6.275c5.134,0,10.268-1.896,14.288-5.694l147.373-139.762
         C391.383,193.294,391.735,180.155,383.841,171.838z"></path></g></g> <g><g><path d="M256,0C114.84,0,0,114.84,0,256s114.84,256,256,256s256-114.84,256-256S397.16,0,256,0z M256,470.487
         c-118.265,0-214.487-96.214-214.487-214.487c0-118.265,96.221-214.487,214.487-214.487c118.272,0,214.487,96.221,214.487,214.487
         C470.487,374.272,374.272,470.487,256,470.487z"></path></g></g></svg>
         @else
         <svg height="512pt" viewBox="0 0 512 512" width="512pt" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mx-auto text-red-600 fill-current"><path d="m256 512c-141.160156 0-256-114.839844-256-256s114.839844-256 256-256 256 114.839844 256 256-114.839844 256-256 256zm0-475.429688c-120.992188 0-219.429688 98.4375-219.429688 219.429688s98.4375 219.429688 219.429688 219.429688 219.429688-98.4375 219.429688-219.429688-98.4375-219.429688-219.429688-219.429688zm0 0"></path><path d="m347.429688 365.714844c-4.679688 0-9.359376-1.785156-12.929688-5.359375l-182.855469-182.855469c-7.144531-7.144531-7.144531-18.714844 0-25.855469 7.140625-7.140625 18.714844-7.144531 25.855469 0l182.855469 182.855469c7.144531 7.144531 7.144531 18.714844 0 25.855469-3.570313 3.574219-8.246094 5.359375-12.925781 5.359375zm0 0"></path><path d="m164.570312 365.714844c-4.679687 0-9.355468-1.785156-12.925781-5.359375-7.144531-7.140625-7.144531-18.714844 0-25.855469l182.855469-182.855469c7.144531-7.144531 18.714844-7.144531 25.855469 0 7.140625 7.140625 7.144531 18.714844 0 25.855469l-182.855469 182.855469c-3.570312 3.574219-8.25 5.359375-12.929688 5.359375zm0 0"></path></svg>
         @endif


                     </td>
                     <td class="py-3 px-2">
                     <div class=" flex items-center">
                        <a href="{{ url('/admin/leavetype/edit/'.$leavetype->id) }}" class="">
                          <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 477.873 477.873" xml:space="preserve" class="w-4 h-4 fill-current text-black-500 mx-2"><g><g><path d="M392.533,238.937c-9.426,0-17.067,7.641-17.067,17.067V426.67c0,9.426-7.641,17.067-17.067,17.067H51.2
      c-9.426,0-17.067-7.641-17.067-17.067V85.337c0-9.426,7.641-17.067,17.067-17.067H256c9.426,0,17.067-7.641,17.067-17.067
      S265.426,34.137,256,34.137H51.2C22.923,34.137,0,57.06,0,85.337V426.67c0,28.277,22.923,51.2,51.2,51.2h307.2
      c28.277,0,51.2-22.923,51.2-51.2V256.003C409.6,246.578,401.959,238.937,392.533,238.937z"></path></g></g> <g><g><path d="M458.742,19.142c-12.254-12.256-28.875-19.14-46.206-19.138c-17.341-0.05-33.979,6.846-46.199,19.149L141.534,243.937
      c-1.865,1.879-3.272,4.163-4.113,6.673l-34.133,102.4c-2.979,8.943,1.856,18.607,10.799,21.585
      c1.735,0.578,3.552,0.873,5.38,0.875c1.832-0.003,3.653-0.297,5.393-0.87l102.4-34.133c2.515-0.84,4.8-2.254,6.673-4.13
      l224.802-224.802C484.25,86.023,484.253,44.657,458.742,19.142z M434.603,87.419L212.736,309.286l-66.287,22.135l22.067-66.202
      L390.468,43.353c12.202-12.178,31.967-12.158,44.145,0.044c5.817,5.829,9.095,13.72,9.12,21.955
      C443.754,73.631,440.467,81.575,434.603,87.419z"></path></g></g></svg>
                        </a>
                     
                        <a href="#" rel="{{ url('/admin/leavetype/delete/'.$leavetype->id) }}" class="delete">
                           <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" xml:space="preserve" class="w-4 h-4 fill-current text-black-500 mx-2"><g><g><g><polygon points="353.574,176.526 313.496,175.056 304.807,412.34 344.885,413.804       "></polygon> <rect x="235.948" y="175.791" width="40.104" height="237.285"></rect> <polygon points="207.186,412.334 198.497,175.049 158.419,176.52 167.109,413.804       "></polygon> <path d="M17.379,76.867v40.104h41.789L92.32,493.706C93.229,504.059,101.899,512,112.292,512h286.74
        c10.394,0,19.07-7.947,19.972-18.301l33.153-376.728h42.464V76.867H17.379z M380.665,471.896H130.654L99.426,116.971h312.474
        L380.665,471.896z"></path></g></g></g> <g><g><path d="M321.504,0H190.496c-18.428,0-33.42,14.992-33.42,33.42v63.499h40.104V40.104h117.64v56.815h40.104V33.42
      C354.924,14.992,339.932,0,321.504,0z"></path></g></g></svg>
                        </a>
                        </div>
                     </td>
                  </tr>
               @endforeach
            </tbody>
         @else
            <tbody class="bg-grey-light">
               <tr class="border-t-2 border-b-2">    
                  <td colspan="4" class="py-3 px-2"><p class="font-semibold text-sm" style="text-align: center">No Records Found</p></td>
               </tr>
            </tbody>
         @endif
      </table>      
   </div>
</div>

@push('scripts')

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript">

   $(document).ready(function(){
      $('.delete').on('click', function(){
         var link = $(this).attr('rel');
         swal({
            icon: "info",
            text: "Do you want to delete this Leave Type ?",
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
                  type: "DELETE",
                  headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                  success:function(data)
                  {
                     swal({
                        icon: "success",
                        text: "Leave type Deleted Successfully",
                     }).then(function(){
                        window.location.reload();
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

@endpush 