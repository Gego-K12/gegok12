<?php
use Illuminate\Support\Facades\Route;
use Gegok12\Timetable\Http\Controllers\Admin\TimeTable\TimeTableCreatorController;
use Gegok12\Timetable\Http\Controllers\Admin\TimetableController;

// Required from the end of routes/admin.php, so these inherit the admin
// portal's prefix('admin') + middleware(['web','auth','schooladmin','privilegeconditions'])
// group set up in app/Providers/RouteServiceProvider.php. Using
// [Controller::class, 'method'] array syntax (rather than bare
// 'Controller@method' strings) bypasses that group's
// ->namespace('App\Http\Controllers\Admin') setting, so the controller can
// stay in the package's own namespace.

// TimeTable Creator
Route::get('/timetable/detail', [TimeTableCreatorController::class, 'detail']);
Route::get('/timetable/creator', [TimeTableCreatorController::class, 'creator']);
Route::get('/timetable/teacher', [TimeTableCreatorController::class, 'teachertimetable']);
Route::get('/timetable/class', [TimeTableCreatorController::class, 'classtimetable']);
Route::get('/timetable/day', [TimeTableCreatorController::class, 'daytimetable']);
Route::get('/timetable/work_allotment', [TimeTableCreatorController::class, 'work_allotment']);

// Timetable
// add
Route::get('/timetable/list', [TimetableController::class, 'list']);
Route::get('/timetable/add', [TimetableController::class, 'create']);
Route::post('/timetable/add', [TimetableController::class, 'store']);
// edit
Route::get('/timetable/edit/list/{standardLink_id}', [TimetableController::class, 'show']);
Route::get('/timetable/edit/{standardLink_id}', [TimetableController::class, 'edit']);
Route::post('/timetable/edit/{standardLink_id}', [TimetableController::class, 'update']);
