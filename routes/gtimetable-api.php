<?php
use Illuminate\Support\Facades\Route;
use Gegok12\Timetable\Http\Controllers\Api\Teacher\TimetableController;

// Required from the end of routes/api.php, inheriting the api portal's
// prefix('api') + middleware('api') group from RouteServiceProvider.
// Note: an earlier, never-finished /v2/timetable/{student_id} route
// referencing a nonexistent Api\TimetableController was dropped here
// rather than migrated — nothing calls it and there's no reliable way
// to implement it without guessing at a student-to-class lookup.

Route::group([
    'prefix' => 'teacher',
    'middleware' => ['auth:sanctum'],
], function () {
    Route::get('/timetable', [TimetableController::class, 'index']);
});
