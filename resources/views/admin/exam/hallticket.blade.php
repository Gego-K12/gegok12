<!DOCTYPE html>
<html>
<head>
   <title>HALL TICKET</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans">

<table style="width: 100%;">
   @foreach($students as $key=>$studentdata) 
  <tbody style="width: 100%;">
    <tr>
       
  @php $check=0 @endphp
@foreach($studentdata as $student)
 <td style="padding: 5px;">
@php $check++ @endphp
<!-- <div class="flex justify-center">
  <div class="block p-6 rounded-lg shadow-lg bg-white max-w-sm">
    <h5 class="text-gray-900 text-xl leading-tight font-medium mb-2">{{Auth::user()->school->name}}</h5>
    <h5 class="text-gray-900 text-xl leading-tight font-medium mb-2">Pasumalai,{{Auth::user()->school->city->name}},{{Auth::user()->school->pincode}}</h5>
    <h4 class="text-gray-900 text-xl leading-tight font-medium mb-2">Hall Ticket</h4>
    <div class="flex">
      <label>Student Name</label>
      <p class="text-gray-700 text-base mb-4">
        {{$student->FullName}}
      </p>
    </div>
     <div class="flex">
      <label>Class</label>
      <p class="text-gray-700 text-base mb-4">
        {{$student->FullName}}
      </p>
    </div>
    <div class="flex">
    <label>Exam</label>
    <p class="text-gray-700 text-base mb-4">
      {{ucwords($exam->name)}}
    </p>
    </div>
   
  </div> -->

 <div>
  <div style="padding: 5px;border-radius:15px;max-width: 34rem;">
    <div style="border: 2px solid #737373;padding: 10px;border-radius:15px;background: url('images/card-bg1.png') center center / cover no-repeat;background-position: center;">
    <h5 style="color: #1E293B;font-size: 16px;margin-bottom: 8px;margin-top: 0;text-align: center;">{{Auth::user()->school->name}}</h5>
    <h6 style="color: #475569;font-size: 14px;margin-bottom: 8px;margin-top: 0;text-align: center;">{{Auth::user()->school->address}},{{Auth::user()->school->city->name}},{{Auth::user()->school->pincode}}</h6>
    @if(count($exam->schedule)<=0)
    <h4 style="color: #1D4ED8;font-size: 15px;margin-bottom: 8px;margin-top: 0;text-align: center;">Hall Ticket</h4>
   @endif
    <table style="margin-top: 10px;width: 100%;">
      <tbody>
        <tr style="vertical-align: top;">
          <td style="width: 85%;">
          <table>
            <tbody>
        <tr style="vertical-align: center;">
          <td style="padding: 5px 0;"><label style="font-weight: 600;font-size: 13px;">Name :</label></td>
          <td style="padding: 5px 15px;"><p style="margin:0;font-size:13px;"> {{$student->FullName}} </p></td>
        </tr>
        <tr style="vertical-align: center;">
          <td style="padding: 5px 0;"><label style="font-weight: 600;font-size: 13px;">Roll No :</label></td>
          <td style="padding: 5px 15px;"><p style="margin:0;font-size:13px;">   </p></td>
        </tr>
        <tr style="vertical-align: center;">
          <td style="padding: 5px 0;"><label style="font-weight: 600;font-size: 13px;">Admission No:</label></td>
          <td style="padding: 5px 15px;"><p style="margin:0;font-size:13px;">  {{$student->registration_number}} </p></td>
        </tr>
        <tr style="vertical-align: center;">
          <td style="padding: 5px 0;"><label style="font-weight: 600;font-size: 13px;">Class :</label></td>
          <td style="padding: 5px 15px;"><p style="margin:0;font-size:13px;"> {{optional(optional($student->studentAcademicLatest)->standardLink)->StandardSection}} </p></td>
        </tr>
        <tr style="vertical-align: center;">
          <td style="padding: 5px 0;"><label style="font-weight: 600;font-size: 13px;">Exam :</label></td>
          <td style="padding: 5px 15px;"><p style="margin:0;font-size:13px;">  {{ucwords($exam->name)}} </p></td>
        </tr>
      </tbody>
      </table>
      </td>
      <td style="width: 15%;text-align: right;">
        {{-- <img src="{{asset('uploads/female.png')}}" style="width: 60px;height: 60px;border-radius: 10px;padding:10px;"> --}}
       <img src="{{$student->userprofile->AvatarPath}}" style="width: 60px;height: 60px;border-radius: 10px;padding:10px;"></td>
    </tr>
      </tbody>
    </table>
@if(count($exam->schedule)>0)
<table  style="width: 100%;">
              <thead>
                <tr>
                  <th style="text-align: left;"> <label  style="font-weight: 600;font-size: 13px;">S.No.</label> </th>
                  <th style="text-align: left;"><label  style="font-weight: 600;font-size: 13px;">Subject/Paper</label></th>
                  <th style="text-align: left;"><label  style="font-weight: 600;font-size: 13px;">Date</label></th>
                  <th style="text-align: left;"><label  style="font-weight: 600;font-size: 13px;">Time</label></th>
                </tr>
              </thead>
              <tbody>
@foreach($exam->schedule as $key=>$schedule)
              <tr>
                <td><p style="margin:0;font-size:10px;"> {{$key+1}}</p></td>
                <td><p style="margin:0;font-size:10px;"> {{$schedule->subject->name}}</p></td>
                <td><p style="margin:0;font-size:10px;white-space: nowrap;">{{date('d-m-Y', strtotime($schedule->start_time))}}</p></td>
                <td><p style="margin:0;font-size:10px;">{{date('h:i a', strtotime($schedule->start_time))}}</p></td>
              </tr>
@endforeach
              </tbody>
            </table>
@endif            

    <div>
      <p style="margin: 0;margin-top: 25px;text-align: right;font-weight: 600;">Principal</p>
    </div>
  </div>
  </div>
</div>

@if( $check % 3 == 0 ) 
     @php echo '<div class="page-break"></div>'; @endphp
 @endif
 </td>
@endforeach

</tr>
</tbody>
@endforeach
</table>
</body>
</html>
<style>
.page-break {
 page-break-after: always;
}
</style>