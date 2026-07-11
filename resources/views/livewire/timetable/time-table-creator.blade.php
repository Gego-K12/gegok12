<div>
<!-- <form>
   @csrf -->
  <!-- <div wire:loading wire:target="load">
        Collecting selected classes data...
 </div> -->
 <!--  <div wire:loading wire:target="assign_time">
    Processing
 </div> -->
 <!--  <div wire:loading wire:target="delete">
   Action Delete Time Table Processing
 </div>

<div wire:loading wire:target="alldelete">
   All Delete Time Table Processing
 </div> -->
<!-- <div wire:loading wire:target="AllTimeTable">
   Time Table Create Processing...
 </div> -->

 

<!--  @if($this->wload==1)

 <div class="w-full">
         <div class="flex items-center justify-center py-16  mt-4 w-full relative">
            <div class="text-center page-loading">
                <div class="loading loader">
      <div><div></div></div>
      <div><div></div></div>
      <div><div></div></div>
      <div><div></div></div>
      <div><div></div></div>
      <div><div></div></div>
      <div><div></div></div>
      <div><div></div></div>
      <div><div></div></div>
      <div><div></div></div>
      <div><div></div></div>
      <div><div></div></div>
    </div>
             <h6 class="text-lg font-semibold pt-5">Just a moment</h6>
             <p class="text-base font-medium pt-1 text-gray-600">Time Table Loading... </p>
         </div>
     </div>
      
    </div>

@endif -->


 <div wire:loading  wire:target='alldelete,delete,deletehr' class="w-full">
         <div class="flex items-center justify-center py-16  mt-4 w-full relative">
            <div class="text-center page-loading">
                <div class="loading loader">
      <div><div></div></div>
      <div><div></div></div>
      <div><div></div></div>
      <div><div></div></div>
      <div><div></div></div>
      <div><div></div></div>
      <div><div></div></div>
      <div><div></div></div>
      <div><div></div></div>
      <div><div></div></div>
      <div><div></div></div>
      <div><div></div></div>
    </div>
             <h6 class="text-lg font-semibold pt-5">Just a moment</h6>
             <p class="text-base font-medium pt-1 text-gray-600">Delete Time Table ... </p>
         </div>
     </div>
       <!--  Loading the hotels ... -->
    </div>

<!-- <div wire:loading wire:target="load">
   Loading...
 </div> -->


   @if(session('successmsg'))
                    <div class="alert alert-success">
                        {{ session('successmsg') }}
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>

                        {{ session()->forget('successmsg') }}
                    </div>
     @endif

     <div wire:loading  wire:target='AllTimeTable,assign_time,load,TimeTable' class="w-full">
         <div class="flex items-center justify-center py-16  mt-4 w-full relative">
            <div class="text-center page-loading">
                <div class="loading loader">
      <div><div></div></div>
      <div><div></div></div>
      <div><div></div></div>
      <div><div></div></div>
      <div><div></div></div>
      <div><div></div></div>
      <div><div></div></div>
      <div><div></div></div>
      <div><div></div></div>
      <div><div></div></div>
      <div><div></div></div>
      <div><div></div></div>
    </div>
             <h6 class="text-lg font-semibold pt-5">Just a moment</h6>
             <p class="text-base font-medium pt-1 text-gray-600">Loading... </p>
         </div>
     </div>
       <!--  Loading the hotels ... -->
    </div>



     <div wire:loading  wire:target='AllTimeTable,assign_time' class="w-full">
         <div class="flex items-center justify-center py-16  mt-4 w-full relative">
            <div class="text-center page-loading">
                <div class="loading loader">
      <div><div></div></div>
      <div><div></div></div>
      <div><div></div></div>
      <div><div></div></div>
      <div><div></div></div>
      <div><div></div></div>
      <div><div></div></div>
      <div><div></div></div>
      <div><div></div></div>
      <div><div></div></div>
      <div><div></div></div>
      <div><div></div></div>
    </div>
             <h6 class="text-lg font-semibold pt-5">Just a moment</h6>
             <p class="text-base font-medium pt-1 text-gray-600">Time Table Creating... </p>
         </div>
     </div>
       <!--  Loading the hotels ... -->
    </div>


 

 
    <!-- <div wire:loading.delay.shortest>...</div> -->


    <div wire:loading wire:target='AllTimeTable' class="mb-2 lg:w-1/6 md:w-1/6">
                                        
                                            <div >
                            <div class="la-ball-spin-clockwise-fade-rotating">
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    </div>
                                            </div>
                                          

                                        

    </div>

    

    <div wire:loading.remove>
  <div class="mb-2 lg:w-1/6 md:w-1/6" >
              <select class="tw-form-control w-full" id="class_teacher_id" name="class_teacher_id"  wire:model="selected_class" wire:change="load($event.target.value)">
                <option value="kg" >KG's</option>
                <option value="1to5" >Class 1 to 5</option>
                <option value="6to10">Class 6 to 10</option>
                <option value="hr_sec">11 & 12</option>
             
              </select>
            </div>
    @if(count($standardLinks)>0)

        @foreach($standardLinks as $standardLink)
        <?php  
if($standardLink->standard_id== 14)
{

        //dd($standardLink->ParingSubject);
}
?>
            <div class="flex item-center justify-between">
            <h1 class="mx-2 my-1 font-semibold"><a href="{{ url('/admin/standardLink/show/'.$standardLink->id) }}">{{ $standardLink->StandardSection }}</a></h1>
            <h1 class="mx-2 my-1 font-semibold">Total periods:{{ $standardLink->teacherlink->sum('no_of_periods') }}</h1>
           </div>
            @if(count($standardLink->teacherlink)!=0)
            <div class="relative"> 
                <div class="flex flex-row justify-between custom-table">   
                    <div class="w-full lg:w-3/5 md:w-3/5 table-responsive m-2 overflow-x-auto">
                        @if($standardLink->TimeTableCount!=0)
                       
                        <table class="w-full custom-timetable">
                            <thead class="bg-grey-light">
                                <tr>
                                    <th width="100px">Days</th>
                                    @for($i = 1 ; $i <= 8 ; $i++)
                                        <th width="100px">{{$i}}</th>
                                    @endfor
                                </tr>
                            </thead>

                            <tbody class="bg-grey-light">
                                 @foreach($weekdays as $weekday)

                                    @if($weekday=='Monday')
      <tr>
        <td>{{$weekday}}</td>
        @php

        $Mondays=$standardLink->temp_timetable->where('day','Monday')->take(8);


        @endphp

         @if(count($Mondays)>0)

         @foreach($Mondays as $Monday)

        <td  class="" x-data="{showModalAdmission: false}">
         <!--<span class="font-semibold">{{$Monday[$m]->subject->name}} </span><br/>-->
          @if($Monday->subject_name!='')
         <span class="font-semibold">{{$Monday->subject_name}}</span><br/>
           <span class="text-gray-700">
            <a href="{{ url('/a
            dmin/teacher/show/'.$Monday->user->name) }}">{{$Monday->user->FullNAme}} </a></span>
        @else
            <div class="text-xs text-white bg-blue-500 px-2 py-1 rounded inline-block mx-1 cursor-pointer" x-on:click="showModalAdmission = {{$Monday->id }}">Free Add
                                   </div>


                                   <!-- remove modal start -->
         <div class="fixed top-0 left-0 flex items-center justify-center w-full h-full z-40" style="background-color: rgba(0,0,0,.3);display: none;" x-show="showModalAdmission == {{$Monday->id }}">

       
        <div class="h-auto  mx-2 text-left bg-white rounded shadow-xl w-11/12 lg:w-1/3 md:w-1/3  md:mx-0 modal-main" @click.away="showRemoveModal = false">
          <div class="">
            <div class="flex items-center justify-between p-3 md:px-5 lg:px-5 lg:py-3 md:py-3">
             <h6 class="text-xl font-semibold custom-title-font">Add TimeTable Slot</h6>
             <div class="cursor-pointer" x-on:click="showModalAdmission = false">
               <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
             </div>
            </div>

       
            <livewire:timetable.time-table-create-form :id="$Monday->id" :key="$Monday->id" :class="$selected_class"/>


        </div>
        </div>
      </div> 
        <!-- remove modal end -->
    
         @endif
        </td>
       
         @endforeach
      @else
         <td>Free</td>

        @endif
    
       </tr>
      @endif

    @if($weekday=='Tuesday')
      <tr>
        <td>{{$weekday}}</td>
        
        @php

        $Tuesdays=$standardLink->temp_timetable->where('day','Tuesday')->take(8);


        @endphp

         @if(count($Tuesdays)>0)

         @foreach($Tuesdays as $Tuesday)

        <td  class="" x-data="{showModalAdmission: false}">
         <!--<span class="font-semibold">{{$Monday[$m]->subject->name}} </span><br/>-->
          @if($Tuesday->subject_name!='')
         <span class="font-semibold">{{$Tuesday->subject_name}}</span><br/>
           <span class="text-gray-700">
            <a href="{{ url('/a
            dmin/teacher/show/'.$Tuesday->user->name) }}">{{$Tuesday->user->FullNAme}} </a></span>
        @else
            <div class="text-xs text-white bg-blue-500 px-2 py-1 rounded inline-block mx-1 cursor-pointer" x-on:click="showModalAdmission = {{$Tuesday->id }}">Free Add
                                   </div>


                                   <!-- remove modal start -->
         <div class="fixed top-0 left-0 flex items-center justify-center w-full h-full z-40" style="background-color: rgba(0,0,0,.3);display: none;" x-show="showModalAdmission == {{$Tuesday->id }}">

       
        <div class="h-auto  mx-2 text-left bg-white rounded shadow-xl w-11/12 lg:w-1/3 md:w-1/3  md:mx-0 modal-main" @click.away="showRemoveModal = false">
          <div class="">
            <div class="flex items-center justify-between p-3 md:px-5 lg:px-5 lg:py-3 md:py-3">
             <h6 class="text-xl font-semibold custom-title-font">Add TimeTable Slot</h6>
             <div class="cursor-pointer" x-on:click="showModalAdmission = false">
               <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
             </div>
            </div>

       
            <livewire:timetable.time-table-create-form :id="$Tuesday->id" :key="$Tuesday->id" :class="$selected_class"/>


        </div>
        </div>
      </div> 
        <!-- remove modal end -->
    
         @endif
        </td>
       
         @endforeach
      @else
         <td>Free</td>

        @endif
    
       </tr>
      @endif

       @if($weekday=='Wednesday')
      <tr x-data="{showModalAdmission: false}">
        <td>{{$weekday}}</td>

        @php

        $Wednesdays=$standardLink->temp_timetable->where('day','Wednesday')->take(8);

        @endphp
         @if(count($Wednesdays)>0)

         @foreach($Wednesdays as $Wednesday)

        <td  class="" x-data="{showModalAdmission: false}">
         <!--<span class="font-semibold">{{$Monday[$m]->subject->name}} </span><br/>-->
          @if($Wednesday->subject_name!='')
         <span class="font-semibold">{{$Wednesday->subject_name}}</span><br/>
           <span class="text-gray-700">
            <a href="{{ url('/a
            dmin/teacher/show/'.$Tuesday->user->name) }}">{{$Wednesday->user->FullNAme}} </a></span>
        @else
            <div class="text-xs text-white bg-blue-500 px-2 py-1 rounded inline-block mx-1 cursor-pointer" x-on:click="showModalAdmission = {{$Wednesday->id }}">Free Add
                                   </div>


                                   <!-- remove modal start -->
         <div class="fixed top-0 left-0 flex items-center justify-center w-full h-full z-40" style="background-color: rgba(0,0,0,.3);display: none;" x-show="showModalAdmission == {{$Wednesday->id }}">

       
        <div class="h-auto  mx-2 text-left bg-white rounded shadow-xl w-11/12 lg:w-1/3 md:w-1/3  md:mx-0 modal-main" @click.away="showRemoveModal = false">
          <div class="">
            <div class="flex items-center justify-between p-3 md:px-5 lg:px-5 lg:py-3 md:py-3">
             <h6 class="text-xl font-semibold custom-title-font">Add TimeTable Slot</h6>
             <div class="cursor-pointer" x-on:click="showModalAdmission = false">
               <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
             </div>
            </div>

       
            <livewire:timetable.time-table-create-form :id="$Wednesday->id" :key="$Wednesday->id" :class="$selected_class"/>


        </div>
        </div>
      </div> 
        <!-- remove modal end -->
    
         @endif
        </td>
       
         @endforeach
      @else
         <td>Free</td>

        @endif
    
       </tr>
      @endif
       @if($weekday=='Thursday')
      <tr>
         <td>{{$weekday}}</td>
       @php

        $Thursdays=$standardLink->temp_timetable->where('day','Thursday')->take(8);

        @endphp
         @if(count($Thursdays)>0)

         @foreach($Thursdays as $Thursday)

        <td  class="" x-data="{showModalAdmission: false}">
         <!--<span class="font-semibold">{{$Monday[$m]->subject->name}} </span><br/>-->
          @if($Thursday->subject_name!='')
         <span class="font-semibold">{{$Thursday->subject_name}}</span><br/>
           <span class="text-gray-700">
            <a href="{{ url('/a
            dmin/teacher/show/'.$Tuesday->user->name) }}">{{$Thursday->user->FullNAme}} </a></span>
        @else
            <div class="text-xs text-white bg-blue-500 px-2 py-1 rounded inline-block mx-1 cursor-pointer" x-on:click="showModalAdmission = {{$Thursday->id }}">Free Add
                                   </div>


                                   <!-- remove modal start -->
         <div class="fixed top-0 left-0 flex items-center justify-center w-full h-full z-40" style="background-color: rgba(0,0,0,.3);display: none;" x-show="showModalAdmission == {{$Thursday->id }}">

       
        <div class="h-auto  mx-2 text-left bg-white rounded shadow-xl w-11/12 lg:w-1/3 md:w-1/3  md:mx-0 modal-main" @click.away="showRemoveModal = false">
          <div class="">
            <div class="flex items-center justify-between p-3 md:px-5 lg:px-5 lg:py-3 md:py-3">
             <h6 class="text-xl font-semibold custom-title-font">Add TimeTable Slot</h6>
             <div class="cursor-pointer" x-on:click="showModalAdmission = false">
               <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
             </div>
            </div>

       
            <livewire:timetable.time-table-create-form :id="$Thursday->id" :key="$Thursday->id" :class="$selected_class"/>


        </div>
        </div>
      </div> 
        <!-- remove modal end -->
    
         @endif
        </td>
       
         @endforeach
      @else
         <td>Free</td>

        @endif
    
       </tr>
      @endif


       @if($weekday=='Friday')
      <tr>
        <td>{{$weekday}}</td>
        @php

        $Fridays=$standardLink->temp_timetable->where('day','Friday')->take(8);

        @endphp
         @if(count($Fridays)>0)

         @foreach($Fridays as $Friday)

        <td  class="" x-data="{showModalAdmission: false}">
         <!--<span class="font-semibold">{{$Monday[$m]->subject->name}} </span><br/>-->
          @if($Friday->subject_name!='')
         <span class="font-semibold">{{$Friday->subject_name}}</span><br/>
           <span class="text-gray-700">
            <a href="{{ url('/a
            dmin/teacher/show/'.$Tuesday->user->name) }}">{{$Friday->user->FullNAme}} </a></span>
        @else
            <div class="text-xs text-white bg-blue-500 px-2 py-1 rounded inline-block mx-1 cursor-pointer" x-on:click="showModalAdmission = {{$Friday->id }}">Free Add
                                   </div>


                                   <!-- remove modal start -->
         <div class="fixed top-0 left-0 flex items-center justify-center w-full h-full z-40" style="background-color: rgba(0,0,0,.3);display: none;" x-show="showModalAdmission == {{$Friday->id }}">

       
        <div class="h-auto  mx-2 text-left bg-white rounded shadow-xl w-11/12 lg:w-1/3 md:w-1/3  md:mx-0 modal-main" @click.away="showRemoveModal = false">
          <div class="">
            <div class="flex items-center justify-between p-3 md:px-5 lg:px-5 lg:py-3 md:py-3">
             <h6 class="text-xl font-semibold custom-title-font">Add TimeTable Slot</h6>
             <div class="cursor-pointer" x-on:click="showModalAdmission = false">
               <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
             </div>
            </div>

       
            <livewire:timetable.time-table-create-form :id="$Friday->id" :key="$Friday->id" :class="$selected_class"/>


        </div>
        </div>
      </div> 
        <!-- remove modal end -->
    
         @endif
        </td>
       
         @endforeach
      @else
         <td>Free</td>

        @endif
    
       </tr>
      @endif    
                                @endforeach
                            </tbody>
                        </table>
                       @else
        <label class="text-sm my-1">No classes are Assigned</label>
    @endif
                    </div>
                    <div class="w-full lg:w-2/5 md:w-2/5 m-2">
                        <table class="w-full">
                            <thead class="bg-grey-light">
                                <tr>
                                    <th>Action</th>
                                    <th>Teacher name</th>
                                    <th>Subject</th>
                                    <th>Remaining</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                           @if($selected_class=='hr_sec')
                             @include('livewire.timetable.hr_sec')
                             @else
                              @include('livewire.timetable.all_classes')
                           @endif

                        </table>
                    </div>
                </div>
            </div>
             @else
        <label>No data are available create timetable <a href="{{url('/admin/standardLink/add')}}">Click here to add a class</a></label>
            @endif
        @endforeach
    @else
        <label>No data are available create timetable <a href="{{url('/admin/standardLink/add')}}">Click here to <span class="text-white px-4 mx-1 custom-green py-1">Add a class</span></a></label>
    @endif
  </div>
<!-- </form> -->

</div>


 