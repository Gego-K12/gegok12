<div class="relative">
   <div class="flex flex-row justify-between custom-table overflow-auto">
      <table class="w-full">
         <caption><h1 class="admin-h1 mb-6">Exam Details</h1></caption>
         <thead class="bg-grey-light">
            <tr class="border-t-2 border-b-2">
               <th class="text-left text-sm px-2 py-2 text-grey-darker">Name</th>
               <th class="text-left text-sm px-2 py-2 text-grey-darker">Class</th>
               <th class="text-left text-sm px-2 py-2 text-grey-darker">Total Marks</th>
               <th width="10%">Actions</th>
            </tr>
         </thead>
         @if(count($exam) != 0)
            <tbody class="bg-grey-light">
               @foreach($exam as $exams)
                  <tr class="border-t-2 border-b-2">    
                     <td class="py-3 px-2">{{ $exams->name }}</td>
                     <td class="py-3 px-2">{{  $exams->standardLink->standard->present()->integerToRoman($exams->standardLink->standard->name)}} - {{$exams->standardlink->section->name}}</td>
                     <td class="py-3 px-2">{{ $exams->total_marks }}</td>
                     
                      <td class="py-3 px-2">
                      <div class="flex items-center">
                        <a href="{{url('/admin/exam/edit/'.$exams->id)}}" class="capitalize rounded  font-medium"><img src="{{asset('/uploads/icons/actions/view.svg')}}" class="w-4 h-4 mx-2"></a>
                     
                        <a href="#" rel="{{url('/admin/exam/delete/'.$exams->id)}}" class="capitalize rounded  font-medium delete"><img src="{{asset('/uploads/icons/actions/trash.svg')}}" class="w-4 h-4 mx-2"></a>
                     
                        <a title="Add schedule" href="{{url('/admin/examschedule/add/'.$exams->id)}}" class="capitalize rounded font-medium"><img src="{{asset('/uploads/icons/add-gallery.svg')}}" class="w-4 h-4 mx-2"></a>
                        </div>
                     </td>
                    
                
                  </tr>

                 
               @endforeach
            </tbody>
         @else
            <tbody class="bg-grey-light">
               <tr class="border-t-2 border-b-2">    
                  <td colspan="5" class="py-3 px-2"><p class="font-semibold text-s" style="text-align: center">No Records Found</p></td>
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
            text: "Do you want to delete this Exam ?",
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
                  type: "GET",
                  headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                  success:function(data)
                  {
                     swal({
                        icon: "success",
                        text: "Exam Deleted Successfully",
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