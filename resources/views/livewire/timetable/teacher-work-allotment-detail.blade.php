<form>
<div class="w-full px-1 my-2 bg-white">
<div>
     {{-- <div class="flex items-center justify-between">
                    <h1 class="text-gray-800 px-3 font-semibold text-xl py-2 pb-3">Work Allotment</h1>
                        <div class="flex  items-center mt-3">
                         <select class="tw-form-control w-full" id="day" wire:model="day"  name="day" >
                                <option value="">Filter By Day</option>
                                @foreach($weekdays as $weekday)
                                <option value="{{$weekday}}" wire:click="load('{{$weekday}}')">{{ucfirst($weekday)}}</option>
                                @endforeach
                            </select> 
                          
                                <a href="#" onclick="printTeacher()" class="blue-bg text-sm text-white px-2 py-1 rounded mx-1">Print</a>
                           
                        </div>        
          </div> --}}

                 <div class="relative" id="div-print"> 
                    <div class="flex flex-row justify-between custom-table">   
                        <div class="w-full table-responsive m-2 overflow-x-auto">
                            <table  class="w-full">
  <col>
  <colgroup span="2"></colgroup>
  <colgroup span="2"></colgroup>
  <thead class="bg-grey-light">
  <tr class="">
    <th rowspan="2" style="color: #000 !important;vertical-align: baseline;">Teacher</th>
    <th colspan="8" scope="colgroup" class="border-l border-r border-b" style="color: #000 !important;text-align: center;font-size: 19px;">Work allotment 2022-2023 Regular Classes  I to XII 01/10/2022</th>
     <th rowspan="2" style="color: #000 !important;vertical-align: baseline;">Total</th>
  </tr>
  <tr >
    
   
    @for($i = 1 ; $i <= 8 ; $i++)
    <th scope="col" class="border-l border-r">{{ $i }}</th>                                  
    @endfor
   
  </tr>
 </thead>

  @if($timetables > 0)
                                    <tbody class="bg-grey-light">
                                        
                                        @foreach($timetables as $key=>$timetable)
                                        <tr>
                                            <td >
                                                <p >{{$key}}</p>
                                            </td>
                                            @for($i = 0 ; $i <= 7 ; $i++)
                                             <?php 
                                                $count=1;
                                             ?>
                                            <td  >@foreach($timetable as $value)

                                            @if($value->schedule==$i)
                                            <p>{{ $value->standardLink->StandardSection }}</p>
                                            <p>{{ $value->subject->name }}</p>

                                            <p>{{ $value->day }}</p>
                                            
                                            @endif
                                            <?php $count++; ?>
                                            @endforeach

                                            </td>
                                             @endfor
                                       
                                           <td>{{count($timetable)}}</td>
                                            
                                        </tr>
                                        
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

                            {{-- <table class="w-full">
                                <thead class="bg-grey-light">

                                    <tr>
                                        <th>Teacher</th>
                                        @for($i = 1 ; $i <= 8 ; $i++)
                                            <th>{{ $i }}</th>
                                            
                                        @endfor
                                        <th>Total</th>
                                    </tr>
                                    
                                </thead>

                                @if($timetables > 0)
                                    <tbody class="bg-grey-light">
                                        
                                        @foreach($timetables as $key=>$timetable)
                                        <tr>
                                            <td >
                                                <p >{{$key}}</p>
                                            </td>
                                            @for($i = 0 ; $i <= 7 ; $i++)
                                             <?php 
                                                $count=1;
                                             ?>
                                            <td  >@foreach($timetable as $value)

                                            @if($value->schedule==$i)
                                            <p>{{ $value->standardLink->StandardSection }}</p>
                                            <p>{{ $value->subject->name }}</p>
                                            
                                            @endif
                                            <?php $count++; ?>
                                            @endforeach

                                            </td>
                                             @endfor
                                       
                                           <td>{{count($timetable)}}</td>
                                            
                                        </tr>
                                        
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
                            </table> --}}
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
