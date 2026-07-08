<?php

use Gegok12\ShortTermCourses\Http\Controllers\Student\ShortTermCoursesController;
use Illuminate\Support\Facades\Route;

// Required from the end of routes/student.php, so this inherits
// that portal's prefix/middleware group set up in
// app/Providers/RouteServiceProvider.php. Using [Controller::class,
// 'method'] array syntax bypasses that group's own ->namespace()
// setting, so the controller can stay in the package's own namespace.

Route::get('/short-term-courses', [ShortTermCoursesController::class, 'index']);

Route::prefix('short-term-courses')->group(function () {
    Route::get('/my-invitations', [ShortTermCoursesController::class, 'myInvitations']);
    Route::post('/invitations/{invitation}/respond', [ShortTermCoursesController::class, 'respondToInvitation']);
    Route::get('/my-enrollments', [ShortTermCoursesController::class, 'myEnrollments']);
});
