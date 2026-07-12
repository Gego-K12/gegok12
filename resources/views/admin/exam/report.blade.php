<!DOCTYPE html>
<html>
<head>
<link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/1.1.3/tailwind.min.css" rel="stylesheet">
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body class="font-sans">
<div class="w-full lg:w-1/2 md:w-1/2 mx-auto ">
@foreach($marks as $mark)
<div class="px-5 py-3 bg-white border my-5 box">
<div class="ribbon ribbon-top-left"><span>{{$mark[0]['exam']['name']}}</span></div>
<div class="pb-5">
  <h1 class="text-center text-4xl font-semibold tracking-wider" style="color: #50514d;"> REPORT CARD </h1>
  <h4 class="text-center text-lg font-normal tracking-wide" style="color: #828e85;"> {{$school->name}}</h4>
</div>
<div class="py-6 flex justify-between">
<div class="px-2">
 <p class="font-semibold my-1">ROLL NO : {{$mark[0]['user']['registration_number']}}</p>
 <p class="text-base my-1" style="color: #afaaaa;">Mother's / Father's Guardian's Name :{{$mark[0]['user']['parents'][0]['userParent']['userprofile']['firstname']}} {{$mark[0]['user']['parents'][0]['userParent']['userprofile']['lastname']}}</p>
 <div class="flex items-center my-1">
 <p class="text-base" style="color: #afaaaa;">Class : {{$mark[0]['standard']['standard']['name']}}</p>
 <p class="text-base mx-10" style="color: #afaaaa;">Section :{{$mark[0]['standard']['section']['name']}}</p>
 </div>
 </div>
 <div><img src="{{$mark[0]['user']['userprofile']['AvatarPath']}}" class="w-20 h-20 border"></div>
 </div>
 <div class="border">
  <p class="border-b py-2 px-2 text-base" style="color: #787777;">Name of the Student : <span class="font-bold text-sm">{{$mark[0]['user']['userprofile']['firstname']}} {{$mark[0]['user']['userprofile']['lastname']}}</span></p>
  <div class="flex border-b">
    <div class="w-1/3">
      <p class="py-2 px-2 text-base" style="color: #787777;">Date of Birth : <span class="font-bold text-sm">{{ date('d-M-Y',strtotime(optional($mark[0]['user']['userprofile'])['date_of_birth'])) }}</span></p>
    </div>
    <div class="w-1/3">
      <p class="py-2 px-2 text-base border-r border-l" style="color: #787777;">Academic Year : <span class="font-bold text-sm">{{$mark[0]['academicyear']['name']}}</span></p>
    </div>
    <div class="w-1/3">
      <p class="py-2 px-2 text-base" style="color: #787777;">Attendance % : <span class="font-bold text-sm">80%</span></p>
    </div>
  </div>
  <div class="py-2">
    <h3 class="text-center text-lg font-semibold " style="color: #176fb7;">{{$mark[0]['exam']['name']}}</h3>
  </div>
  <div>
    <table class="w-full text-sm">
      <thead style="background-color: #176fb7; color: #fff;">
        <tr>
          <th class="px-2 py-2 border font-medium" style="border-color:#217dc8;">Name of the Subject</th>
          <th class="px-2 py-2 border font-medium" style="border-color:#217dc8;">Marks Obtained </th>
<!--           <th class="px-2 py-2 border font-medium" style="border-color:#217dc8;">Grades Awarded</th>
 -->          <!-- <th class="px-2 py-2 border font-medium" style="border-color:#217dc8;">Teachers Comment</th> -->
        <!--   <th class="px-2 py-2 border font-medium" style="border-color:#217dc8;">Student Status</th> -->
        </tr>
      </thead>
      <tbody style="color: #787777;">
     @foreach($mark as $subject)
      <tr>
    
        <td class="px-2 py-2 border">{{$subject['subject']['name']}}</td>
        <td class="px-2 py-2 border">
          @if($subject['student_status']=='present')
          @if($mark[0]['exam']['scholastic']['grading_method']=='cbse')
         {{$subject['grade']['grades']}}</td>
         @else
         {{$subject['obtained_marks']}}
         @endif
         @else
         {{ucfirst($subject['student_status'])}}
         @endif
<!--         <td class="px-2 py-2 border">{{$subject['grade']['grades']}}</td>
 -->       <!--  <td class="px-2 py-2 border">{{$subject['teacher_comment']}}</td> -->
       <!--  <td class="px-2 py-2 border">{{ucfirst($subject['student_status'])}} </td> -->
      

      </tr>
        @endforeach
     @if($mark[0]['exam']['scholastic']['grading_method']!='cbse')     
      <tr>
        <td class="px-2 py-2 border">Total</td>
        <td colspan="4" class="px-2 py-2 border font-bold text-black text-lg italic">{{ $mark[0]->getMarkObtained($mark[0]['user_id'],$mark[0]['exam_id']) }}</td>
      </tr>
       <tr>
        <td class="px-2 py-2 border">Percentage</td>
        <td colspan="4" class="px-2 py-2 border font-bold text-black text-lg italic">{{$mark[0]->getPercentageMark($mark[0]['user_id'],$mark[0]['exam_id']) }}%</td>
      </tr>
      @else
      <tr>
        <td class="px-2 py-2 border">Grade</td>
        <td colspan="4" class="px-2 py-2 border font-bold text-green-700 text-lg italic">{{$mark[0]->getTotalGrade($mark[0]['user_id'],$mark[0]['exam_id'])}}</td>
      </tr>
      @endif
     
      </tbody>
    </table>
  </div>
        <div class="py-2">
        <div class="flex">
        <div class="w-1/2 pt-3 px-2">
          <table class="w-full text-sm">
              <thead style="background-color: #176fb7; color: #fff;">
                <tr>
                  <th class="px-2 py-2 border font-medium" style="border-color:#217dc8;">Progressive</th>
                </tr>
              </thead>
              <tbody style="color: #787777;">
              @if(count($mark[0]->get_Progressive($mark[0]['user_id'],$mark[0]['exam_id']))>0)
              @foreach($mark[0]->get_Progressive($mark[0]['user_id'],$mark[0]['exam_id']) as $key => $value)
              <tr>
                <td class="px-2 py-2 border">{{strtoupper($value->subject->name)}}</td>
              </tr>
              @endforeach
              @else
              <tr>
                <td class="px-2 py-2 border">---</td>
              </tr>
              @endif
              </tbody>
              </table>
            </div>
        <div class="w-1/2 pt-3 px-2">
          <table class="w-full text-sm">
              <thead style="background-color: #176fb7; color: #fff;">
                <tr>
                  <th class="px-2 py-2 border font-medium" style="border-color:#217dc8;">Need Attention</th>
                </tr>
              </thead>
              <tbody style="color: #787777;">
              @if(count($mark[0]->get_Attention($mark[0]['user_id'],$mark[0]['exam_id']))>0)
              @foreach($mark[0]->get_Attention($mark[0]['user_id'],$mark[0]['exam_id']) as $key => $value)
              <tr>
                <td class="px-2 py-2 border">{{strtoupper($value->subject->name)}}</td>
              </tr>
              @endforeach
              @else
              <tr>
                <td class="px-2 py-2 border">---</td>
              </tr>
              @endif
              </tbody>
              </table>
          </div>
        </div>
      </div>
</div>

<!--   <div class="py-3 flex items-center">
     <p class="font-semibold text-sm">Grading System : </p>
     <div class="flex mx-4 text-sm">
       <p class="px-3"><b>A :</b> <span style="color: #787777;">Outstanding</span></p>
       <p class="px-3"><b>B :</b> <span style="color: #787777;">Very Good</span></p>
       <p class="px-3"><b>C :</b> <span style="color: #787777;">Fair</span></p>
     </div>
  </div>
<div class="py-2">
 <h3 class="font-semibold text-base px-2" style="color: #787777;">CO- SCHOLASTIC AREAS:TERM I (ON A 3 POINT GRADING SCALE)</h3>
  <div class="flex">
    <div class="w-1/2 pt-3 px-2">
     <table class="w-full text-sm">
      <thead style="background-color: #176fb7; color: #fff;">
        <tr>
          <th class="px-2 py-2 border font-medium" style="border-color:#217dc8;"></th>
          <th class="px-2 py-2 border font-medium text-left" style="border-color:#217dc8;">Grade</th>
        </tr>
      </thead>
      <tbody style="color: #787777;">
      @foreach($mark[0]['grades_awarded'] as $key => $value)
      <tr>
        <td class="px-2 py-2 border">{{strtoupper($key)}}</td>
        <td class="px-2 py-2 border">{{$value}}</td>
      </tr>

      @endforeach
      </tbody>
      </table>
    </div>
    <div class="w-1/2 pt-3 px-2">
      <table class="w-full text-sm">
      <thead style="background-color: #176fb7; color: #fff;">
        <tr>
          <th class="px-2 py-2 border font-medium" style="border-color:#217dc8;"></th>
          <th class="px-2 py-2 border font-medium text-left" style="border-color:#217dc8;">Grade</th>
        </tr>
      </thead>
      <tbody style="color: #787777;">
      <tr>
        <td class="px-2 py-2 border">Discipline Term 1</td>
        <td class="px-2 py-2 border">A</td>
      </tr>
      
      </tbody>
      </table>
    </div>
  </div>
  <div class="px-2 pt-4">
    <textarea class="border w-full px-2 py-2" rows="4" placeholder="Teacher's Comment">{{$mark[0]['teacher_comment']}}</textarea>
  </div>
</div> -->
</div>


@endforeach
</body>
</html>
<style>
.box {
  position: relative;
  box-shadow: 0 0 15px rgba(0,0,0,.1);
}

/* common */
.ribbon {
  width: 150px;
  height: 150px;
  overflow: hidden;
  position: absolute;
}
.ribbon::before,
.ribbon::after {
  position: absolute;
  z-index: -1;
  content: '';
  display: block;
  border: 5px solid #2980b9;
}
.ribbon span {
  position: absolute;
  display: block;
  width: 225px;
  padding: 15px 0;
  background-color: #176fb7;
  box-shadow: 0 5px 10px rgba(0,0,0,.1);
  color: #fff;
  font: 700 18px/1 'Lato', sans-serif;
  text-shadow: 0 1px 1px rgba(0,0,0,.2);
  text-transform: uppercase;
  text-align: center;
}

/* top left*/
.ribbon-top-left {
  top: -10px;
  left: -10px;
}
.ribbon-top-left::before,
.ribbon-top-left::after {
  border-top-color: transparent;
  border-left-color: transparent;
}
.ribbon-top-left::before {
  top: 0;
  right: 0;
}
.ribbon-top-left::after {
  bottom: 0;
  left: 0;
}
.ribbon-top-left span {
  right: -25px;
  top: 30px;
  transform: rotate(-45deg);
}
</style>
