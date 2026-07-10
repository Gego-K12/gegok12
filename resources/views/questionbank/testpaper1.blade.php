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

<body style="width:100%,margin-left:10%;font-size: 12px">






<style>
    hr{
        width: 100%;
        height: 1px;
        border-top: 1px solid black;
    }
</style>

   <div style="text-align:center">
    <h3 class="text-center" style="color:black;font-size: 20px;">{{$test->school->name}}</h3>
    <h4 class="text-center" style="color:black;font-size: 15px;">{{$test->name}}</h4>
    <h4 class="text-center" style="color:black;font-size: 18px;"><strong> Subject: {{$test->subject->name}} </strong></h4>
    
    <table style="width: 100%;">
    <tbody>
    <tr style="font-size: 13px;">
     <td> <p> <b>Exam Date :</b> {{date('d-m-Y ',strtotime($test->test_at))}} </p> </td>
     <td> <p style="text-align: center;"> <b>Exam Time :</b> {{date('h:i A',strtotime($test->test_at))}} </p> </td>
     <td> <p style="text-align: center;"> <b>Exam Duration :</b>  {{$test->duration}} </p> </td>
     <td> <p style="text-align: right;"> <b>Total marks :</b> {{$test->total_marks}} </p> </td>
    </tr>
    </tbody>
    </table>
    </div>
        
    
    <hr>
    

        
        
        
            @foreach($patterns as $key=>$pattern)
            <div>
              <p style="text-align: left; font-size: 14px; font-weight: 600;">{!!$key!!}</p><p style="text-align: right; font-size: 14px; font-weight: 600;">{!!$pattern['total']!!} × {!!$pattern['head_marks']!!} = {!!$pattern['head_total']!!}</p>
               
            </div>

<?php 
$i=1;
?>    

@foreach($pattern['question'] as $keys1=> $questions)

@foreach($questions as $keys=>$question)

               <div style="width: 100%;">
                
                  <div style="margin-left: 20px;">
                  
                  <div style="font-style: bold; font-size: 12px;font-weight: 400;"> 
                    <span> {{$i}} )  </span> 
                    {!! $question->question!!} </div>
                  </div>
            
                @if(count($question['quizoptions'])>0)
                <?php 
                $alp='A';
                ?>  

                   @foreach($question['quizoptions'] as $optkey=>$option)
                    <div>
                        <p style="margin-left: 40px; width: 100%;">
                          {{$alp}} )  {{$option->option}}
                        </p>
                      </div>
                    <?php 
                    $alp++;
                    ?>    
                   @endforeach
                  @endif
                  </div>
              
<?php 
$i++;
?>
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
              

</body>

</html>