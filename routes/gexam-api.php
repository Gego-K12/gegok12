  <?php
  use Illuminate\Support\Facades\Route;
  use Gegok12\Exam\Http\Controllers\Api\Teacher\Exam\ExamController;
  use Gegok12\Exam\Http\Controllers\Api\ExamController as PExamController;
  use Gegok12\Exam\Http\Controllers\Api\MarksController as PMarksController;

  Route::group([ 'prefix' => 'teacher' , 'middleware'=>['auth:sanctum'] , 'namespace' =>'Api\Teacher' ], 
    function() 
{
      Route::group([ 'namespace' =>'Approval' ], function () {
      //exam
        Route::get('/exam/list',[ExamController::class,'index']);
        Route::get('/exam/add/list/{schedule_id}',[ExamController::class,'list']);
        Route::post('/exam/add/marks/{schedule_id}',[ExamController::class,'store']);
        Route::get('/exam/marks/show/{schedule_id}',[ExamController::class,'show']);
      });
 });

  Route::group([
    'prefix' => 'v2', 
    'namespace' =>'Api' ,
    'middleware' => ['auth:sanctum'],
  ], function () {

      Route::get('/exams/upcoming/{student_id}',[PExamController::class,'upcomingExam']);

      Route::get('/exams/past/{student_id}',[PExamController::class,'pastExam']);

      //Mark

      Route::get('/marks/{student_id}/{exam_id}',[PMarksController::class,'index']);
      Route::get('/marks/graph/{student_id}/{exam_id}',[PMarksController::class,'getmarks']);

      Route::get('/mark/show/{mark_id}',[PMarksController::class,'show']);
   

  });