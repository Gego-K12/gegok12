<?php
use Illuminate\Support\Facades\Route;
use Gegok12\Exam\Http\Controllers\Teacher\Exam\ExamController as TExamController;
use Gegok12\Exam\Http\Controllers\Teacher\Exam\ExammarkController as TExammarkController;
use Gegok12\Exam\Http\Controllers\Teacher\MarkController as TMarkController;

// Required from the end of routes/teacher.php, so these inherit the teacher
// portal's prefix('teacher') + middleware(['web','auth','teacher']) group set
// up in app/Providers/RouteServiceProvider.php. Using [Controller::class,
// 'method'] array syntax bypasses that group's namespace setting, so the
// controller can stay in the package's own namespace.

//Exam
Route::get( '/exam', [TExamController::class,'index']);
Route::get( '/exam/show', [TExamController::class,'show']);
Route::get( '/exammarks/{id}', [TExammarkController::class,'create']);
Route::get( '/exam/marks/list/{schedule_id}', [TExammarkController::class,'list']);
Route::post( '/exam/marks/add/{schedule_id}', [TExammarkController::class,'store']);
Route::get( '/exammarks/show/{schedule_id}', [TExammarkController::class,'show']);
Route::get( '/exammarks/view/{id}', [TExammarkController::class,'view']);
Route::get( '/exammarks/export/{id}', [TExammarkController::class,'Export']);

//Mark
Route::get( '/marks/view/{standard_id}', [TMarkController::class,'view']);
Route::get( '/marks/show', [TMarkController::class,'show']);
Route::get( '/marks/viewmark/{standard_id}/{user_id}/{exam_id}/{academic_year_id}', [TMarkController::class,'viewmark'] );
