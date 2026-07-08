<?php

use Gegok12\WorkPermission\Http\Controllers\Teacher\WorkPermissionController;
use Illuminate\Support\Facades\Route;

// Required from the end of routes/teacher.php, so these inherit the teacher
// portal's prefix('teacher') + middleware(['web','auth','teacher']) group
// set up in app/Providers/RouteServiceProvider.php. Using [Controller::class, 'method']
// array syntax (rather than bare 'Controller@method' strings) bypasses that
// group's ->namespace('App\Http\Controllers\Teacher') setting, so the
// controller can stay in the package's own namespace.

Route::get('/workpermission/list', [WorkPermissionController::class, 'list']);
Route::get('/workpermission/pendingCount', [WorkPermissionController::class, 'pendingCount']);
Route::get('/workpermissions', [WorkPermissionController::class, 'index']);

Route::get('/workpermission/add', [WorkPermissionController::class, 'create']);
Route::post('/workpermission/add', [WorkPermissionController::class, 'store']);

Route::get('/workpermission/show/{id}', [WorkPermissionController::class, 'view']);
Route::get('/workpermission/delete/{id}', [WorkPermissionController::class, 'destroy']);

Route::group(['middleware' => ['role:leave_checker']], function () {
    Route::get('/workpermission/mylist', [WorkPermissionController::class, 'myList']);
    Route::get('/mypermissions', [WorkPermissionController::class, 'myIndex']);

    Route::get('/workpermission/approve/list/{id}', [WorkPermissionController::class, 'approveList']);
    Route::get('/workpermission/approve/{id}', [WorkPermissionController::class, 'approveCreate']);
    Route::post('/workpermission/approve/{id}', [WorkPermissionController::class, 'approveStore']);

    Route::get('/workpermission/reject/{id}', [WorkPermissionController::class, 'rejectCreate']);
    Route::post('/workpermission/reject/{id}', [WorkPermissionController::class, 'rejectStore']);
});
