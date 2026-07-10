<?php
use Illuminate\Support\Facades\Route;
use Gegok12\Quiz\Http\Controllers\Teacher\Quiz\QuizController;
use Gegok12\Quiz\Http\Controllers\Teacher\Quiz\ParticipantController;
use Gegok12\Quiz\Http\Controllers\Teacher\Quiz\ParticipantDetailController;
use Gegok12\Quiz\Http\Controllers\Teacher\Quiz\QuestionController;

// Required from the end of routes/teacher.php, so these inherit the
// teacher portal's prefix('teacher') + middleware(['web','auth','teacher'])
// group set up in app/Providers/RouteServiceProvider.php.

// Quiz topic
Route::get('/quiz', [QuizController::class, 'index']);
Route::get('/quiz/list', [QuizController::class, 'showlist']);
Route::post('/quiz/add', [QuizController::class, 'store']);
Route::get('/quiz/{id}/edit', [QuizController::class, 'edit']);
Route::post('/quiz/{id}/update', [QuizController::class, 'update']);
Route::delete('/quiz/{id}/delete', [QuizController::class, 'destroy']);

// Quiz questions
Route::get('/quiz/{id}/show', [QuizController::class, 'show']);
Route::get('/quiz/test/{id}/details', [ParticipantController::class, 'showlist']);
Route::get('/quiz/test/{id}/show', [ParticipantController::class, 'show']);
Route::get('/quiz/{id}/questions', [QuestionController::class, 'showlist']);
Route::get('/quiz/question/list', [QuestionController::class, 'list']);
Route::get('/quiz/{id}/question/add', [QuestionController::class, 'create']);
Route::post('/quiz/question', [QuestionController::class, 'store']);
Route::get('/quiz/question/{id}/edit', [QuestionController::class, 'edit']);
Route::get('/quiz/question/{id}/show', [QuestionController::class, 'show']);
Route::post('/quiz/question/{id}/update', [QuestionController::class, 'update']);
Route::delete('/quiz/question/{id}/delete', [QuestionController::class, 'destroy']);

// Quiz participants
Route::get('quiz/{id}/participants/add', [ParticipantController::class, 'create']);
Route::get('quiz/participants/list', [ParticipantController::class, 'list']);
Route::get('quiz/{id}/assign', [ParticipantController::class, 'index']);
Route::post('quiz/participants', [ParticipantController::class, 'store']);
Route::delete('quiz/participant/{id}/delete', [ParticipantController::class, 'destroy']);
Route::get('quiz/{id}/participants', [ParticipantDetailController::class, 'show']);
