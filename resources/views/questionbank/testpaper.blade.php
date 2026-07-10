<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Question Paper</title>


</head>

<body style="width:90%,margin-left:10%;font-size: 12px">






<style>
    hr{
        width: 100%;
        height: 1px;
        border-top: 1px solid black;
    }
</style>

   <div style="text-align:center">
    <h3 class="text-center" style="color:black;">{{$test->school->name}}</h3>
    <h4 class="text-center" style="color:black;">4 th sem</h4>
    <h4 class="text-center" style="color:black;"><strong> Subject: {{$test->subject->name}} </strong></h4>
    <p><strong> Exam Date: {{date('d-m-Y ',strtotime($test->test_at))}} | Exam Time: {{date('h:i A',strtotime($test->test_at))}} | Exam Duration: {{$test->duration}} | Total marks: {{$test->total_marks}} 
    </strong> </p>
    </div>
        
    
    <hr>
    

        
        <table border="0px" align="center" width="100%" cellpadding="10px" style="margin-left: 25px;">
        
            @foreach($patterns as $key=>$pattern)
            <tr>
               <td style="width:5%"> <p style="margin-left: 25px;font-weight: bold;border: 2px solid;padding: 5px;border-radius: 0px ;">{!!$key!!}</p> </td>
               
            </tr>

            @foreach($pattern as $head=>$head_values)

                  
                    @foreach($head_values['question'] as $ques_key=>$question)

            <tr>

               <td style="width:5%"> 
               	<p style="margin-left: 25px;font-weight: bold;padding: 5px;border-radius: 0px ;">
               		{{$ques_key}} ) {!!$question->question!!}
               	</p> 
               	 	@if(count($question['quizoptions'])>0)
		               @foreach($question['quizoptions'] as $optkey=>$option)
		                    <p style="margin-left: 50px;">{{$option->option}}</p>
		               @endforeach
               		@endif
               </td>
              
               
            </tr>
         
                      @endforeach

             @endforeach

           <!--  <tr>
               <td style="width:5%"> <p style="margin-left: 25px;font-weight: bold"> a)</p> </td>
               <td style="width: 85%">{!! $questions1[$i]->questionTitle!!}</td>
               <td > <p style="margin-left: 0px;font-weight: bold">1</p></td>
            </tr>
            <tr>
               <td style="width:5%"> <p style="margin-left: 25px;font-weight: bold"> b)</p> </td>
               <td style="width: 85%">{!! $questions2[$i]->questionTitle!!}</td>
               <td > <p style="margin-left: 0px;font-weight: bold">2</p></td>
            </tr>
            <tr>
               <td style="width:5%"> <p style="margin-left: 25px;font-weight: bold"> c)</p> </td>
               <td style="width: 85%;	">{!! $questions3[$i]->questionTitle!!}</td>
               <td > <p style="margin-left: 0px;font-weight: bold">3</p></td>
            </tr>
            <tr>
               <td style="width:5%"> <p style="margin-left: 25px;font-weight: bold"> d)</p> </td>
               <td style="width: 85%">{!! $questions4[$i]->questionTitle!!}</td>
               <td > <p style="margin-left: 0px;font-weight: bold">4</p></td>
            </tr>
             -->
            
               @endforeach
        </table>



</body>

</html>