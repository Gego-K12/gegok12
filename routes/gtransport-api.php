<?php
use Illuminate\Support\Facades\Route;
use Gegok12\Transport\Http\Controllers\Api\Transport\LoginController;
use Gegok12\Transport\Http\Controllers\Api\Transport\TransportServiceController;
use Gegok12\Transport\Http\Controllers\Api\Transport\RouteController;
use Gegok12\Transport\Http\Controllers\Api\Transport\CoordinatorController;
use Gegok12\Transport\Http\Controllers\Api\Transport\TransportDriverController;

Route::post('/coordinator/login', [LoginController::class,'login']);
Route::post('/transportdriver/login', [LoginController::class,'loginDriver']);

Route::group([
    'middleware' => ['auth:sanctum'],
], function () {
Route::post('/transport/logout', [LoginController::class,'logout']);
});

Route::group([
    'prefix' => 'v2', 
    'middleware' => ['auth:sanctum'],
], function () {
 //Current Lat Lng in Parent App
    //Transport Module
    //Route::get('/transport/current/trip/list', [TransportDriverController::class,'currentTripLatLng']);
    Route::get('/transport/locationlist/{user_id}', [TransportServiceController::class,'latLngList']);
    
    Route::get('/transport/tripactivestatus', [TransportServiceController::class,'tripActiveStatus']);

    });

Route::group([
    //'prefix' => 'v2', 
    'middleware' => ['auth:sanctum'], 
], function () {


Route::get('/transport/route/list',[RouteController::class,'index']); 
Route::get('/transport/studentroute/list/{route_id}', [RouteController::class,'studentList']);
Route::get('/transport/stoppage/list/{route_id}', [RouteController::class,'stoppageList']);
Route::get('/transport/section/list', [CoordinatorController::class,'sectionList']);
Route::get('/transport/standard/list', [CoordinatorController::class,'standardList']);
Route::post('/transport/studentattendance', [CoordinatorController::class,'studentAttendance']);
Route::get('/transport/studentattendance/list/{route_id}/{stop_id}/{type}', [CoordinatorController::class,'list']);
});


Route::group([
    //'prefix' => 'v2', 
    'namespace' =>'Api',
    'middleware' => ['auth:sanctum'], 
], function () {

Route::get('/transport/category/list', [TransportDriverController::class,'categoryList']);
Route::post('/transport/vehicle/start', [TransportDriverController::class,'vehicleStart']);
//Route::post('/transport/vehicle/end', [TransportDriverController::class,'vehicleEnd']);
Route::post('/transport/vehicle/canceltrip', [TransportDriverController::class,'tripCancel']);
Route::get('/transport/triplist/{route_id}/{driver_id}', [TransportDriverController::class,'tripList']);
Route::get('/transport/triplist/cancel/{route_id}/{driver_id}', [TransportDriverController::class,'cancelledTripList']);
//Route::get('/transport/current/triplist/{service_id}', [TransportDriverController::class,'currentTrip');

Route::get('/transport/currenttrip/status', [TransportDriverController::class,'tripStatus']);
Route::get('/transport/service/list', [TransportDriverController::class,'servicesList']);

Route::post('/transport/servicedetail/{service_id}',[TransportServiceController::class,'store']); 
//Route::post('/transport/addservice',[TransportServiceController::class,'addTransportService']); 
Route::get('/transport/vehicle/list',[TransportServiceController::class,'vehicleList']);
Route::get('/transport/currentdate/triplist',[TransportDriverController::class,'currentDateTripList']); 
Route::get('/transport/vehicle-service-route-list',[TransportDriverController::class,'vehicleServiceRouteList']); 

});