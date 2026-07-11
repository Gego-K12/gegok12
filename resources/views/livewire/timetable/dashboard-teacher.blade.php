<form>
<div class="w-full px-1 my-2 bg-white">

    <div class="flex items-center justify-between">
                    <h1 class="text-gray-800 px-3 font-semibold text-xl py-2 pb-3">Teacher Timetable</h1>
                        <div class="flex  items-center mt-3">
                            <select class="tw-form-control w-full" id="class_teacher_id" wire:model="teacher_id"  name="class_teacher_id" >
                                <option value="">Filter By Teacher</option>
                                @foreach($teachers as $teacher)
                                <option value="{{$teacher->id}}" wire:click="load('{{$teacher->id}}')">{{$teacher->fullname}}</option>
                                @endforeach
                            </select>
                            @if( $teacher->id != null)  
                                <a href="#" onclick="printTeacher()" class="blue-bg text-sm text-white px-2 py-1 rounded mx-1">Print</a>
                            @endif
                        </div>
                        
                </div>

<div class="relative" id="div-print">    
    <form>
        <h1 class="mx-2 my-1 font-semibold">

            @php

  if($teacherLink->teacher->userprofile->gender=='female' && $teacherLink->teacher->userprofile->marital_status=='married'){

         $status='Mrs.';
  }
  else if($teacherLink->teacher->userprofile->gender=='female' && $teacherLink->teacher->userprofile->marital_status!='married'){

         $status='Ms.';
  }else{
      $status='Mr.';
  }

  @endphp
          @if($teacherLink!=null)
            <a href="{{ url('/admin/teacher/show/'.$teacherLink->teacher->name) }}">{{$status}} {{ $teacherLink->teacher->FullName }}</a>
            @endif 
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

                        @if($teacherLink->TeacherTimeTable > 0)
                            <tbody class="bg-grey-light">
                                @foreach($weekdays as $weekday)
                                    @if($weekday=='Monday')
      <tr>
        <td>{{$weekday}}</td>

        @for($m=0;$m<8;$m++)

        @php

        $Monday=$teacherLink->temp_timetable->where('day','Monday')->where('schedule',$m)->first();

        @endphp

    
         @if(count($Monday)>0)

         @php

          if($Monday->subject->name=='SOCIAL SCIENCE'){

            $subjectname='SOC SCI';
          }
           else if($Monday->subject->name=='COMPUTER APPLICATION'){

            $subjectname='CA';
          }
          else if($Monday->subject->name=='COMPUTER SCIENCE'){

            $subjectname='CS';
          } else if($Monday->subject->name=='ECONOMICS'){

            $subjectname='ECO';
          }else if($Monday->subject->name=='COMMERCE'){

            $subjectname='COMM';
          }
          else if($Monday->subject->name=='ACCOUNTANCY'){

            $subjectname='ACC';
          }
          else if($Monday->subject->name=='CHEMISTRY'){

            $subjectname='CHE';
          }

        else{
              $subjectname=$Monday->subject->name;
          }

          @endphp

        <td  class="">
          
         <span class="font-semibold"> {{$subjectname}}</span><br/>
           <span class="text-gray-700">
            <a href="{{ url('/admin/standardLink/show/'.$teacher->Monday[$m]->standardLink_id) }}">{{$Monday->standardLink->StandardSection}}</a></span>
        </td>
      @else
      <td>Free</td>

        @endif
      @endfor
       </tr>
      @endif

                                    @if($weekday=='Tuesday')
      <tr>
        <td>{{$weekday}}</td>
        @for($m=0;$m<8;$m++)
            @php

        $Tuesday=$teacherLink->temp_timetable->where('day','Tuesday')->where('schedule',$m)->first();

        @endphp

    
         @if(count($Tuesday)>0)

           @php

          if($Tuesday->subject->name=='SOCIAL SCIENCE'){

            $subjectname='SOC SCI';
          }
           else if($Tuesday->subject->name=='COMPUTER APPLICATION'){

            $subjectname='CA';
          }
          else if($Tuesday->subject->name=='COMPUTER SCIENCE'){

            $subjectname='CS';
          }
          else if($Tuesday->subject->name=='ECONOMICS'){

            $subjectname='ECO';
          }
           else if($Tuesday->subject->name=='COMMERCE'){

            $subjectname='COMM';
          }
           else if($Tuesday->subject->name=='ACCOUNTANCY'){

            $subjectname='ACC';
          } else if($Tuesday->subject->name=='CHEMISTRY'){

            $subjectname='CHE';
          }

          else{
              $subjectname=$Tuesday->subject->name;
          }

          @endphp
        <td  class="">
         <span class="font-semibold"> {{$subjectname}}</span><br/>
           <span class="text-gray-700">
            <a href="{{ url('/admin/standardLink/show/'.$teacher->Tuesday[$m]->standardLink_id) }}">{{$Tuesday->standardLink->StandardSection}}</a></span>
        </td>
      @else
      <td>Free</td>
        @endif
      @endfor
       </tr>
      @endif
      @if($weekday=='Wednesday')
      <tr>
        <td>{{$weekday}}</td>
        @for($m=0;$m<8;$m++)
         @php

        $Wednesday=$teacherLink->temp_timetable->where('day','Wednesday')->where('schedule',$m)->first();

        @endphp

    
         @if(count($Wednesday)>0)


          @php

          if($Wednesday->subject->name=='SOCIAL SCIENCE'){

            $subjectname='SOC SCI';
          }
           else if($Wednesday->subject->name=='COMPUTER APPLICATION'){

            $subjectname='CA';
          }
          else if($Wednesday->subject->name=='COMPUTER SCIENCE'){

            $subjectname='CS';
          }
          else if($Wednesday->subject->name=='ECONOMICS'){

            $subjectname='ECO';
          }
           else if($Wednesday->subject->name=='COMMERCE'){

            $subjectname='COMM';
          }
           else if($Wednesday->subject->name=='ACCOUNTANCY'){

            $subjectname='ACC';
          }
          else if($Wednesday->subject->name=='CHEMISTRY'){

            $subjectname='CHE';
          }

          else{
              $subjectname=$Wednesday->subject->name;
          }

          @endphp
       <td  class="">
         <span class="font-semibold"> {{$subjectname}}</span><br/>
           <span class="text-gray-700">
            <a href="{{ url('/admin/standardLink/show/'.$Wednesday->standardLink_id) }}">{{$Wednesday->standardLink->StandardSection}}</a></span>
        </td>
      @else
      <td>Free</td>
        @endif
      @endfor
       </tr>
      @endif
      @if($weekday=='Thursday')
      <tr>
        <td>{{$weekday}}</td>
        @for($m=0;$m<8;$m++)
         @php

        $Thursday=$teacherLink->temp_timetable->where('day','Thursday')->where('schedule',$m)->first();

        @endphp

    
         @if(count($Thursday)>0)


          @php

          if($Thursday->subject->name=='SOCIAL SCIENCE'){

            $subjectname='SOC SCI';
          }
           else if($Thursday->subject->name=='COMPUTER APPLICATION'){

            $subjectname='CA';
          }
          else if($Thursday->subject->name=='COMPUTER SCIENCE'){

            $subjectname='CS';
          }
          else if($Thursday->subject->name=='ECONOMICS'){

            $subjectname='ECO';
          }
           else if($Thursday->subject->name=='COMMERCE'){

            $subjectname='COMM';
          }
           else if($Thursday->subject->name=='ACCOUNTANCY'){

            $subjectname='ACC';
          }
          else if($Thursday->subject->name=='CHEMISTRY'){

            $subjectname='CHE';
          }

          else{
              $subjectname=$Thursday->subject->name;
          }

          @endphp
       <td  class="">
         <span class="font-semibold"> {{$subjectname}}</span><br/>
           <span class="text-gray-700">
            <a href="{{ url('/admin/standardLink/show/'.$Thursday->standardLink_id) }}">{{$Thursday->standardLink->StandardSection}}</a></span>
        </td>
      @else
      <td>Free</td>
        @endif
      @endfor
       </tr>
      @endif
      @if($weekday=='Friday')
      <tr>
        <td>{{$weekday}}</td>
        @for($m=0;$m<8;$m++)
         @php

        $Friday=$teacherLink->temp_timetable->where('day','Friday')->where('schedule',$m)->first();

        @endphp

    
         @if(count($Friday)>0)


          @php

          if($Friday->subject->name=='SOCIAL SCIENCE'){

            $subjectname='SOC SCI';
          }
           else if($Friday->subject->name=='COMPUTER APPLICATION'){

            $subjectname='CA';
          }
          else if($Friday->subject->name=='COMPUTER SCIENCE'){

            $subjectname='CS';
          }
          else if($Friday->subject->name=='ECONOMICS'){

            $subjectname='ECO';
          }
           else if($Friday->subject->name=='COMMERCE'){

            $subjectname='COMM';
          }
           else if($Friday->subject->name=='ACCOUNTANCY'){

            $subjectname='ACC';
          }
          else if($Friday->subject->name=='CHEMISTRY'){

            $subjectname='CHE';
          }

          else{
              $subjectname=$Friday->subject->name;
          }

          @endphp
       <td  class="">
         <span class="font-semibold"> {{$subjectname}}</span><br/>
           <span class="text-gray-700">
            <a href="{{ url('/admin/standardLink/show/'.$Friday->standardLink_id) }}">{{$Friday->standardLink->StandardSection}}</a></span>
        </td>
      @else
      <td>Free</td>
        @endif
      @endfor
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
    </form>
</div>

</div>
</form>

@push('scripts')
    <script>
        function printTeacher()
        {
            var print_ = document.getElementById("div-print");
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