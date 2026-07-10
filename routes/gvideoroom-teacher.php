<?php
use Illuminate\Support\Facades\Route;
use Gegok12\Videoroom\Http\Controllers\Teacher\VideoConferencesController;

// Required from the end of routes/teacher.php, so these inherit the
// teacher portal's prefix('teacher') + middleware(['web','auth','teacher'])
// group set up in app/Providers/RouteServiceProvider.php.

/*video conference*/
Route::get('/video-conference', [VideoConferencesController::class,'index']);
Route::get('/video-conference/create', [VideoConferencesController::class,'create']);
Route::post('/video-conference/save', [VideoConferencesController::class,'store']);
Route::get('/video-conference/{slug}', [VideoConferencesController::class,'show']);
Route::get('/video-conference/edit/{id}', [VideoConferencesController::class,'edit']);
Route::post('/video-conference/update/{id}', [VideoConferencesController::class,'update']);
Route::get('/video-conference/remove/{id}', [VideoConferencesController::class,'remove']);
Route::get('/video-conference/manage-invites/{id}', [VideoConferencesController::class,'invites']);
Route::get('/video-conference/remove-users/{id}', [VideoConferencesController::class,'removeUsers']);
Route::get('/video-conference/status/{id}', [VideoConferencesController::class,'statusUpdate']);
Route::get('/video-conference/recordings/{id}', [VideoConferencesController::class,'recordings']);
Route::post('/video-conference/student-list', [VideoConferencesController::class,'studentInfo']);
Route::post('/video-conference/subject-list', [VideoConferencesController::class,'subjectInfo']);
Route::get('/video-conference/add-invites/{id}', [VideoConferencesController::class,'addinvites']);
Route::post('/video-conference/save-invites/{id}', [VideoConferencesController::class,'saveinvites']);
Route::get('/video-conference/editlist/{id}', [VideoConferencesController::class,'editlist']);
Route::get('/video-conference/show/{id}', [VideoConferencesController::class,'showduration']);
