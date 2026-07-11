<form>
<div class="w-full px-1 my-2 bg-white">

    <div class="flex items-center justify-between">
                    <h1 class="text-gray-800 px-3 font-semibold text-xl py-2 pb-3">Class Timetable</h1>
                        <div class="flex  items-center mt-3">
                            <select class="tw-form-control w-full" id="class_teacher_id" wire:model="standard_id"  name="class_teacher_id" >
                                <option value="">Filter By Class</option>
                                @foreach($standards as $standard)
                                <option value="{{$standard->id}}" wire:click="load('{{$standard->id}}')">{{$standard->StandardSection}}</option>
                                @endforeach
                            </select>
                            @if( $standard->id != null)  
                                <a href="#" onclick="printClass()" class="blue-bg text-sm text-white px-2 py-1 rounded mx-1">Print</a>
                            @endif
                        </div>
                        
                </div>
    
            <div id="printDiv" class=""> 
                <h1 class="mx-2 my-1 font-semibold">
                    <a href="{{ url('/admin/standardLink/show/'.$standardLink->id) }}">{{ $standardLink->StandardSection }}</a>
                </h1>
                <div class="relative"> 
                    <div class="flex flex-row justify-between custom-table">   
                        <div class="w-full table-responsive m-2 overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-grey-light">
                                    <tr>
                                        <th>Days</th>
                                        @for($i = 1 ; $i <= 8 ; $i++)
                                            <th>{{ $i }}</th>
                                        @endfor
                                    </tr>
                                </thead>

                                @if($standardLink->TimeTableCount > 0)
                                    <tbody class="bg-grey-light">
                                        @foreach($weekdays as $weekday)
                                            @if($weekday == 'Monday')

                                
      <tr>
        <td>{{$weekday}}</td>
       
         @php

        $Mondays=$standardLink->temp_timetable->where('day','Monday')->take(8);

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

        $Tuesdays=$standardLink->temp_timetable->where('day','Tuesday')->take(8);


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

        $Wednesdays=$standardLink->temp_timetable->where('day','Wednesday')->take(8);


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

        $Thursdays=$standardLink->temp_timetable->where('day','Thursday')->take(8);

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

        $Fridays=$standardLink->temp_timetable->where('day','Friday')->take(8);


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
                                @else
                                    <tbody class="bg-grey-light">
                                        <tr>
                                            <td colspan="9">
                                                <p style="text-align: center;">No Records Found</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
   </div>
</form>

@push('scripts')
    <script>
        function printClass()
        {
            var print_ = document.getElementById("printDiv");
            var htmlToPrint = '' +
            '<style type="text/css">' +
            'table th, table td {' +
                'border:1px solid #000;' +
                'padding: 10px;' +
            '}' +
            '.flex {' +
                'display: flex;' +
            '}' +
            '.flex-row {' +
                'flex-direction: row;' +
            '}' +
            'table th, table td .bg-grey-light {' +
            'background-color: #dae1e7;' +
            '}' +
            'a {' +
                'text-decoration: none;' +  
                'color: #4a5568;' +
            '}' +
            '.font-semibold {' +
                'font-weight: 600;' +
            '}' +
            'text-gray-700 {' +
                '--text-opacity: 1;' +
                'color: #4a5568;' +
            '}' +
            'table.borderTable thead th, table.borderTable thead td {' +
                'border-bottom: 1px solid #1110;' +
            '}' +
            'table.borderTable {' +
                'width: 100%;' +
                'margin: 0 auto;' +
                'clear: both;' +
                'border-collapse: collapse;' +
                'border-spacing: 0;' +
            '}' +

            '@media only screen and (max-width: 760px), (max-device-width: 1024px) and (min-device-width: 768px){ ' +
                'td {' +
                    'border: none;' +
                    'border-bottom: 1px solid #eee;' +
                    'position: relative;' +
                    'padding-left:50%;' +
                    'padding-top:5%;' +
                    'padding-bottom:5%;' +
                '}' +
                '.borderTables_wrapper .borderTables_length, .borderTables_wrapper .borderTables_filter {' +
                    'float: none;' +
                    'text-align: left;' +
                '}' +
            '}' +
     
            '.t-dropdown.t-dropdown-size-sm button' +
            '{' +
                'border:none! important;' +
            '}' +
            '</style>';
            htmlToPrint += print_.outerHTML;
            win = window.open("");
            win.document.write( "<link rel='stylesheet' href='css/app.css' type='text/css' media='print'/>" );
            win.document.write(htmlToPrint);
            win.print();
            win.close();
        }
    </script>
@endpush