<?php

use App\Http\Controllers\Academy\LearnerController;
use App\Http\Controllers\Academy\GroupLessonController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('learning/academy')->group(function () {
    Route::get('/test-accounts', [\App\Http\Controllers\Academy\TeacherWorkspaceController::class, 'testAccounts']);
    Route::get('/teacher/leaves', [\App\Http\Controllers\Academy\TeacherWorkspaceController::class, 'leaves']);
    Route::post('/teacher/leaves', [\App\Http\Controllers\Academy\TeacherWorkspaceController::class, 'requestLeave']);
    Route::post('/teacher/leaves/{leave}/review', [\App\Http\Controllers\Academy\TeacherWorkspaceController::class, 'review'])->whereNumber('leave');
    Route::post('/teacher/leaves/{leave}/withdraw', [\App\Http\Controllers\Academy\TeacherWorkspaceController::class, 'withdraw'])->whereNumber('leave');
    Route::get('/teacher/attendance', [\App\Http\Controllers\Academy\TeacherWorkspaceController::class, 'attendance']);
    Route::post('/teacher/attendance', [\App\Http\Controllers\Academy\TeacherWorkspaceController::class, 'markAttendance']);

    Route::get('/', [LearnerController::class, 'index']);
    Route::get('/accounts/new/{type}', [LearnerController::class, 'create']);
    Route::post('/accounts/new/{type}', [LearnerController::class, 'store']);
    Route::get('/groups', [LearnerController::class, 'groups']);
    Route::get('/groups/{group}', [GroupLessonController::class, 'show'])->whereNumber('group');
    Route::post('/groups/{group}/students', [GroupLessonController::class, 'addStudents'])->whereNumber('group');
    Route::post('/groups/{group}/lessons', [GroupLessonController::class, 'start'])->whereNumber('group');
    Route::get('/groups/{group}/lessons/{lesson}', [GroupLessonController::class, 'show'])->whereNumber(['group', 'lesson']);
    Route::post('/groups/{group}/lessons/{lesson}', [GroupLessonController::class, 'save'])->whereNumber(['group', 'lesson']);
    Route::post('/groups', [LearnerController::class, 'saveGroup']);
    Route::post('/groups/{group}', [LearnerController::class, 'saveGroup'])->whereNumber('group');
    Route::post('/groups/{group}/archive', [LearnerController::class, 'archiveGroup'])->whereNumber('group');
    Route::get('/activity', [LearnerController::class, 'activity']);
    Route::get('/students/{student}', [LearnerController::class, 'show'])->whereNumber('student');
    Route::post('/students/{student}/details', [LearnerController::class, 'updateProfile'])->whereNumber('student');
    Route::post('/students/{student}/parent/unlink', [LearnerController::class, 'unlinkParent'])->whereNumber('student');
    Route::post('/students/{student}/placement', [LearnerController::class, 'placement'])->whereNumber('student');
    Route::post('/students/{student}/parent', [LearnerController::class, 'parent'])->whereNumber('student');
    Route::post('/students/{student}/group', [LearnerController::class, 'assign'])->whereNumber('student');
    Route::post('/students/{student}/feedback', [LearnerController::class, 'feedback'])->whereNumber('student');
    Route::post('/students/{student}/message', [GroupLessonController::class, 'message'])->whereNumber('student');
    Route::post('/students/{student}/feedback/{feedback}/read', [LearnerController::class, 'readFeedback'])->whereNumber(['student', 'feedback']);
});

Route::get('/parent/dashboard', function () {
    abort_unless((int) auth()->user()->usergroup_id === 7, 403);
    return redirect('/learning/academy');
})->middleware('auth');
