<div class="relative">
   <div class="flex flex-row justify-between custom-table">
      <table class="w-full">
       <!--   <caption><h1 class="admin-h1 mb-6">Exam Schedule Details</h1></caption> -->
         <thead class="bg-grey-light">
            <tr class="border-t-2 border-b-2">
               <th class="text-left text-sm px-2 py-2 text-grey-darker">Exam</th>
               <th class="text-left text-sm px-2 py-2 text-grey-darker">Class</th>
               <th class="text-left text-sm px-2 py-2 text-grey-darker">Subject</th>
               <th class="text-left text-sm px-2 py-2 text-grey-darker">Date Of Exam</th>
               <th class="text-left text-sm px-2 py-2 text-grey-darker">Total Marks</th>
               <th colspan="3">Actions</th>
            </tr>
         </thead>
         @if(count($schedule) != 0)
            <tbody class="bg-grey-light">
               @foreach($schedule as $schedules)
                  <tr class="border-t-2 border-b-2">    
                     <td class="py-3 px-2">{{ $schedules->exam->name }}</td>
                     <td class="py-3 px-2">{{ $schedules->standardlink->standard->name}} - {{$schedules->standardlink->section->name}}</td>
                     <td class="py-3 px-2">{{ $schedules->subject->name}}</td>
                     <td class="py-3 px-2">{{ $schedules->start_time }}</td>
                     <td class="py-3 px-2">{{ $schedules->exam->total_marks }}</td>

                  <!--     <td class="py-3 px-2">
                        <a href="{{url('/admin/examschedule/edit/'.$schedules->id)}}" class="capitalize rounded  font-medium"><img src="{{url('/uploads/icons/actions/view.svg')}}" class="w-4 h-4 mx-2"></a>
                     </td> -->
                     <td class="py-3 px-2">
                        <a href="#" rel="{{url('/admin/examschedule/delete/'.$schedules->id)}}" class="capitalize rounded  font-medium delete"><img src="{{asset('/uploads/icons/actions/trash.svg')}}" class="w-4 h-4 mx-2"></a>
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
            text: "Do you want to delete this Exam Schedule ?",
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
                        text: "Exam Schedule Deleted Successfully",
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