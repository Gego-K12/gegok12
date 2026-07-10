<?php
use Illuminate\Support\Facades\Route;
use Gegok12\Alumni\Http\Controllers\Admin\AlumniController;

// Required from the end of routes/admin.php, so these inherit the admin
// portal's prefix('admin') + middleware(['web','auth','schooladmin','privilegeconditions'])
// group set up in app/Providers/RouteServiceProvider.php. Using
// [Controller::class, 'method'] array syntax (rather than bare
// 'Controller@method' strings) bypasses that group's
// ->namespace('App\Http\Controllers\Admin') setting, so the controller can
// stay in the package's own namespace.

Route::get( '/alumni/find', [AlumniController::class,'find' ]);
Route::get( '/alumni', [AlumniController::class,'index' ]);
Route::get( '/alumni/getdate', [AlumniController::class,'getDate' ]);
Route::get( '/alumni/add', [AlumniController::class,'create' ]);
Route::post( '/alumni/add', [AlumniController::class,'store' ]);

Route::post( '/alumni/add/validationProfile', [AlumniController::class,'validationProfile' ]);
Route::post( '/alumni/add/validationEducation', [AlumniController::class,'validationEducation' ]);
Route::post( '/alumni/add/validationJob', [AlumniController::class,'validationJob' ]);
Route::post( '/alumni/add/validationContact', [AlumniController::class,'validationContact' ]);

Route::get( '/alumni/show/details/{name}', [AlumniController::class,'showDetails' ]);
Route::get( '/alumni/show/{name}', [AlumniController::class,'show' ]);
