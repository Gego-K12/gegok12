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

 <div class="border">
 <div class="border">
  
  <div class="py-2">
    <h3 class="text-center text-lg font-semibold " style="color: #176fb7;">{{$mark[0]['exam']['name']}} </h3>
  </div>
  <p class="border-b py-2 px-2 text-base" style="color: #787777;">Name of the Student : <span class="font-bold text-sm">{{$mark[0]['user']['userprofile']['firstname']}} {{$mark[0]['user']['userprofile']['lastname']}}</span></p></div>

  <div>
    <table class="w-full text-sm">
      <thead style="background-color: #176fb7; color: #fff;">
        <tr>
          <th class="px-2 py-2 border font-medium text-left" style="border-color:#217dc8;">Name of the Subject</th>
          <th class="px-2 py-2 border font-medium text-left" style="border-color:#217dc8;">Marks Obtained </th>
          
        </tr>
      </thead>
      <tbody style="color: #787777;">
      @foreach($mark as $subject)
      <tr>
        <td class="px-2 py-2 border">{{$subject['subject']['name']}}</td>
        <td class="px-2 py-2 border">{{$subject['obtained_marks']}}</td>
      
      </tr>
    @endforeach
 
      </tbody>
    </table>
  </div>
</div>
</div>
@endforeach

</div>
</body>
</html>
<style>
.box {
  position: relative;
  box-shadow: 0 0 15px rgba(0,0,0,.1);
}
</style>
