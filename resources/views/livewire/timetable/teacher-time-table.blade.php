<div class="relative w-full" id="printDiv"> 

<form>
  @foreach($teacherLinks as $teacher)

 @if($teacher->teacher!=null)
<h1 class="mx-2 my-1 font-semibold"> <a href="{{ url('/admin/teacher/show/'.$teacher->teacher->name) }}">

  @php

  if($teacher->teacher->userprofile->gender=='female' && $teacher->teacher->userprofile->marital_status=='married'){

         $status='Mrs.';
  }
  else if($teacher->teacher->userprofile->gender=='female' && $teacher->teacher->userprofile->marital_status!='married'){

         $status='Ms.';
  }else{
      $status='Mr.';
  }

  @endphp


   {{$status}} {{$teacher->teacher->FullName}}</a></h1>
 @if($teacher->TeacherTimeTable!=0)
<div class="relative"> 
<div class="flex flex-row justify-between custom-table">   
       <div class="w-full">
    <table class="w-full custom-timetable">
       <thead class="bg-grey-light">
      <tr>
        <th>Days</th>
        @for($i=1;$i<=8;$i++)
        <th>{{$i}}</th>
        @endfor
      </tr>
    </thead>

     <tbody class="bg-grey-light">

       @foreach($weekdays as $weekday)

       @if($weekday=='Monday')
      <tr>
        <td>{{$weekday}}</td>

        @for($m=0;$m<8;$m++)

        @php

        $Monday=$teacher->temp_timetable->where('day','Monday')->where('schedule',$m)->first();

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

        $Tuesday=$teacher->temp_timetable->where('day','Tuesday')->where('schedule',$m)->first();

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

        $Wednesday=$teacher->temp_timetable->where('day','Wednesday')->where('schedule',$m)->first();

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

        $Thursday=$teacher->temp_timetable->where('day','Thursday')->where('schedule',$m)->first();

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

        $Friday=$teacher->temp_timetable->where('day','Friday')->where('schedule',$m)->first();

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
    </table>
    </div>
</div>
</div>
@else
 <tr>
<td colspan="9">
   <p class="text-sm mx-2 my-1">No Records Found</p><!--  style="text-align: center;" -->
   </td>
</tr>@endif
@endif
@endforeach



</form>

</div>