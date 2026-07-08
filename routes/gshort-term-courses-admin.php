<?php

use Gegok12\ShortTermCourses\Http\Controllers\Admin\CourseController;
use Gegok12\ShortTermCourses\Http\Controllers\Admin\ShortTermCoursesController;
use Illuminate\Support\Facades\Route;

// Required from the end of routes/admin.php, so this inherits
// that portal's prefix/middleware group set up in
// app/Providers/RouteServiceProvider.php. Using [Controller::class,
// 'method'] array syntax bypasses that group's own ->namespace()
// setting, so the controller can stay in the package's own namespace.

Route::get('/short-term-courses', [ShortTermCoursesController::class, 'index']);

Route::prefix('short-term-courses')->group(function () {
    // All course/batch/instructor/invitation/enrollment management is
    // handled by the Livewire components (CourseManager, BatchManager,
    // CourseShow) calling the plugin's services directly — this plain
    // route just resolves the dedicated course detail page they live on.
    Route::get('/courses/{course}', [CourseController::class, 'show']);
});
