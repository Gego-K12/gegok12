<div>
                            <tbody class="bg-grey-light"  wire:loading.remove>
                                   <div wire:loading wire:target="assign">
    Processing
 </div>
  <div wire:loading wire:target="delete">
   Action Processing
 </div>

<div wire:loading wire:target="alldelete">
   Action Processing
 </div>
 <div wire:loading wire:target="AllTimeTable">
   Time table create....
 </div>
            
                                @foreach($standardLink->teacherlink as $link)
                                    <tr>
                                        <td>

                                            @if($link->remaining_periods != 0)
                                                <button class="text-green-400" wire:click.prevent="assign_time('{{$link->id}}','{{$link->teacher_id}}','{{$standardLink->id}}','{{$link->remaining_periods}}','{{$link->subject_id}}','')">Add</button>
                                            @endif
                                            <br>
                                            @if($link->remaining_periods != $link->no_of_periods)
                                              
                                                    <button class="text-red-400 py-2" wire:click.prevent="delete('{{$link->teacher_id}}','{{$standardLink->id}}','{{$link->no_of_periods}}','{{$link->subject_id}}')">Delete

                                                       
                                                    </button>
                                              
                                            @endif
                                        </td>
                                        <td><a href="{{ url('/admin/teacher/show/'.$link->teacher->name) }}">
   {{$link->teacher->FullName}}</a></td>
                                        <td>{{ $link->subject->name }}</td>
                                        <td>{{ $link->remaining_periods }}</td>
                                        <td>{{ $link->no_of_periods }}</td>
                                    </tr>
                                @endforeach

                                <tr>

                                    <!-- <div class=" flex items-center justify-center "> -->
            <!-- <button type="button" class="inline-flex items-center px-4 py-2 bg-green-500 border border-transparent rounded-md transition bg-yellow rounded-full px-14 py-2 mt-5 text-black font-medium  relative hover-filled-btn" wire:click="AllTimeTable('{{$standardLink->id}}')" ><div wire:loading wire:target="AllTimeTable" class="btn-loading"></div> Create</button> -->

            <!--  w-full lg:w-40 md:w-40 text-center py-2 lg:py-4 md:py-4 px-4 lg:px-10 md:px-10 rounded-lg btn-double bg-purple text-white text-sm shadow-lg relative  -->
          <!-- </div> -->

          @php

          $counttime_table=\Gegok12\Timetable\Models\TempTimetable::where('standardLink_id',$standardLink->id)->count();


          @endphp

                                @if($counttime_table==0)
                                    <button type="button" wire:click.prevent="AllTimeTable('{{$standardLink->id}}')" class="bg-green-500 px-2 text-white py-2">Create


                                </button> 
                                @endif

                                <button  wire:click.prevent="alldelete('{{$standardLink->id}}')" class="bg-red-600 px-2 py-2 text-white mx-2">Delete

                                </button>

                                

                                </tr>

                            </tbody>
                        </div>