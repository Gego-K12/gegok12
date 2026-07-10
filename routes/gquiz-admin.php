<?php
use Illuminate\Support\Facades\Route;
use Gegok12\Quiz\Http\Controllers\Test\ChapterContorller;
use Gegok12\Quiz\Http\Controllers\Test\QuestionHeadController;
use Gegok12\Quiz\Http\Controllers\Test\TestQuestionController;
use Gegok12\Quiz\Http\Controllers\Test\TestController;
use Gegok12\Quiz\Http\Controllers\Test\TestPatternController;
use Gegok12\Quiz\Http\Controllers\Test\TestPaperController;
use Gegok12\Quiz\Http\Controllers\Test\QuestionImportController;

// Required from the end of routes/admin.php, so these inherit the admin
// portal's prefix('admin') + middleware(['web','auth','schooladmin','privilegeconditions'])
// group set up in app/Providers/RouteServiceProvider.php. Using
// [Controller::class, 'method'] array syntax (rather than bare
// 'Controller@method' strings) bypasses that group's
// ->namespace('App\Http\Controllers\Admin') setting, so the controller can
// stay in the package's own namespace.

Route::get('/subject/chapters', [ChapterContorller::class, 'index']);

Route::get('/subject/chapter/{subject_id}/show', [ChapterContorller::class, 'subjectshow']);
Route::get('/subject/chapter/{subject_id}/list', [ChapterContorller::class, 'chapterlist']);

Route::get('/chapter/list', [ChapterContorller::class, 'list']);
Route::get('/chapter/create', [ChapterContorller::class, 'create']);
Route::get('/chapter/create/list', [ChapterContorller::class, 'createlist']);
Route::post('/chapter/create', [ChapterContorller::class, 'store']);
Route::post('/chapter/{id}/update', [ChapterContorller::class, 'update']);
Route::get('/chapter/show/{id}', [ChapterContorller::class, 'show']);
Route::get('/chapter/show/{id}/list', [ChapterContorller::class, 'showlist']);
Route::get('/chapter/{id}/delete', [ChapterContorller::class, 'destroy']);

Route::get('/question/head/{subject_id}', [QuestionHeadController::class, 'index']);
Route::post('/question/head/create', [QuestionHeadController::class, 'store']);
Route::get('/question/head/{id}/delete', [QuestionHeadController::class, 'destroy']);

Route::get('/chapter/{id}/question', [TestQuestionController::class, 'create']);
Route::get('/test/question/{chapter_id}/list', [TestQuestionController::class, 'showlist']);
Route::post('/test/question/create', [TestQuestionController::class, 'store']);
Route::get('/test/question/{question_id}/edit', [TestQuestionController::class, 'edit']);
Route::get('/test/question/{question_id}/show', [TestQuestionController::class, 'show']);
Route::post('/test/question/{question_id}/update', [TestQuestionController::class, 'update']);
Route::get('/test/question/{question_id}/delete', [TestQuestionController::class, 'destroy']);

Route::get('/test/pattern', [TestController::class, 'index']);
Route::get('/test/paper/list', [TestController::class, 'list']);
Route::post('/test/create', [TestController::class, 'store']);
Route::get('/test/{id}/edit', [TestController::class, 'edit']);
Route::get('/test/show/{id}', [TestController::class, 'show']);
Route::post('/test/{id}/update', [TestController::class, 'update']);

Route::get('/test/{id}/pattern', [TestPatternController::class, 'index']);
Route::get('/test/pattern/{id}/create', [TestPatternController::class, 'create']);
Route::post('/test/pattern/{id}', [TestPatternController::class, 'store']);
Route::get('/test/pattern/{id}/edit', [TestPatternController::class, 'edit']);
Route::post('/test/pattern/{id}/update', [TestPatternController::class, 'update']);
Route::get('/test/pattern/{id}/delete', [TestPatternController::class, 'destroy']);
Route::get('/test/pattern/{test_id}/list', [TestPatternController::class, 'list']);
Route::get('/test/import/{subject_id}/list', [TestPatternController::class, 'subjectlist']);
Route::get('/test/page/{chapter_id}/list', [TestPatternController::class, 'getPageList']);

Route::post('/test/question/counts', [TestPatternController::class, 'updatecount']);

Route::get('/test/generate/{test_id}', [TestPaperController::class, 'index']);
Route::get('/test/paper/{test_id}/delete', [TestPaperController::class, 'destroy']);

Route::get('/test/question/paper/{test_id}', [TestPaperController::class, 'list']);
Route::get('/test/downloadformat/questions', [QuestionImportController::class, 'downloadFormat']);
Route::post('/test/questions/import', [QuestionImportController::class, 'importQuestions']);
