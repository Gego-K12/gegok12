<?php
use Illuminate\Support\Facades\Route;
use Gegok12\Exam\Http\Controllers\Admin\ExamRuleController;
use Gegok12\Exam\Http\Controllers\Admin\ExamGradeController;
use Gegok12\Exam\Http\Controllers\Admin\ExamController;
use Gegok12\Exam\Http\Controllers\Admin\ExamScheduleController;
use Gegok12\Exam\Http\Controllers\Admin\ExammarkController;
use Gegok12\Exam\Http\Controllers\Admin\MarksController;

// Required from the end of routes/admin.php, so these inherit the admin
// portal's prefix('admin') + middleware(['web','auth','schooladmin','privilegeconditions'])
// group set up in app/Providers/RouteServiceProvider.php. Using
// [Controller::class, 'method'] array syntax (rather than bare
// 'Controller@method' strings) bypasses that group's
// ->namespace('App\Http\Controllers\Admin') setting, so the controller can
// stay in the package's own namespace.

//examrule
Route::get( '/examrules', [ExamRuleController::class,'create']);
Route::post( '/examrules', [ExamRuleController::class,'store']);

//grade
Route::get( '/exam/grade', [ExamGradeController::class,'index']);
Route::get( '/exam/grade/list', [ExamGradeController::class,'list']);
Route::get( '/exam/grade/{id}', [ExamGradeController::class,'show']);
Route::post( '/exam/grade/{id}', [ExamGradeController::class,'update']);

//exam
Route::get( '/exam/list', [ExamController::class,'list' ]);
Route::get( '/exam/add', [ExamController::class,'create' ]);
Route::post( '/exam/add', [ExamController::class,'store' ]);
Route::get( '/exam', [ExamController::class,'index' ]);
Route::get( '/exam/show', [ExamController::class,'show' ]);
Route::get( '/exam/edit/{id}', [ExamController::class,'edit' ]);
Route::post( '/exam/update/{id}', [ExamController::class,'update' ]);
Route::get( '/exam/delete/{id}', [ExamController::class,'destroy' ]);
Route::get( '/exam/report/{id}', [ExamController::class,'report' ]);
Route::get( '/exam/reportdownload/{id}', [ExamController::class,'downloadreport' ]);
Route::get( '/exam/sendreport/{id}', [ExamController::class,'sendreport' ]);
Route::get( '/exam/hallticket/{id}', [ExamController::class,'hallticket' ]);

//exam-schedule
Route::get( '/examschedule/list/{id}', [ExamScheduleController::class,'list']);
Route::get( '/examschedule/add/{id}', [ExamScheduleController::class,'create']);
Route::post( '/examschedule/add/{id}', [ExamScheduleController::class,'store']);
Route::get( '/examschedule', [ExamScheduleController::class,'index']);
Route::get( '/examschedule/show', [ExamScheduleController::class,'show']);
Route::get( '/examschedule/edit/{id}',[ExamScheduleController::class,'edit']);
Route::post( '/examschedule/update/{id}',[ExamScheduleController::class,'update']);
Route::get( '/examschedule/delete/{id}',[ExamScheduleController::class,'destroy']);

//exammark
Route::get( '/exammarks/list/{id}', [ExammarkController::class,'list' ]);
Route::get( '/exammarks/{id}', [ExammarkController::class,'create' ]);
Route::post( '/exammarks/{id}', [ExammarkController::class,'sampleDownload' ]);
Route::post( '/importMarks', [ExammarkController::class,'importMarks' ]);
Route::get( '/exammarks/show/{id}', [ExammarkController::class,'view' ]);
Route::get( '/exammarks/view/{id}', [ExammarkController::class,'show' ]);
Route::get( '/exammarks/export/{id}', [ExammarkController::class,'Export' ]);

//marks
Route::get( '/marks/list', [MarksController::class,'list' ]);
Route::get( '/marks/add', [MarksController::class,'create' ]);
Route::post( '/marks/add', [MarksController::class,'store' ]);
Route::get( '/marks/view/{standard_id}', [MarksController::class,'view' ]);
Route::get( '/marks/show', [MarksController::class,'show' ]);
Route::get( '/marks/viewmark/{standard_id}/{user_id}/{exam_id}/{academic_year_id}', [MarksController::class,'viewmark' ]);
