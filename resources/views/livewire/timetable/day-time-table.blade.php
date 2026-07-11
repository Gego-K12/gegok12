<form>
<div class="w-full px-1 my-2 bg-white">
<div>
     <div class="flex items-center justify-between">
                    <h1 class="text-gray-800 px-3 font-semibold text-xl py-2 pb-3">Day Timetable</h1>
                        <div class="flex  items-center mt-3">
                            <select class="tw-form-control w-full" id="day" wire:model="day"  name="day" >
                                <option value="">Filter By Day</option>
                                @foreach($weekdays as $weekday)
                                <option value="{{$weekday}}" wire:click="load('{{$weekday}}')">{{ucfirst($weekday)}}</option>
                                @endforeach
                            </select>
                          
                                <a href="#" onclick="printTeacher()" class="blue-bg text-sm text-white px-2 py-1 rounded mx-1">Print</a>
                           
                        </div>        
                </div>

                 <div class="relative" id="div-print"> 
                    <div class="flex flex-row justify-between custom-table">   
                        <div class="w-full table-responsive m-2 overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-grey-light">
                                    <tr class="bg-white text-black"><th class="text-black border-r" style="color:black;text-align: center;font-size: 15px;font-weight: bold;">Name </th> <th colspan="8"  style="color:black;    text-align: center;font-size: 15px;font-weight: bold;">{{$day}}</th></tr>
                                    <tr>
                                        <th></th>
                                        @for($i = 1 ; $i <= 8 ; $i++)
                                            <th>{{ $i }}</th>
                                        @endfor
                                    </tr>
                                </thead>

                                @if($timetables > 0)
                                    <tbody class="bg-grey-light">
                                        
                                        @foreach($timetables as $key=>$timetable)

                                        @if($key!='')

                                        @php

  if($timetable[0]->user->userprofile->gender=='female' && $timetable[0]->user->userprofile->marital_status=='married'){

         $user_title='Mrs.';
  }
  else if($timetable[0]->user->userprofile->gender=='female' && $timetable[0]->user->userprofile->marital_status!='married'){

         $user_title='Ms.';
  }else{
      $user_title='Mr.';
  }

  @endphp

                                        <tr>
                                            <td >
                                                <p >{{$user_title}} {{$key}}</p>
                                            </td>
                                            @for($i = 0 ; $i <= 7 ; $i++)
                                            <td  >@foreach($timetable as $value)
                                            @if($value->schedule==$i)
                                            <p>{{ $value->standardLink->StandardSection }}</p>

                                            @php

          if($value->subject->name=='SOCIAL SCIENCE'){

            $subjectname='SOC SCI';
          }
           else if($value->subject->name=='COMPUTER APPLICATION'){

            $subjectname='CA';
          }
          else if($value->subject->name=='COMPUTER SCIENCE'){

            $subjectname='CS';
          }
          else if($value->subject->name=='ECONOMICS'){

            $subjectname='ECO';
          }
           else if($value->subject->name=='COMMERCE'){

            $subjectname='COMM';
          }
           else if($value->subject->name=='ACCOUNTANCY'){

            $subjectname='ACC';
          }
          else if($value->subject->name=='CHEMISTRY'){

            $subjectname='CHE';
          }

          else{
              $subjectname=$value->subject->name;
          }

          @endphp


                                            <p>{{ $subjectname }}</p>
                                            @endif
                                            @endforeach

                                            </td>
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
            </div>
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
