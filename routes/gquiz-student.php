<?php
use Illuminate\Support\Facades\Route;
use Gegok12\Quiz\Http\Controllers\Student\Quiz\QuizController;
use Gegok12\Quiz\Http\Controllers\Student\Quiz\AnswerController;

// Required from the end of routes/student.php, so these inherit the
// student portal's prefix('student') + middleware(['web','auth','student'])
// group set up in app/Providers/RouteServiceProvider.php.

Route::get('/quiz', [QuizController::class, 'index']);
Route::get('/quiz/show', [QuizController::class, 'list']);
Route::get('/quiz/test/{id}/show', [QuizController::class, 'show']);
Route::get('/quiz/test/{id}/question', [AnswerController::class, 'index']);
Route::post('quiz/answer/add', [AnswerController::class, 'store']);
Route::get('quiz/test/{id}/review', [AnswerController::class, 'show']);
Route::get('quiz/test/{id}/details', [AnswerController::class, 'showlist']);
