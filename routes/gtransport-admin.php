<?php
use Illuminate\Support\Facades\Route;
use Gegok12\Transport\Http\Controllers\Admin\Transport\StoppageController;
use Gegok12\Transport\Http\Controllers\Admin\Transport\VehicleRouteController;
use Gegok12\Transport\Http\Controllers\Admin\Transport\RouteVehicleController;
use Gegok12\Transport\Http\Controllers\Admin\Transport\VehicleController;
use Gegok12\Transport\Http\Controllers\Admin\Transport\VehicleDriverController;
use Gegok12\Transport\Http\Controllers\Admin\Transport\VehicleDocumentController;
use Gegok12\Transport\Http\Controllers\Admin\Transport\TransportDetailController;
use Gegok12\Transport\Http\Controllers\Admin\Transport\TransportServiceController;
use Gegok12\Transport\Http\Controllers\Admin\Transport\StudentRouteController;
use Gegok12\Transport\Http\Controllers\Admin\BusPassController;

// Required from the end of routes/admin.php, so these inherit the admin
// portal's prefix('admin') + middleware(['web','auth','schooladmin','privilegeconditions'])
// group set up in app/Providers/RouteServiceProvider.php. Using
// [Controller::class, 'method'] array syntax (rather than bare
// 'Controller@method' strings) bypasses that group's
// ->namespace('App\Http\Controllers\Admin') setting, so the controller can
// stay in the package's own namespace.

// Transport
Route::get('/transport/stoppage/add', [StoppageController::class, 'create']);
Route::get('/transport/stoppage', [StoppageController::class, 'index']);
Route::get('/transport/stoppage/list', [StoppageController::class, 'list']);
Route::post('/transport/stoppage/create', [StoppageController::class, 'store']);
Route::get('/transport/stoppage/{id}/edit', [StoppageController::class, 'show']);
Route::get('/transport/stoppage/edit/{id}', [StoppageController::class, 'edit']);
Route::post('/transport/stoppage/{id}/update', [StoppageController::class, 'update']);
Route::delete('/transport/stoppage/{id}/delete', [StoppageController::class, 'destroy']);

Route::get('/transport/route', [VehicleRouteController::class, 'index']);
Route::get('/transport/route/list', [VehicleRouteController::class, 'list']);
Route::get('/transport/route/create', [VehicleRouteController::class, 'create']);
Route::post('/transport/route/store', [VehicleRouteController::class, 'store']);
Route::get('/transport/route/{id}/edit', [VehicleRouteController::class, 'edit']);
Route::get('/transport/route/{id}/show', [VehicleRouteController::class, 'show']);
Route::get('/transport/route/{id}/editshow', [VehicleRouteController::class, 'editshow']);

Route::post('/transport/route/{id}/update', [VehicleRouteController::class, 'update']);
Route::delete('/transport/route/{id}/delete', [VehicleRouteController::class, 'destroy']);
Route::get('/transport/route/getlist', [VehicleRouteController::class, 'getlist']);
Route::get('/transport/route/driverlist', [VehicleRouteController::class, 'driverlist']);

Route::post('/transport/route/vehicle/create', [RouteVehicleController::class, 'store']);
Route::get('/transport/route/{id}/vehicle', [RouteVehicleController::class, 'show']);
Route::delete('/transport/route/vehicle/{id}/delete', [RouteVehicleController::class, 'destroy']);

Route::get('/transport/vehicle', [VehicleController::class, 'index']);
Route::get('/transport/vehicle/list', [VehicleController::class, 'list']);
Route::get('/transport/vehicle/create', [VehicleController::class, 'create']);
Route::post('/transport/vehicle/store', [VehicleController::class, 'store']);
Route::get('/transport/vehicle/{id}/edit', [VehicleController::class, 'edit']);
Route::get('/transport/vehicle/{id}/editshow', [VehicleController::class, 'editshow']);
Route::post('/transport/vehicle/{id}/update', [VehicleController::class, 'update']);
Route::delete('/transport/vehicle/{id}/delete', [VehicleController::class, 'destroy']);
Route::get('/transport/vehicle/{id}/show', [VehicleController::class, 'show']);

Route::post('/transport/vehicle/driver/create', [VehicleDriverController::class, 'store']);
Route::post('/transport/vehicle/route/create', [VehicleDriverController::class, 'addroute']);
Route::get('/transport/vehicle/driver/{id}/show', [VehicleDriverController::class, 'show']);

Route::get('/transport/vehicle/{id}/document/list', [VehicleDocumentController::class, 'index']);
Route::get('/transport/vehicle/document/{id}/edit', [VehicleDocumentController::class, 'edit']);
Route::delete('/transport/vehicle/document/{id}/delete', [VehicleDocumentController::class, 'destroy']);
Route::post('/transport/vehicle/document/{id}/update', [VehicleDocumentController::class, 'update']);
Route::post('/transport/vehicle/{id}/document/add', [VehicleDocumentController::class, 'store']);

Route::get('/transport/detail/create', [TransportDetailController::class, 'create']);
Route::get('/transport/detail/list', [TransportDetailController::class, 'list']);
Route::post('/transport/detail/add', [TransportDetailController::class, 'store']);
Route::get('/transport/route/{id}/memberslist', [TransportDetailController::class, 'memberslist']);

Route::get('/transport/service', [TransportServiceController::class, 'index']);
Route::get('/transport/service/create', [TransportServiceController::class, 'create']);
Route::post('/transport/service/add', [TransportServiceController::class, 'store']);
Route::get('/transport/service/{id}/show', [TransportServiceController::class, 'show']);

Route::delete('/transport/service/{id}/delete', [TransportServiceController::class, 'destroy']);

Route::get('/transport/details/show/{name}', [TransportDetailController::class, 'show']);

Route::get('/transport/studentroute/create', [StudentRouteController::class, 'create']);
Route::get('/transport/driverlist', [StudentRouteController::class, 'driverList']);
Route::get('/transport/studentsattendance/list', [StudentRouteController::class, 'studentsAttendanceList']);

// Bus Pass
Route::post('student/buspass', [BusPassController::class, 'create']);
Route::get('student/buspass/show/{id}', [BusPassController::class, 'show']);
Route::get('student/buspass/print/{id}', [BusPassController::class, 'print_buspass']);
