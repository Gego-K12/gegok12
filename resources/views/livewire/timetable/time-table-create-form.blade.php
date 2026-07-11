<div>
    <div>


    

                <div class=" px-5"> 
             
            
             <div class="py-3">          
                 <label class="text-xs text-gray-600 font-medium">Subject -Teacher </label>

                 <div class="px-5 pb-3 my-3">
                   <!--  <ul class="list-reset grid grid-cols-1 lg:grid-cols-2 md:grid-cols-2 gap-1"> -->
                    <!-- <select name="subject">
                        <option value="0">Select subject</option> -->
                        @php
                        for($i=0;$i<count($subject_array);$i++){

                         @endphp

            {{-- <option value="{{$i}}" wire:change="hr_assign_time('{{$subject_array[$i]['teacherlink_id']}}','{{$subject_array[$i]['teacher_id']}}','{{$standardlink_id}}','{{$subject_array[$i]['no_of_periods']}}','{{$subject_array[$i]['subject_id']}}','{{$subject_array[$i]['subject_type']}}','{{$table_id}}')">Add {{$subject_array[$i]['subject_name']}} - {{$subject_array[$i]['teacher_name']}} </option> --}}

            <!-- <button class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md transition bg-yellow rounded-full px-14 py-2 mt-5 text-black font-medium  relative hover-filled-btn"><div wire:loading wire:target="addChild" class="btn-loading"></div> Save</button> -->

                         <button type="button" class="bg-blue-500 hover:bg-blue-400 rounded px-8 py-2 mb-2 text-white text-sm tracking-wider mr-3 relative" wire:click.prevent="hr_assign_time('{{$subject_array[$i]['teacherlink_id']}}','{{$subject_array[$i]['teacher_id']}}','{{$standardlink_id}}','{{$subject_array[$i]['no_of_periods']}}','{{$subject_array[$i]['subject_id']}}','{{$subject_array[$i]['subject_type']}}','{{$table_id}}')">
                            <!-- <div wire:loading wire:target="hr_assign_time" class="btn-loading"></div> -->
                        Add {{$subject_array[$i]['subject_name']}} - {{$subject_array[$i]['teacher_name']}}</button> <br/>
                        

                       @php
                        }
                         @endphp
                     </select>
                        

                    <!-- </ul>  -->
                 </div>
             </div>
             
             
             
          

             </div>

            
            
            
   </div>
</div>


