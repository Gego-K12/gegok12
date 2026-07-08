<?php

use Gegok12\ShortTermCourses\Http\Controllers\Teacher\CourseAttendanceController;
use Gegok12\ShortTermCourses\Http\Controllers\Teacher\ShortTermCoursesController;
use Illuminate\Support\Facades\Route;

// Required from the end of routes/teacher.php, so this inherits
// that portal's prefix/middleware group set up in
// app/Providers/RouteServiceProvider.php. Using [Controller::class,
// 'method'] array syntax bypasses that group's own ->namespace()
// setting, so the controller can stay in the package's own namespace.

Route::get('/short-term-courses', [ShortTermCoursesController::class, 'index']);

Route::prefix('short-term-courses')->group(function () {
    Route::get('/my-batches', [ShortTermCoursesController::class, 'myBatches']);

    Route::get('/batches/{batch}/sessions', [CourseAttendanceController::class, 'sessions']);
    Route::get('/sessions/{session}/roster', [CourseAttendanceController::class, 'roster']);
    Route::post('/sessions/{session}/mark', [CourseAttendanceController::class, 'markSession']);
});
