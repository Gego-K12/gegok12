<div class="relative" id="printDiv"> 

 <form>
            @foreach($standardLinks as $standard)
                <div id="printDiv"> 
                    <h1 class="mx-2 my-1 font-semibold">{{ $standard->StandardSection }}</h1>
                     @if($standard->TimeTableCount!=0)
                    <div class="relative"> 
                        <div class="flex flex-row justify-between custom-table">   
                            <div class="w-full table-responsive m-2 overflow-x-auto">
                                <table class="w-full custom-timetable">
                                    <thead class="bg-grey-light">
                                        <tr>
                                            <th>Days</th>
                                            @for($i = 1 ; $i <= 8 ; $i++)
                                                <th>{{ $i }}</th>
                                            @endfor
                                        </tr>
                                    </thead>
                                   <tbody class="bg-grey-light">
                                 @foreach($weekdays as $weekday)

                                    @if($weekday=='Monday')
      <tr>
        <td>{{$weekday}}</td>
       

         @php

        $Mondays=$standard->temp_timetable->where('day','Monday')->take(8);


        @endphp

         @if(count($Mondays)>0)

         @foreach($Mondays as $Monday)

        <td>
         <!--<span class="font-semibold">{{$Monday[$m]->subject->name}} </span><br/>-->
          @if($Monday->subject_name!='')
         <span class="font-semibold">{{$Monday->subject_name}}</span><br/>
           <span class="text-gray-700">
            <a href="{{ url('/a
            dmin/teacher/show/'.$Monday->user->name) }}">{{$Monday->user->FullNAme}} </a></span>
            @else
            <div class="text-xs text-white bg-blue-500 px-2 py-1 rounded inline-block mx-1 cursor-pointer" >Free
                                   </div>
            @endif

          </td>

          @endforeach

          @else
          <td><span class="font-semibold">{{$Monday->subject_name}}</span><br/>
           <span class="text-gray-700">
            Free</span></td>

           @endif

       </tr>
      @endif

    @if($weekday=='Tuesday')
      <tr>
        <td>{{$weekday}}</td>
        @php

        $Tuesdays=$standard->temp_timetable->where('day','Tuesday')->take(8);


        @endphp

         @if(count($Tuesdays)>0)

         @foreach($Tuesdays as $Tuesday)

        <td>
         <!--<span class="font-semibold">{{$Monday[$m]->subject->name}} </span><br/>-->
          @if($Tuesday->subject_name!='')
         <span class="font-semibold">{{$Tuesday->subject_name}}</span><br/>
           <span class="text-gray-700">
            <a href="{{ url('/a
            dmin/teacher/show/'.$Tuesday->user->name) }}">{{$Tuesday->user->FullNAme}} </a></span>
            @else
            <div class="text-xs text-white bg-blue-500 px-2 py-1 rounded inline-block mx-1 cursor-pointer">Free
                                   </div>
            @endif

          </td>

          @endforeach

          @else
          <td><span class="font-semibold">{{$Tuesday->subject_name}}</span><br/>
           <span class="text-gray-700">
            Free</span></td>
           @endif

       </tr>
      @endif

       @if($weekday=='Wednesday')
      <tr>
        <td>{{$weekday}}</td>
       @php

        $Wednesdays=$standard->temp_timetable->where('day','Wednesday')->take(8);


        @endphp

         @if(count($Wednesdays)>0)

         @foreach($Wednesdays as $Wednesday)

        <td>
         <!--<span class="font-semibold">{{$Monday[$m]->subject->name}} </span><br/>-->
          @if($Wednesday->subject_name!='')
         <span class="font-semibold">{{$Wednesday->subject_name}}</span><br/>
           <span class="text-gray-700">
            <a href="{{ url('/a
            dmin/teacher/show/'.$Wednesday->user->name) }}">{{$Wednesday->user->FullNAme}} </a></span>
            @else
            <div class="text-xs text-white bg-blue-500 px-2 py-1 rounded inline-block mx-1 cursor-pointer" >Free
                                   </div>
            @endif

          </td>

          @endforeach

          @else
          <td>
           <span class="text-gray-700">
            Free</span></td>
           @endif

       </tr>
      @endif
       @if($weekday=='Thursday')
      <tr>
        <td>{{$weekday}}</td>
         @php

        $Thursdays=$standard->temp_timetable->where('day','Thursday')->take(8);

        @endphp

         @if(count($Thursdays)>0)

         @foreach($Thursdays as $Thursday)

        <td>
         <!--<span class="font-semibold">{{$Monday[$m]->subject->name}} </span><br/>-->
          @if($Thursday->subject_name!='')
         <span class="font-semibold">{{$Thursday->subject_name}}</span><br/>
           <span class="text-gray-700">
            <a href="{{ url('/a
            dmin/teacher/show/'.$Thursday->user->name) }}">{{$Thursday->user->FullNAme}} </a></span>
            @else
            <div class="text-xs text-white bg-blue-500 px-2 py-1 rounded inline-block mx-1 cursor-pointer">Free
                                   </div>
            @endif

          </td>

          @endforeach

          @else
          <td>
           <span class="text-gray-700">
            Free</span></td>
           @endif

       </tr>
      @endif
       @if($weekday=='Friday')
      <tr>
        <td>{{$weekday}}</td>
        @php

        $Fridays=$standard->temp_timetable->where('day','Friday')->take(8);


        @endphp

         @if(count($Fridays)>0)

         @foreach($Fridays as $Friday)
        <td>
         <!--<span class="font-semibold">{{$Monday[$m]->subject->name}} </span><br/>-->
          @if($Friday->subject_name!='')
         <span class="font-semibold">{{$Friday->subject_name}}</span><br/>
           <span class="text-gray-700">
            <a href="{{ url('/a
            dmin/teacher/show/'.$Friday->user->name) }}">{{$Friday->user->FullNAme}} </a></span>
            @else
            <div class="text-xs text-white bg-blue-500 px-2 py-1 rounded inline-block mx-1 cursor-pointer" >Free
                                   </div>
            @endif

          </td>

          @endforeach

          @else
          <td>
           <span class="text-gray-700">
            Free</span></td>
           @endif

       </tr>
      @endif    
                                @endforeach
                            </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @else
                     <label class="text-sm mx-2 my-1">No classes are Assigned</label>
                    @endif
                </div>
            @endforeach
        </form>

      </div>