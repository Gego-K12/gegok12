<?php
use Illuminate\Support\Facades\Route;
use Gegok12\Videoroom\Http\Controllers\Student\VideoConferencesController;

// Required from the end of routes/student.php, so these inherit the
// student portal's prefix('student') + middleware(['web','auth','student'])
// group set up in app/Providers/RouteServiceProvider.php.

/*video conference*/
Route::get('/video-conference', [VideoConferencesController::class,'index']);
Route::get('/video-conference/{slug}', [VideoConferencesController::class,'show']);
