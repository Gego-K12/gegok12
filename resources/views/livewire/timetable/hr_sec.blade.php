
                            <tbody class="bg-grey-light" >
                                   
  <div wire:loading wire:target="delete">
   Action Processing
 </div>
  <div wire:loading wire:target="hr_assign_time">
   Action Processing
 </div>

 <div wire:loading wire:target="hr_assign_time">
   Action Processing
 </div>

                                @foreach($standardLink->ParingSubject as $subject)
                            
                                    <tr>
                                        <td>

                                            @if(count($subject)<=1)
                                      
                                                @if($subject->remaining_periods != 0)
                                                <button class="text-green-400" wire:click.prevent="hr_assign_time('{{$subject->id}}','{{$subject->teacher_id}}','{{$standardLink->id}}','{{$subject->remaining_periods}}','{{$subject->subject_id}}','{{$subject->subject_type}}')">Add</button>
                                                 @endif
                                                @else
                                               

                                @if($subject[0]->remaining_periods != 0)
                                          <?php 
                                          if($subject[0]->subject_type != null)
                                          {
                                            $subject_type=$subject[0]->subject_type;
                                          }
                                          else
                                          {
                                            $subject_type=$subject[0][0]->subject_type;
                                          }
                                                    
                                          ?>
                               
                    <button class="text-green-400" wire:click.prevent="hr_assign_time('{{$subject[0]->id}}','{{$subject[0]->teacher_id}}','{{$standardLink->id}}','{{$subject[0]->remaining_periods}}','{{$subject[0]->subject_id}}','{{$subject_type}}')">Add</button>
                                                  @endif
                                            @endif

                                           
                                            <br>
                            @if(count($subject)<=1)
            
        @if($subject->remaining_periods != $subject->no_of_periods)
        <button class="text-red-400" wire:click.prevent="deletehr('{{$subject->teacher_id}}','{{$standardLink->id}}','{{$subject->no_of_periods}}','{{$subject->subject_id}}','{{$subject[0]->subject_type}}')">Delete</button>
                                                    @endif
                                            @else
@if($subject[0]->remaining_periods != $subject[0]->no_of_periods)
    <button class="text-red-400" wire:click.prevent="deletehr('{{$subject[0]->teacher_id}}','{{$standardLink->id}}','{{$subject[0]->no_of_periods}}','{{$subject[0]->subject_id}}')">Delete</button>
    @endif
                        
        @endif
         </td>
            @if(count($subject)<2)

                                        <td>
                                            <a href="{{ url('/admin/teacher/show/'.$subject->teacher->name) }}">
                                            {{$subject->teacher->FullName}}</a>
                                        </td>
                                        <td>
                                            {{ $subject->subject->name }}
                                        </td>

                                        <td>
                                            {{ $subject->remaining_periods }}
                                        </td>

                                        <td>
                                            {{ $subject->no_of_periods }}
                                        </td>

                                        @else

                                        <td>@foreach($subject as $sub)
                                            <a href="{{ url('/admin/teacher/show/'.$sub->teacher->name) }}">
                                        {{$sub->teacher->FullName}}</a>
                                        @endforeach
                                            </td>
                                    
                                         <td>@foreach($subject as $sub)
                                           <p>{{ $sub->subject->name }}</p>
                                            @endforeach
                                        </td>

                                        <td>@foreach($subject as $sub)
                                           <p> {{ $sub->remaining_periods }}</p>
                                           @break
                                        @endforeach
                                        </td>

                                        <td>@foreach($subject as $sub)
                                            <p>{{ $sub->no_of_periods }}</p>
                                            @break
                                        @endforeach</td>
                                        
                                        @endif

                                    </tr>
                                     

                                @endforeach


                                <tr>
                                    @php

          $counttime_table=App\Models\TempTimetable::where('standardLink_id',$standardLink->id)->count();


          @endphp

                                @if($counttime_table==0)
                                    <button wire:click.prevent="TimeTable('{{$standardLink->id}}')" class="bg-green-500 px-2 text-white py-2">Create</button>
                                @endif 

                                <button wire:click.prevent="alldelete('{{$standardLink->id}}')" class="bg-red-600 px-2 py-2 text-white mx-2">Delete</button>

                                </tr>

                            </tbody>